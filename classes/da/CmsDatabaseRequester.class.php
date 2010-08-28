<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class CmsDatabaseRequester extends Singleton implements CacherInterface
	{
		protected $poolAlias	= 'cms';
		protected $tableAlias	= null;
		
		private $linkedCachers = array();
		
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
		
		public function getLinkedCachers()
		{
			return $this->linkedCachers;			
		}
		
		public function addLinkedCacher(CacherInterface $cacher)
		{
			Assert::isFalse($cacher->hasLinkedCacher($this), 'recursion detected');
			
			$this->linkedCachers[get_class($cacher)] = $cacher;
		}
		
		public function hasLinkedCacher(CacherInterface $cacher)
		{
			return isset($this->linkedCachers[get_class($cacher)]);
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
		
		public function getByQuery(DatabaseQueryInterface $dbQuery)
		{
			$result = null;
			
			$dbResult = $this->db()->query($dbQuery);

			if ($dbResult->recordCount())
				$result = $this->build($dbResult->fetchRow());
			
			return $result;
		}
		
		public function getCachedByQuery(DatabaseQueryInterface $dbQuery)
		{
			return $this->getCacheWorker()->getCachedByQuery($dbQuery, $this);
		}
		
		public function getListByQuery(DatabaseQueryInterface $dbQuery)
		{
			$dbResult = $this->db()->query($dbQuery);

			return $this->buildList($dbResult->fetchList());
		}
		
		public function getListCachedByQuery(DatabaseQueryInterface $dbQuery)
		{
			return $this->getCacheWorker()->getListCachedByQuery($dbQuery, $this);
		}
		
		public function dropCache()
		{
			$this->getCacheWorker()->dropCache($this);
			
			foreach ($this->getLinkedCachers() as $cacher)
				$cacher->dropCache();
			
			return $this;
		}

		public function getCacheWorker()
		{
			return DefaultCacheWorker::me();
		}
		
		/**
		 * @return CacheTicket
		 */
		public function createCacheTicket()
		{
			return $this->getCacheWorker()->createTicket($this);
		}

		
		public function addCacheTicketToTag(CacheTicket $ticket)
		{
			$this->getCacheWorker()->addTicketToTag($ticket, $this);
			return $this;
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
	}
?>