<?php
	namespace ewgraCms;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoViewFileDA extends DatabaseRequester
	{
		protected $tableAlias = 'ViewFile';

		/**
		 * @return ViewFile
		 */
		public function insert(ViewFile $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();

			if (!is_null($object->getContentType())) {
				$queryParts[] = '`content_type` = ?';
				$queryParams[] = $object->getContentType()->getId();
			}

			if (!is_null($object->getPath())) {
				$queryParts[] = '`path` = ?';
				$queryParams[] = $object->getPath();
			}

			if (!is_null($object->getJoinable())) {
				$queryParts[] = '`joinable` = ?';
				$queryParams[] = $object->getJoinable();
			}

			if (!is_null($object->getSourceId())) {
				$queryParts[] = '`source_id` = ?';
				$queryParams[] = $object->getSourceId();
			}

			$dbQuery .= join(', ', $queryParts);

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);

			$object->setId($this->db()->getInsertedId());

			$this->dropCache();

			return $object;
		}

		/**
		 * @return AutoViewFileDA
		 */
		public function save(ViewFile $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';

			$queryParts = array();
			$whereParts = array();
			$queryParams = array();

			$queryParts[] = '`content_type` = ?';
			$queryParams[] = $object->getContentType()->getId();
			$queryParts[] = '`path` = ?';
			$queryParams[] = $object->getPath();

			if ($object->getJoinable() === null)
				$queryParts[] = '`joinable` = NULL';
			else {
				$queryParts[] = '`joinable` = ?';
				$queryParams[] = $object->getJoinable();
			}

			$queryParts[] = '`source_id` = ?';
			$queryParams[] = $object->getSourceId();

			$whereParts[] = 'id = ?';
			$queryParams[] = $object->getId();
			\ewgraFramework\Assert::isNotEmpty($whereParts);

			$dbQuery .= join(', ', $queryParts).' WHERE '.join(' AND ', $whereParts);

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);

			$this->dropCache();

			return $object;
		}

		/**
		 * @return AutoViewFileDA
		 */
		public function delete(ViewFile $object)
		{
			$dbQuery =
				'DELETE FROM '.$this->getTable().' WHERE id = '.$object->getId();

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->setQuery($dbQuery)
			);

			$object->setId(null);

			$this->dropCache();

			return $this;
		}

		public function getById($id)
		{
			return $this->getCachedByQuery(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery('SELECT * FROM '.$this->getTable().' WHERE id = ?')->
				setValues(array($id))
			);
		}

		public function getByIds(array $ids)
		{
			return $this->getListCachedByQuery(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery('SELECT * FROM '.$this->getTable().' WHERE id IN(?)')->
				setValues(array($ids))
			);
		}

		/**
		 * @return ViewFile
		 */
		public function build(array $array)
		{
			return
				ViewFile::create()->
				setId($array['id'])->
				setContentType(\ewgraFramework\ContentType::create($array['content_type']))->
				setPath($array['path'])->
				setJoinable($array['joinable'] == null ? null : $array['joinable'] == true)->
				setSourceId($array['source_id']);
		}

		public function dropCache()
		{
			Layout::da()->dropCache();
			PageController::da()->dropCache();
			return parent::dropCache();
		}
	}
?>