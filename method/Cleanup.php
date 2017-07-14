<?php
final class Friends_Cleanup extends GWF_MethodCronjob
{
	public function run()
	{
		$module = Module_Friends::instance();
		$cut = GWF_Time::getDate(time() - $module->cfgCleanupAge());
		GWF_FriendRequest::table()->deleteWhere("frq_denied < '$cut'");
		if ($affected = GDODB::instance()->affectedRows())
		{
			$this->logNotice(sprintf("Deleted %s old denied friend requests.", $affected));
		}
	}
}
