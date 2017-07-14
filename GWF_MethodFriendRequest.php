<?php
abstract class GWF_MethodFriendRequest extends GWF_Method
{
	/**
	 * @param GWF_FriendRequest $request
	 * @return GWF_Response
	 */
	public abstract function executeWithRequest(GWF_FriendRequest $request);
	
	public function isAlwaysTransactional() { return true; }
	
	public function execute()
	{
		$forId = Common::getRequestInt('for', GWF_User::current()->getID());
		$fromId = Common::getRequestInt('from');
		
		$tokenRequired = GWF_User::current()->getID() !== $forId;
		
		$table = GWF_FriendRequest::table();
		$query = $table->select()->where("frq_user=$fromId AND frq_friend=$forId");
		if (!($request = $query->first()->exec()->fetchObject()))
		{
			return $this->error('err_friend_request');
		}
		
		if ( ($tokenRequired) && (Common::getRequestString('token') !== $request->gdoHashcode()) )
		{
			return $this->error('err_friend_request');
		}
		
		return $this->executeWithRequest($request);
	}
}
