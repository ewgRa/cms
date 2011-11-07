<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class DatabaseRequester extends \ewgraFramework\Singleton
		implements CacherInterface, CacheableRequesterInterface
	{
		protected $poolAlias	= 'cms';
		protected $tableAlias	= null;

		private $linkedCachers = array();

		abstract protected function build(array $array);

		public function getTable()
		{
			\ewgraFramework\Assert::isNotNull($this->tableAlias);

			return $this->escapeTable($this->tableAlias);
		}

		public function escapeTable($table)
		{
			return
				$this->db()->getDialect()->
				escapeTable($table, $this->db());
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

		public function getCustomByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			$result = null;

			$dbResult = $this->db()->query($dbQuery);

			if ($dbResult->recordCount()) {
				\ewgraFramework\Assert::isEqual(
					$dbResult->recordCount(),
					1,
					'query returned more than one row'
				);

				$result = $dbResult->fetchRow();
			}

			return $result;
		}

		public function getByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			$result = $this->getCustomByQuery($dbQuery);

			if ($result)
				$result = $this->build($result);

			return $result;
		}

		public function getCustomCachedByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			return $this->getCacheWorker()->getCustomCachedByQuery($dbQuery, $this);
		}

		public function getCachedByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			return $this->getCacheWorker()->getCachedByQuery($dbQuery, $this);
		}

		public function getCustomListByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			$dbResult = $this->db()->query($dbQuery);

			return $dbResult->fetchList();
		}

		public function getListByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			return $this->buildList($this->getCustomListByQuery($dbQuery));
		}

		public function getListCachedByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			return $this->getCacheWorker()->getListCachedByQuery($dbQuery, $this);
		}

		public function getCustomListCachedByQuery(\ewgraFramework\DatabaseQueryInterface $dbQuery)
		{
			return $this->getCacheWorker()->getCustomListCachedByQuery($dbQuery, $this);
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