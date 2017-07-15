<?php
final class Friends_Request extends GWF_MethodForm
{
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	public function createForm(GWF_Form $form)
	{
		$gdo = GWF_FriendRequest::table();
		$form->addFields(array(
			GDO_User::make('frq_friend')->notNull()->validator([$this, 'validate_NoRelation']),
			$gdo->gdoColumn('frq_relation'),
			GDO_Submit::make(),
			GDO_AntiCSRF::make(),
		));
	}
	
	public function execute()
	{
		$response = parent::execute();
		return Module_Friends::instance()->renderTabs()->add($response);
	}
	
	public function validate_NoRelation(GWF_Form $form, GDO_User $field)
	{
		$user = GWF_User::current();
		$friend = $field->getUser();
		if ($friend->getID() === $user->getID())
		{
			return $field->error('err_friend_self');
		}
		if (GWF_Friendship::areRelated($user, $friend))
		{
			return $field->error('err_already_related', [$friend->displayNameLabel()]);
		}
		if ($request = GWF_FriendRequest::getPendingFor($user, $friend))
		{
			if ($request->isDenied())
			{
				return $field->error('err_already_pending_denied', [$friend->displayNameLabel()]);
			}
			else
			{
				return $field->error('err_already_pending', [$friend->displayNameLabel()]);
			}
		}
		return true;
	}
	
	public function formValidated(GWF_Form $form)
	{
		$user = GWF_User::current();
		$request = GWF_FriendRequest::blank($form->values())->setVar('frq_user', $user->getID())->insert();
		
		$this->sendMail($request);
		
		GWF_Hook::call('FriendsRequest', $request);
		
		return $this->message('msg_friend_request_sent');
	}
	
	private function sendMail(GWF_FriendRequest $request)
	{
		$mail = new GWF_Mail();
		$mail->setSender(GWF_BOT_EMAIL);
		$mail->setSenderName(GWF_BOT_NAME);
		
		$friend = $request->getFriend();
		$user = $request->getUser();
		$relation = GDO_FriendRelation::displayRelation($request->getRelation());
		$sitename = $this->getSiteName();
		$append = "&from={$user->getID()}&for={$friend->getID()}&token={$request->gdoHashcode()}";
		$linkAccept = GWF_HTML::anchor(url('Friends', 'Accept', $append));
		$linkDeny = GWF_HTML::anchor(url('Friends', 'Deny', $append));
		
		$mail->setSubject(tusr($friend, 'mail_subj_friend_request', [$sitename, $user->displayNameLabel()]));
		$args = [$friend->displayNameLabel(), $user->displayNameLabel(), $relation, $sitename, $linkAccept, $linkDeny];
		$mail->setBody(tusr($friend, 'mail_body_friend_request', $args));
		
		$mail->sendToUser($friend);
	}
}
