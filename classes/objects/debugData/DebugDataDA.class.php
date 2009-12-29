<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DebugDataDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'DebugData';
		
		/**
		 * @return DebugDataDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function insert(DebugData $data)
		{
			$dbQuery = "INSERT INTO " . $this->getTable()
				. " SET session = ?, data = ?";
			
			$this->db()->query(
				$dbQuery,
				array($data->getSession(), serialize($data->getData()))
			);
			
			return $this->db()->getInsertedId();
		}
		
		/**
		 * @return DebugData
		 */
		protected function build(array $array)
		{
			return
				DebugData::create()->
					setId($array['id'])->
					setSession($array['session'])->
					setData(unserialize($array['data']))->
					setDate($array['date']);
		}
	}
?>