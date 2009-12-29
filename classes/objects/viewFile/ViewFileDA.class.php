<?php
	/* $Id$ */

	final class ViewFileDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'ViewFile';
		
		/**
		 * @return ViewFileDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function getByPage(Page $page)
		{
			$dbQuery = "
				SELECT DISTINCT t1.* FROM ".$this->getTable()." t1
				INNER JOIN ".$this->db()->getTable('PageModule')." t2
					ON(t2.view_file_id = t1.id)
				WHERE t2.page_id = ?
				UNION
				SELECT DISTINCT t3.* FROM ".$this->db()->getTable('Page')." t1
				INNER JOIN ".$this->db()->getTable('Layout')." t2
					ON(t2.id = t1.layout_id)
				INNER JOIN ".$this->getTable()." t3
					ON(t3.id = t2.view_file_id)
				WHERE t1.id = ?
			";
			
			$dbResult = $this->db()->query($dbQuery, array($page->getId(), $page->getId()));
			
			return $this->buildList($dbResult->fetchList());
		}
		
		public function getInheritanceByIds(array $ids)
		{
			$dbQuery = "
				SELECT t1.* FROM ".$this->getTable()." t1
				INNER JOIN ".$this->db()->getTable('ViewFile_inheritance')." t2
					ON(t2.child_view_file_id = t1.id)
				WHERE t2.view_file_id IN (?)
				ORDER BY position
			";
			
			$dbResult = $this->db()->query($dbQuery, array($ids));
			
			return $this->buildList($dbResult->fetchList());
		}

		private function buildList(array $arrayList)
		{
			$result = array();
			
			foreach ($arrayList as $array)
				$result[$array['id']] = $this->build($array);
			
			return $result;
		}
		
		private function build(array $array)
		{
			return
				ViewFile::create()->
					setId($array['id'])->
					setContentType(
						ContentType::createByName($array['content_type'])
					)->
					// FIXME: really needed?
					setPath(Config::me()->replaceVariables($array['path']))->
					setJoinable($array['joinable']);
		}
	}
?>