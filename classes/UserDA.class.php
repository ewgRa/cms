<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * // FIXME: tested?
	*/
	final class UserDA extends CmsDatabaseRequester
	{
		/**
		 * @return UserDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
				
		public function checkLogin($login, $password)
		{
			$result = null;
			
			$dbQuery = '
				SELECT *, password = MD5( ? ) as verify_password
					FROM ' . $this->db()->getTable('User') . '
				WHERE login = ?
			';

			$dbResult = $this->db()->query($dbQuery, array($password, $login));

			if($dbResult->recordCount())
				$result = $dbResult->fetchArray();
			
			return $result;
		}
	}
?>