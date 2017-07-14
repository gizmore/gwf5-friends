<?php
final class Friends_Accept extends GWF_MethodFriendRequest
{
	public function executeWithRequest(GWF_FriendRequest $request)
	{
		$request->delete();
		$forRequester = GWF_Friendship::blank(array(
			'friend_user' => $request->getUserID(),
			'friend_friend' => $request->getFriendID(),
			'friend_relation' => $request->getRelation(),
		))->insert();
		$forHisFriend = GWF_Friendship::blank(array(
			'friend_user' => $request->getFriendID(),
			'friend_friend' => $request->getUserID(),
			'friend_relation' => GDO_FriendRelation::reverseRelation($request->getRelation()),
		))->insert();
		return $this->message('msg_friends_accepted');
	}
}
