<?php
final class GWF_Friendship extends GDO
{
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDO_User::make('friend_user')->primary(),
			GDO_User::make('friend_friend')->primary(),
			GDO_FriendRelation::make('friend_relation')->notNull(),
			GDO_CreatedAt::make('friend_created'),
		);
	}
	
	/**
	 * @return GWF_User
	 */
	public function getUser() { return $this->getValue('friend_user'); }
	public function getUserID() { return $this->getVar('friend_user'); }
	
	/**
	 * @return GWF_User
	 */
	public function getFriend() { return $this->getValue('friend_friend'); }
	public function getFriendID() { return $this->getVar('friend_friend'); }

	public function getCreated() { return $this->getVar('friend_created'); }
	public function getRelation() { return $this->getVar('friend_relation'); }

	public function displayRelation() { return GDO_FriendRelation::displayRelation($this->getRelation()); }
	
	public function renderList() { return GWF_Template::modulePHP('Friends', 'listitem/friendship.php', ['gdo' => $this]); }
	public function renderCard() { return GWF_Template::modulePHP('Friends', 'card/friendship.php', ['gdo' => $this]); }
	
	##############
	### Static ###
	##############
	public static function getRelationBetween(GWF_User $user, GWF_User $friend)
	{
		return self::table()->select('friend_relation')->
			where("friend_user={$user->getID()} AND friend_friend={$friend->getID()}")->exec()->fetchValue();
	}
	
	public static function areRelated(GWF_User $user, GWF_User $friend)
	{
		return self::getRelationBetween($user, $friend) !== null;
	}
	
	public static function count(GWF_User $user)
	{
		return self::queryCount($user);
	}
	
	private static function queryCount(GWF_User $user)
	{
		return self::table()->countWhere('friend_user='.$user->getID());
	}
}
