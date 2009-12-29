<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UserRightDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'UserRight';
		
		/**
		 * @return RightDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function getByUser(User $user)
		{
			$dbQuery = "SELECT * FROM ".$this->getTable()." WHERE user_id = ?";
			
			return $this->getListCachedByQuery($dbQuery, array($user->getId()));
		}
		
		protected function build(array $array) {
			return
				UserRight::create()->
					setUserId($array['user_id'])->
					setRightId($array['right_id']);
		}
	}
?>