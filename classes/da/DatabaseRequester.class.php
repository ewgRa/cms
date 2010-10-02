<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class DatabaseRequester extends \ewgraFramework\Singleton 
		implements CacherInterface
	{
		protected $poolAlias	= 'cms';
		protected $tableAlias	= null;
		
		private $linkedCachers = array();
		
		abstract protected function build(array $array);
		
		public function getTable()
		{
			\ewgraFramework\Assert::isNotNull($this->tableAlias);
			
			return $this->quoteTable($this->tableAlias);
		}
		
		public function quoteTable($table)
		{
			return
				$this->db()->getDialect()->
				quoteTable($table, $this->db());
		}
		
		/**
		 * @return DatabaseRequester
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
			\ewgraFramework\Assert::isFalse($cacher->hasLinkedCacher($this), 'recursion detected');
			
			$this->linkedCachers[get_class($cacher)] = $cacher;
		}
		
		public function hasLinkedCacher(CacherInterface $cacher)
		{
			return isset($this->linkedCachers[get_class($cacher)]);
		}
		
		/**
		 * @return \ewgraFramework\BaseDatabase
		 */
		public function getPool()
		{
			return \ewgraFramework\Database::me()->getPool($this->getPoolAlias());
		}

		/**
		 * @return \ewgraFramework\BaseDatabase
		 */
		public function db()
		{
			return $this->getPool();
		}
		
		public function getByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			$result = null;
			
			$dbResult = $this->db()->query($dbQuery);

			if ($dbResult->recordCount())
				$result = $this->build($dbResult->fetchRow());
			
			return $result;
		}
		
		public function getCachedByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			return $this->getCacheWorker()->getCachedByQuery($dbQuery, $this);
		}
		
		public function getListByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			$dbResult = $this->db()->query($dbQuery);

			return $this->buildList($dbResult->fetchList());
		}
		
		public function getListCachedByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
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
			$worker = CacheWorkerManager::me()->getFor($this);
			
			if (!$worker)
				$worker = CacheWorkerManager::me()->getDefault();

			return $worker; 
		}
		
		/**
		 * @return \ewgraFramework\CacheTicket
		 */
		public function createCacheTicket()
		{
			return $this->getCacheWorker()->createTicket($this);
		}

		
		public function addCacheTicketToTag(\ewgraFramework\CacheTicket $ticket)
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