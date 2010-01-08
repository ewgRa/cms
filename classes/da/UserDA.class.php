<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UserDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'User';
		
		/**
		 * @return UserDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
				
		/**
		 * @return User
		 */
		public function getByLogin($login)
		{
			$dbQuery = 'SELECT * FROM '.$this->getTable().' WHERE login = ?';

			return $this->getCachedByQuery($dbQuery, array($login));
		}

		/**
		 * @return User
		 */
		public function getByRight(Right $right)
		{
			$dbQuery = '
				SELECT t1.*
				FROM '.$this->getTable().' t1
				INNER JOIN '.UserRight::da()->getTable().' t2
					ON(t2.user_id = t1.id AND t2.right_id = ?)
				';

			return $this->getListCachedByQuery($dbQuery, array($right->getId()));
		}
		
		/**
		 * @return User
		 */
		public function getById($id)
		{
			$dbQuery = 'SELECT * FROM '.$this->getTable().' WHERE id = ?';

			return $this->getCachedByQuery($dbQuery, array($id));
		}
		
		/**
		 * @return User
		 */
		protected function build(array $array) {
			return
				User::create()->
					setId($array['id'])->
					setLogin($array['login'])->
					setPassword($array['password']);
		}
	}
?>