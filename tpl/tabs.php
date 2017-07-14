<?php
$user = GWF_User::current();
$bar = GDO_Bar::make();
$friends = GWF_Friendship::count($user);
$incoming = GWF_FriendRequest::countIncomingFor($user);
$bar->addFields(array(
	GDO_Link::make('link_add_friend')->icon('add')->href(href('Friends', 'Request')),
	GDO_Link::make('link_friends')->label('link_friends', [$friends])->icon('group')->href(href('Friends', 'List')),
	GDO_Link::make('link_incoming_friend_requests')->label('link_incoming_friend_requests', [$incoming])->icon('notifications_active')->href(href('Friends', 'Requests')),
	GDO_Link::make('link_pending_friend_requests')->icon('notifications_paused')->href(href('Friends', 'Requesting')),
));
echo $bar->renderCell();
