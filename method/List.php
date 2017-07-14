<?php
final class Friends_List extends GWF_MethodQueryList
{
	/**
	 * @return GDO
	 */
	public function gdoTable() { return GWF_Friendship::table(); }
	
	public function isGuestAllowed() { return Module_Friends::instance()->cfgGuestFriendships(); }
	
	public function gdoDecorateList(GDO_List $list)
	{
		$list->label('list_friends', [$list->countItems()]);
// 		$list->itemTemplate(GDO_FriendshipItem::make());
	}
	
	public function gdoQuery()
	{
		$user = GWF_User::current();
		return $this->gdoTable()->select()->where("friend_user={$user->getID()}");
	}
	
	public function execute()
	{
		$response = parent::execute();
		return Module_Friends::instance()->renderTabs()->add($response);
	}
	
}
