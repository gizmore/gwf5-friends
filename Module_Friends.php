<?php
/**
 * Friendship and user relation module
 * 
 * @author gizmore
 * @since 5.0
 * @version 5.0
 */
final class Module_Friends extends GWF_Module
{
	##############
	### Module ###
	##############
	public $module_priority = 40;
	public function onLoadLanguage() { return $this->loadLanguage('lang/friends'); }
	public function getClasses() { return ['GDO_FriendRelation', 'GDO_ACL', 'GWF_Friendship', 'GWF_FriendRequest', 'GWF_MethodFriendRequest']; }

	##############
	### Config ###
	##############
	public function getUserSettings()
	{
		return array(
			GDO_Checkbox::make('friendship_guests')->initial('0'),
			GDO_Int::make('friendship_level')->unsigned()->initial('0'),
		);
	}
	
	public function getConfig()
	{
		return array(
			GDO_Checkbox::make('friendship_guests')->initial('0'),
			GDO_Duration::make('friendship_cleanup_age')->initial(GWF_Time::ONE_DAY),
		);
	}
	public function cfgGuestFriendships() { return $this->getConfigValue('friendship_guests'); }
	public function cfgCleanupAge() { return $this->getConfigValue('friendship_cleanup_age'); }
	
	##############
	### Render ###
	##############
	public function renderTabs()
	{
		return $this->templatePHP('tabs.php');
	}

	public function onRenderFor(GWF_Navbar $navbar)
	{
		$this->templatePHP('sidebars.php', ['navbar' => $navbar]);
	}
}
