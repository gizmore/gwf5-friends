<?php
$gdo instanceof GWF_Friendship;
$friendship = $gdo;
$friend = $friendship->getFriend();
?>
<md-list-item class="md-2-line">
  <?= GWF_Avatar::renderAvatar($friend); ?>
  <div class="md-list-item-text" layout="column">
    <h3><?= $friend->displayName(); ?></h3>
    <p><?= t('friend_relation_since', [$friendship->displayRelation(), tt($friendship->getCreated())]); ?></p>
  </div>
  <?= GDO_IconButton::make()->icon('delete')->href(href('Friends', 'Remove', '&friend='.$friend->getID())); ?>
</md-list-item>
