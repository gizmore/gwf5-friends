<?php
final class Friends_Requests extends GWF_MethodQueryList
{
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	/**
	 * @return GDO
	 */
	public function gdoTable() { return GWF_FriendRequest::table(); }
	
	public function gdoDecorateList(GDO_List $list)
	{
		$list->label('list_friends_requests', [$this->getSiteName(), $list->countItems()]);
	}
	
	public function gdoQuery()
	{
		$user = GWF_User::current();
		return $this->gdoTable()->select()->where("frq_friend={$user->getID()} AND frq_denied IS NULL");
	}
	
	public function execute()
	{
		$response = parent::execute();
		$tabs = Module_Friends::instance()->renderTabs();
		return $tabs->add($response);
	}
}
