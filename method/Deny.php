<?php
final class Friends_Deny extends GWF_MethodFriendRequest
{
	public function executeWithRequest(GWF_FriendRequest $request)
	{
		$request->saveVar('frq_denied', GWF_Time::getDate());
		
		$this->sendMail($request);
		
		$tabs = Module_Friends::instance()->renderTabs();
		$response = $this->message('msg_friends_denied');
		$redirect = GWF_Website::redirect(href('Friends', 'Requests'));
		
		return $tabs->add($response)->add($redirect);
	}
	
	private function sendMail(GWF_FriendRequest $request)
	{
		$sitename = $this->getSiteName();
		$user = GWF_User::current();
		$username = $user->displayNameLabel();
		$friend = $request->getFriend();
		
		$mail = GWF_Mail::botMail();
		$mail->setSubject(tusr($user, 'mail_subj_frq_denied', [$sitename, $username]));
		$args = [$friend->displayNameLabel(), $username, $sitename];
		$mail->setBody(tusr($friend, 'mail_body_frq_denied', $args));
	}
}
