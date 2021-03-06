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
		protected $tableAlias = 'view_file';

		public function getTag()
		{
			return '\ewgraCms\ViewFile';
		}

		/**
		 * @return array
		 */
		public function getTagList()
		{
			return array($this->getTag(), '\ewgraCms\FileSource');
		}

		/**
		 * @return ViewFile
		 */
		public function insert(ViewFile $object)
		{
			$result = $this->rawInsert($object);
			$this->dropCache();
			return $result;
		}

		/**
		 * @return ViewFile
		 */
		public function rawInsert(ViewFile $object)
		{
			$dialect = $this->db()->getDialect();

			$dbQuery = 'INSERT INTO '.$this->getTable().' ';
			$fields = array();
			$fieldValues = array();
			$values = array();

			if ($object->hasId()) {
				$fields[] = $dialect->escapeField('id');
				$fieldValues[] = '?';
				$values[] = $object->getId();
			}

			$fields[] = $dialect->escapeField('content_type');
			$fieldValues[] = '?';
			$values[] = $object->getContentType()->getId();
			$fields[] = $dialect->escapeField('path');
			$fieldValues[] = '?';
			$values[] = $object->getPath();
			$fields[] = $dialect->escapeField('joinable');
			$fieldValues[] = '?';

			if ($object->getJoinable() === null)
				$values[] = null;
			else {
				$values[] = ($object->getJoinable() ? 1 : 0);
			}

			$fields[] = $dialect->escapeField('source_id');
			$fieldValues[] = '?';
			$values[] = $object->getSourceId();
			$dbQuery .= '('.join(', ', $fields).') VALUES ';
			$dbQuery .= '('.join(', ', $fieldValues).')';

			$dbResult =
				$this->db()->insertQuery(
					\ewgraFramework\DatabaseInsertQuery::create()->
					setPrimaryField('id')->
					setQuery($dbQuery)->
					setValues($values)
				);

			if (!$object->hasId())
				$object->setId($dbResult->getInsertedId());

			return $object;
		}

		/**
		 * @return AutoViewFileDA
		 */
		public function save(ViewFile $object)
		{
			$result = $this->rawSave($object);
			$this->dropCache();
			return $result;
		}

		/**
		 * @return AutoViewFileDA
		 */
		public function rawSave(ViewFile $object)
		{
			$dialect = $this->db()->getDialect();
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';

			$queryParts = array();
			$whereParts = array();
			$queryParams = array();

			$queryParts[] = $dialect->escapeField('content_type').' = ?';
			$queryParams[] = $object->getContentType()->getId();
			$queryParts[] = $dialect->escapeField('path').' = ?';
			$queryParams[] = $object->getPath();

			if ($object->getJoinable() === null)
				$queryParts[] = $dialect->escapeField('joinable').' = NULL';
			else {
				$queryParts[] = $dialect->escapeField('joinable').' = ?';
				$queryParams[] = ($object->getJoinable() ? 1 : 0);
			}

			$queryParts[] = $dialect->escapeField('source_id').' = ?';
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

			return $object;
		}

		/**
		 * @return AutoViewFileDA
		 */
		public function delete(ViewFile $object)
		{
			$result = $this->rawDelete($object);
			$this->dropCache();
			return $result;
		}

		/**
		 * @return AutoViewFileDA
		 */
		public function rawDelete(ViewFile $object)
		{
			$dbQuery =
				'DELETE FROM '.$this->getTable().' WHERE id = '.$object->getId();

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->setQuery($dbQuery)
			);

			$object->setId(null);

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
			if (!$ids)
				return array();

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
				setJoinable($array['joinable'] === null ? null : $array['joinable'] == true)->
				setSourceId($array['source_id']);
		}
	}
?>