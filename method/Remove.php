<?php
final class Friends_Remove extends GWF_Method
{
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$user = GWF_User::current();
		
		$friendship = GWF_Friendship::findById(Common::getRequestString('friend'), $user->getID());
		$friendship->delete();
		$friendship = GWF_Friendship::findById($user->getID(), Common::getRequestString('friend'));
		$friendship->delete();
		$this->sendMail($friendship);
		
		$tabs = Module_Friends::instance()->renderTabs();
		$response = $this->message('msg_friendship_deleted', [$friendship->getFriend()->displayNameLabel()]);
		$redirect = GWF_Website::redirect(href('Friends', 'List'));
		
		return $tabs->add($response)->add($redirect);
	}
	
	private function sendMail(GWF_Friendship $friendship)
	{
		$user = GWF_User::current();
		$friend = $friendship->getFriend();
		$sitename = $this->getSiteName();
		$mail = GWF_Mail::botMail();
		$mail->setSubject(tusr($friend, 'mail_subj_friend_removed', [$sitename, $user->displayNameLabel()]));
		$args = [$friend->displayNameLabel(), $user->displayNameLabel(), $sitename];
		$mail->setBody(tusr($friend, 'mail_body_friend_removed', $args));
		$mail->sendToUser($friend);
	}
}
