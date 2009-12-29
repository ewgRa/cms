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

		public function loadRights($userId)
		{
			$result = null;
			
			$dbQuery = '
				SELECT t1.* FROM ' . $this->db()->getTable('Right') . ' t1
				INNER JOIN ' . $this->db()->getTable('UserRight_ref') . ' t2
					ON ( t1.id = t2.right_id AND t2.user_id = ? )
			';

			$dbResult = $this->db()->query($dbQuery, array($userId));
			
			if($dbResult->recordCount())
				$result = $dbResult->fetchList();
			
			return $result;
		}
		
		public function loadInheritanceRights($inheritanceId)
		{
			$result = null;
			
			$dbQuery = '
				SELECT t1.* FROM ' . $this->db()->getTable('Right') . ' t1
				INNER JOIN ' . $this->db()->getTable('Right_inheritance') . ' t2
					ON ( t1.id = t2.child_right_id AND t2.right_id IN( ? ) )
			';

			$dbResult = $this->db()->query(
				$dbQuery,
				array($inheritanceId)
			);
			
			if($dbResult->recordCount())
				$result = $dbResult->fetchList();
			
			return $result;
		}
	}
?>