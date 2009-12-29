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
				
		public function getByLogin($login)
		{
			$dbQuery = 'SELECT * FROM '.$this->getTable().' WHERE login = ?';

			return $this->getCachedByQuery($dbQuery, array($login));
		}

		public function getById($id)
		{
			$dbQuery = 'SELECT * FROM '.$this->getTable().' WHERE id = ?';

			return $this->getCachedByQuery($dbQuery, array($id));
		}
		
		protected function build(array $array) {
			return
				User::create()->
					setId($array['id'])->
					setLogin($array['login'])->
					setPassword($array['password']);
		}
	}
?>