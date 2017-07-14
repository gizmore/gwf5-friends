<?php
final class Friends_RemoveFrom extends GWF_Method
{
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$user = GWF_User::current();
		$fromId = Common::getRequestString('user');
		if (!($request = GWF_FriendRequest::table()->getById($fromId, $user->getID())))
		{
			return $this->error('err_friend_request');
		}
		
		method('Friends', 'Deny')->executeWithRequest($request);
		
		$tabs = Module_Friends::instance()->renderTabs();
		$response = $this->message('msg_request_denied');
		$redirect = GWF_Website::redirect(href('Friends', 'Requests'));
		
		return $tabs->add($response)->add($redirect);
	}
}
