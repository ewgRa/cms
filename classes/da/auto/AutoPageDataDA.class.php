<?php
	namespace ewgraCms;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoPageDataDA extends DatabaseRequester
	{
		protected $tableAlias = 'page_data';

		/**
		 * @return PageData
		 */
		public function insert(PageData $object)
		{
			$dialect = $this->db()->getDialect();

			$dbQuery = 'INSERT INTO '.$this->getTable().' ';
			$fields = array();
			$fieldValues = array();
			$values = array();
			$fields[] = $dialect->escapeField('page_id');
			$fieldValues[] = '?';
			$values[] = $object->getPageId();
			$fields[] = $dialect->escapeField('language_id');
			$fieldValues[] = '?';
			$values[] = $object->getLanguageId();
			$fields[] = $dialect->escapeField('title');
			$fieldValues[] = '?';

			if ($object->getTitle() === null)
				$values[] = null;
			else {
				$values[] = $object->getTitle();
			}

			$fields[] = $dialect->escapeField('description');
			$fieldValues[] = '?';

			if ($object->getDescription() === null)
				$values[] = null;
			else {
				$values[] = $object->getDescription();
			}

			$fields[] = $dialect->escapeField('keywords');
			$fieldValues[] = '?';

			if ($object->getKeywords() === null)
				$values[] = null;
			else {
				$values[] = $object->getKeywords();
			}

			$dbQuery .= '('.join(', ', $fields).') VALUES ';
			$dbQuery .= '('.join(', ', $fieldValues).')';

			$dbResult =
				$this->db()->insertQuery(
					\ewgraFramework\DatabaseInsertQuery::create()->
					setPrimaryField('id')->
					setQuery($dbQuery)->
					setValues($values)
				);

			$object->setId($dbResult->getInsertedId());

			$this->dropCache();

			return $object;
		}

		/**
		 * @return AutoPageDataDA
		 */
		public function save(PageData $object)
		{
			$dialect = $this->db()->getDialect();
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';

			$queryParts = array();
			$whereParts = array();
			$queryParams = array();

			$queryParts[] = $dialect->escapeField('page_id').' = ?';
			$queryParams[] = $object->getPageId();
			$queryParts[] = $dialect->escapeField('language_id').' = ?';
			$queryParams[] = $object->getLanguageId();

			if ($object->getTitle() === null)
				$queryParts[] = $dialect->escapeField('title').' = NULL';
			else {
				$queryParts[] = $dialect->escapeField('title').' = ?';
				$queryParams[] = $object->getTitle();
			}


			if ($object->getDescription() === null)
				$queryParts[] = $dialect->escapeField('description').' = NULL';
			else {
				$queryParts[] = $dialect->escapeField('description').' = ?';
				$queryParams[] = $object->getDescription();
			}


			if ($object->getKeywords() === null)
				$queryParts[] = $dialect->escapeField('keywords').' = NULL';
			else {
				$queryParts[] = $dialect->escapeField('keywords').' = ?';
				$queryParams[] = $object->getKeywords();
			}


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
		 * @return AutoPageDataDA
		 */
		public function delete(PageData $object)
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
		 * @return PageData
		 */
		public function build(array $array)
		{
			return
				PageData::create()->
				setId($array['id'])->
				setPageId($array['page_id'])->
				setLanguageId($array['language_id'])->
				setTitle($array['title'])->
				setDescription($array['description'])->
				setKeywords($array['keywords']);
		}
	}
?>