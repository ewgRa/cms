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

			$dbResult = $this->db()->query($dbQuery, array($login));

			if(!$dbResult->recordCount())
				throw new NotFoundException();
			
			return $this->build($dbResult->fetchArray());
		}

		private function build(array $array) {
			return
				User::create()->
					setId($array['id'])->
					setLogin($array['login'])->
					setPassword($array['password']);
		}
	}
?>