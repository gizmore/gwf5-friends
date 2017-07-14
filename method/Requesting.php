<?php
final class Friends_Requesting extends GWF_MethodQueryList
{
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	/**
	 * @return GDO
	 */
	public function gdoTable() { return GWF_FriendRequest::table(); }
	
	public function gdoDecorateList(GDO_List $list)
	{
		$list->label('list_pending_friend_requests', [$this->getSiteName(), $list->countItems()]);
// 		$list->itemTemplate(GDO_FriendshipItem::make());
	}
	
	public function execute()
	{
		$response = parent::execute();
		$tabs = Module_Friends::instance()->renderTabs();
		return $tabs->add($response);
	}
	
	public function gdoQuery()
	{
		$user = GWF_User::current();
		return $this->gdoTable()->select()->where("frq_user={$user->getID()} AND frq_denied IS NULL");
	}
	
}
