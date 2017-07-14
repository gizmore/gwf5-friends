<?php
final class Friends_AcceptFrom extends GWF_Method
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
		
		method('Friends', 'Accept')->executeWithRequest($request);
		
		$tabs = Module_Friends::instance()->renderTabs();
		$response = $this->message('msg_friends_accepted');
		$redirect = GWF_Website::redirect(href('Friends', 'Requests'));
		
		return $tabs->add($response)->add($redirect);
	}
}
