<?php
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
			
			return $this->quoteTable($this->tableAlias);
		}
		
		public function quoteTable($table)
		{
			return
				$this->db()->getDialect()->
				quoteTable($table, $this->db());
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
		public function createCacheTicket()
		{
			$pool =
				Cache::me()->hasPool($this->getPoolAlias())
					? Cache::me()->getPool($this->getPoolAlias())
					: null;
			
			return
				$pool
					? $pool->createTicket()->setPrefix(get_class($this))
					: null;
		}
		
		protected function buildList(array $arrayList)
		{
			$result = array();
			
			foreach ($arrayList as $array) {
				$object = $this->build($array);
				$result[$object->getId()] = $object;
			}
			
			return $result;
		}
		
		public function getCachedByQuery(DatabaseQueryInterface $dbQuery)
		{
			$result = null;

			$cacheTicket = $this->createCacheTicket();
			
			if ($cacheTicket) {
				$result =
					$cacheTicket->
					setKey($dbQuery)->
					restoreData();
			}
				
			if (!$cacheTicket || $cacheTicket->isExpired()) {
				$dbResult = $this->db()->query($dbQuery);
	
				if ($dbResult->recordCount())
					$result = $this->build($dbResult->fetchRow());

				if ($cacheTicket) {
					$cacheTicket->storeData($result);
					$this->addTicketToTag($cacheTicket);
				}
			}
			
			return $result;
		}

		public function getListCachedByQuery(DatabaseQueryInterface $dbQuery)
		{
			$result = null;
			
			$cacheTicket = $this->createCacheTicket();
			
			if ($cacheTicket) {
				$result =
					$cacheTicket->
						setKey(get_class($this), $dbQuery)->
						restoreData();
			}
				
			if (!$cacheTicket || $cacheTicket->isExpired()) {
				$dbResult = $this->db()->query($dbQuery);
	
				$result = $this->buildList($dbResult->fetchList());

				if ($cacheTicket) {
					$cacheTicket->storeData($result);
					$this->addTicketToTag($cacheTicket);
				}
			}
			
			return $result;
		}
		
		/**
		 * @return CmsDatabaseRequester
		 */
		public function addTicketToTag(CacheTicket $cacheTicket)
		{
			$tagTicket = $this->createCacheTicket()->setKey('tag');
				
			$data = $tagTicket->restoreData();

			if ($tagTicket->isExpired())
				$data = array();
				
			$data[] = $cacheTicket->getCacheInstance()->compileKey($cacheTicket);
			
			$tagTicket->storeData($data);
			
			return $this;
		}

		public function dropCache()
		{
			$tagTicket = $this->createCacheTicket();

			if ($tagTicket) {
				$data = $tagTicket->setKey('tag')->restoreData();
	
				if ($tagTicket->isExpired())
					$data = array();

				foreach ($data as $cacheKey)
					$tagTicket->getCacheInstance()->dropByKey($cacheKey);
					
				$tagTicket->drop();
			}

			return $this;
		}
	}
?>