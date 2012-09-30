<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class DatabaseRequester extends \ewgraFramework\Singleton
		implements CacheableRequesterInterface
	{
		protected $poolAlias	= 'cms';
		protected $tableAlias	= null;

		private $cacheWorkerManager = null;
		private $database = null;

		abstract protected function build(array $array);

		/**
		 * @return DatabaseRequester
		 */
		protected function __construct()
		{
			$this->cacheWorkerManager = CacheWorkerManager::me();
			$this->database = \ewgraFramework\Database::me();

			parent::__construct();
		}

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

		/**
		 * @return \ewgraFramework\BaseDatabase
		 */
		public function getPool()
		{
			return $this->database->getPool($this->getPoolAlias());
		}

		/**
		 * @return \ewgraFramework\BaseDatabase
		 */
		public function db()
		{
			return $this->getPool();
		}

		public function getCustomByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery
		) {
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

		public function getByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery
		) {
			$result = $this->getCustomByQuery($dbQuery);

			if ($result)
				$result = $this->build($result);

			return $result;
		}

		public function getCustomCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			array $tags = null
		) {
			if (!$tags)
				$tags = $this->getTagList();

			return
				$this->getCacheWorker()->
				getCustomCachedByQuery($dbQuery, $this, $tags);
		}

		public function getCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			array $tags = null
		) {
			if (!$tags)
				$tags = $this->getTagList();

			return
				$this->getCacheWorker()->
				getCachedByQuery($dbQuery, $this, $tags);
		}

		public function getCustomListByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery
		) {
			$dbResult = $this->db()->query($dbQuery);

			return $dbResult->fetchList();
		}

		public function getListByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery
		) {
			return $this->buildList($this->getCustomListByQuery($dbQuery));
		}

		public function getListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			array $tags = null
		) {
			if (!$tags)
				$tags = $this->getTagList();

			return
				$this->getCacheWorker()->
				getListCachedByQuery($dbQuery, $this, $tags);
		}

		public function getCustomListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			array $tags = null
		) {
			if (!$tags)
				$tags = $this->getTagList();

			return
				$this->getCacheWorker()->
				getCustomListCachedByQuery($dbQuery, $this, $tags);
		}

		public function dropCache(array $tags = null)
		{
			if (!$tags)
				$tags = array($this->getTag());

			$this->getCacheWorker()->dropCache($this, $tags);

			return $this;
		}

		public function getCacheWorker()
		{
			$worker = $this->cacheWorkerManager->getFor($this);

			if (!$worker)
				$worker = $this->cacheWorkerManager->getDefault();

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