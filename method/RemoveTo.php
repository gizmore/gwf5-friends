<?php
final class Friends_RemoveTo extends GWF_Method
{
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$user = GWF_User::current();
		$request = GWF_FriendRequest::findById($user->getID(), Common::getRequestString('friend'));
		$request->saveVar('frq_denied', GWF_Time::getDate());
		
		$tabs = Module_Friends::instance()->renderTabs();
		$redirect = GWF_Website::redirectMessage(href('Friends', 'Requesting'));
		return $tabs->add($this->message('msg_request_revoked'))->add($redirect);
	}
}
