<?php
final class GWF_FriendRequest extends GDO
{
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDO_User::make('frq_user')->primary(),
			GDO_User::make('frq_friend')->primary(),
			GDO_FriendRelation::make('frq_relation')->initial('friend'),
			GDO_CreatedAt::make('frq_created'),
			GDO_DateTime::make('frq_denied'),
		);
	}
	
	public function gdoHashcode() { return self::gdoHashcodeS($this->getVars(['frq_user', 'frq_friend', 'frq_relation'])); }
	
	public function getRelation() { return $this->getVar('frq_relation'); }
	public function getReverseRelation() { return GDO_FriendRelation::reverseRelation($this->getRelation()); }
	public function getCreated() { return $this->getVar('frq_created'); }
	public function getDenied() { return $this->getVar('frq_denied'); }
	public function isDenied() { return $this->getDenied() !== null; }
	
	public function displayRelation() { return GDO_FriendRelation::displayRelation($this->getRelation()); }
	
	public function isFrom(GWF_User $user) { return $this->getUserID() === $user->getID(); }
	
	/**
	 * @return GWF_User
	 */
	public function getUser() { return $this->getValue('frq_user'); }
	public function getUserID() { return $this->getVar('frq_user'); }
	
	/**
	 * @return GWF_User
	 */
	public function getFriend() { return $this->getValue('frq_friend'); }
	public function getFriendID() { return $this->getVar('frq_friend'); }
	
	public function renderCard() { return GWF_Template::modulePHP('Friends', 'card/friendrequest.php', ['gdo' => $this]); }
	public function renderList() { return GWF_Template::modulePHP('Friends', 'listitem/friendrequest.php', ['gdo' => $this]); }
	
	##############
	### Static ###
	##############
	public static function getPendingFor(GWF_User $user, GWF_User $friend)
	{
		return self::getById($user->getID(), $friend->getID());
	}
	
	public static function countIncomingFor(GWF_User $user)
	{
		if (null === ($cached = $user->tempGet('gwf_friendrequest_count')))
		{
			$cached = self::table()->countWhere("frq_friend={$user->getID()} AND frq_denied IS NULL");
			$user->tempSet('gwf_friendrequest_count', $cached);
			$user->recache();
		}
		return $cached;
	}
	public function gdoAfterCreate()
	{
		$user = $this->getFriend();
		$user->tempUnset('gwf_friendrequest_count');
		$user->recache();
	}
	
}
