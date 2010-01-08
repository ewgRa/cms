<?php
	/* $Id */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoNavigationDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Navigation';
		
		/**
		 * @return Navigation
		 */		
		public function insert(Navigation $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParams = array();
			
			if (!is_null($object->getCategoryId())) {
				$dbQuery .= 'category_id = ?';
				$queryParams[] = $object->getCategoryId();
			}
			
			if (!is_null($object->getUri())) {
				$dbQuery .= 'uri = ?';
				$queryParams[] = $object->getUri()->getId();
			}
			
			$this->db()->query($dbQuery, $queryParams);
			 
			$object->setId($this->db()->getInsertedId());
			
			return $object;
		}

		/**
		 * @return Navigation
		 */
		protected function build(array $array)
		{
			return
				Navigation::create()->
					setId($array['id'])->
					setCategoryId($array['category_id'])->
					setUri(HttpUrl::createFromString($array['uri']));
		}
	}
?>