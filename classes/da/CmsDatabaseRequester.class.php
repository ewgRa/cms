<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class CmsDatabaseRequester extends Singleton
	{
		protected $poolAlias	= 'cms';
		protected $tableAlias	= null;
		
		abstract protected function build(array $array);
		
		public function getTable()
		{
			Assert::isNotNull($this->tableAlias);
			
			return $this->db()->getTable($this->tableAlias);
		}
		
		/**
		 * @return CmsDatabaseRequester
		 */
		public function setPoolAlias($alias)
		{
			$this->poolAlias = $alias;
			return $this;
		}
		
		public function getPoolAlias()
		{
			return $this->poolAlias;
		}
		
		/**
		 * @return BaseDatabase
		 */
		public function getPool()
		{
			return Database::me()->getPool($this->getPoolAlias());
		}

		/**
		 * @return BaseDatabase
		 */
		public function db()
		{
			return $this->getPool();
		}
		
		/**
		 * @return CacheTicket
		 */
		public function getCacheTicket()
		{
			try {
				$cacheTicket =
					Cache::me()->
						getPool($this->getPoolAlias())->
						createTicket(get_class($this));

			} catch(MissingArgumentException $e) {
				$cacheTicket = null;
			}
			
			return $cacheTicket;
		}
		
		protected function buildList(array $arrayList)
		{
			$result = array();
			
			foreach ($arrayList as $array) {
				$object = $this->build($array);
				
				if ($object instanceof IdentifierObject)
					$result[$object->getId()] = $object;
				else
					$result[] = $object;
			}
			
			return $result;
		}
		
		public function getCachedByQuery($dbQuery, array $params = array())
		{
			$result = null;
			
			$cacheTicket = $this->getCacheTicket();
			
			if ($cacheTicket) {
				$cacheTicket->
					setKey(get_class($this), $dbQuery, $params)->
					restoreData();
			}
				
			if (!$cacheTicket || $cacheTicket->isExpired()) {
				$dbResult = $this->db()->query($dbQuery, $params);
	
				if (!$dbResult->recordCount())
					throw NotFoundException::create();
				
				$result = $this->build($dbResult->fetchArray());

				if ($cacheTicket) {
					$cacheTicket->setData($result)->storeData();
					$this->addTicketToTag($cacheTicket);
				}
			} else
				$result = $cacheTicket->getData();
			
			return $result;
		}

		public function getListCachedByQuery($dbQuery, array $params = array())
		{
			$result = null;
			
			$cacheTicket = $this->getCacheTicket();
			
			if ($cacheTicket) {
				$cacheTicket->
					setKey(get_class($this), $dbQuery, $params)->
					restoreData();
			}
				
			if (!$cacheTicket || $cacheTicket->isExpired()) {
				$dbResult = $this->db()->query($dbQuery, $params);
	
				$result = $this->buildList($dbResult->fetchList());

				if ($cacheTicket) {
					$cacheTicket->setData($result)->storeData();
					$this->addTicketToTag($cacheTicket);
				}
			} else
				$result = $cacheTicket->getData();
			
			return $result;
		}
		
		/**
		 * @return CmsDatabaseRequester
		 */
		public function addTicketToTag(CacheTicket $cacheTicket)
		{
			$tagTicket =
				$this->getCacheTicket()->
				setKey(get_class($this), 'tag')->
				restoreData();

			$data =
				$tagTicket->isExpired()
					? array()
					: $tagTicket->getData();
				
			$data[] = $cacheTicket->getCacheInstance()->compileKey($cacheTicket);
			
			$tagTicket->setData($data)->storeData();
			
			return $this;
		}

		public function dropCache()
		{
			$tagTicket = $this->getCacheTicket();

			if ($tagTicket) {
				$tagTicket->
					setKey(get_class($this), 'tag')->
					restoreData();
	
				$data =
					$tagTicket->isExpired()
						? array()
						: $tagTicket->getData();

				foreach ($data as $cacheKey)
					$tagTicket->getCacheInstance()->dropByKey($cacheKey);
					
				$tagTicket->drop();
			}

			return $this;
		}
	}
?>