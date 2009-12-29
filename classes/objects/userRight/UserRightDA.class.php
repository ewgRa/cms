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
			
			$dbResult = $this->db()->query($dbQuery, array($user->getId()));
			
			return $this->buildList($dbResult->fetchList());
		}
		
		private function buildList(array $arrayList) {
			$result = array();
			
			foreach ($arrayList as $array) {
				$object = $this->build($array);
				$result[$object->getId()] = $object;
			}
			
			return $result;
		}
		
		private function build(array $array) {
			return
				UserRight::create()->
					setUserId($array['user_id'])->
					setRightId($array['right_id']);
		}
	}
?>