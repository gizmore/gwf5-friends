<?php
$gdo instanceof GWF_FriendRequest;
$friendship = $gdo;
$friend = $friendship->getFriend();
$user = GWF_User::current();
if ($friendship->isFrom($user)) :
?>
<md-list-item class="md-2-line">
  <?= GWF_Avatar::renderAvatar($friend); ?>
  <div class="md-list-item-text" layout="column">
    <h3><?= $friend->displayName(); ?></h3>
    <p><?= t('friend_request_to', [$friendship->displayRelation(), tt($friendship->getCreated())]); ?></p>
  </div>
  <?= GDO_Button::make('btn_delete')->noLabel()->icon('delete')->href(href('Friends', 'RemoveTo', '&friend='.$friend->getID())); ?>
</md-list-item>
<?php else : ?>
<md-list-item class="md-2-line">
  <?= GWF_Avatar::renderAvatar($friend); ?>
  <div class="md-list-item-text" layout="column">
    <h3><?= $friendship->getUser()->displayName(); ?></h3>
    <p><?= t('friend_request_from', [$friendship->displayRelation(), tt($friendship->getCreated())]); ?></p>
  </div>
  <?= GDO_Button::make('btn_accept')->noLabel()->icon('person_add')->href(href('Friends', 'AcceptFrom', '&user='.$friendship->getUser()->getID())); ?>
  <?= GDO_Button::make('btn_deny')->noLabel()->icon('block')->href(href('Friends', 'RemoveFrom', '&user='.$friendship->getUser()->getID())); ?>
</md-list-item>
<?php endif; ?>
