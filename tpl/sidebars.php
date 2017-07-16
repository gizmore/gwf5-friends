<?php
$navbar instanceof GWF_Navbar;
if ($navbar->isRight())
{
	$user = GWF_User::current();
	if ($user->isAuthenticated())
	{
		$count = GWF_Friendship::count($user);
		$link = GDO_Link::make('link_friends')->label('link_friends', [$count])->href(href('Friends', 'List'));
		if (GWF_FriendRequest::countIncomingFor($user))
		{
			$link->icon('notifications');
		}
		$navbar->addField($link);
	}
}
