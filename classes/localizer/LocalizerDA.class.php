<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class LocalizerDA extends CmsDatabaseRequester
	{
		/**
		 * @return LocalizerDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
				
		public function loadLanguages()
		{
			$dbQuery = "SELECT * FROM " . $this->db()->getTable('Language');
			
			$dbResult = $this->db()->query($dbQuery);
			
			return $dbResult->fetchList();
		}
	}
?>