<?php
final class GDO_FriendRelation extends GDO_Enum
{
	public static $TYPES = array(
		'friend' => 'friend',
		'bestfriend' => 'bestfriend',
		'coworker' => 'coworker',
		'husband' => 'wife',
	);
	
	public static function displayRelation(string $relation)
	{
		return t('enum_'.$relation);
	}
	
	public static function reverseRelation($relation)
	{
		if (isset(self::$TYPES[$relation]))
		{
			return self::$TYPES[$relation];
		}
		elseif (false !== ($index = array_search($relation, self::$TYPES, true)))
		{
			return $index;
		}
		else
		{
			throw new GWF_Exception('err_reverse_friends_relation');
		}
	}
		
	
	public function defaultLabel() { return $this->label('friend_relation'); }
	
	public function __construct()
	{
		$this->enumValues(...array_unique(array_merge(array_keys(self::$TYPES), array_values(self::$TYPES))));
	}
}
