<?php
	/* $Id$ */

	class NavigationController extends Controller
	{
		private $category = null;
		
		private function setCategory($category)
		{
			$this->category = $category;
			return $this;
		}
		
		private function getCategory()
		{
			return $this->category;
		}
		
		public function importSettings($settings)
		{
			$this->setCategory($settings['category']);

			if(Cache::me()->hasTicketParams('navigation'))
			{
				$this->setCacheTicket(
					Cache::me()->createTicket('navigation')->
						setKey($this->getCategory())
				);
			}
			
			return $this;
		}
		
		public function getModel()
		{
			$dbQuery = "
				SELECT *
				FROM " . Database::me()->getTable('Navigations') . " t1
				INNER JOIN " . Database::me()->getTable('NavigationsData') . " t2
					ON(t1.id = t2.navigation_id AND t2.language_id = ?)
				WHERE
					t1.category_id = (
						SELECT id FROM " . Database::me()->getTable('Categories') . "
						WHERE alias = ?
					)
			";
			
			$dbResult = Database::me()->query(
				$dbQuery,
				array(
					Localizer::me()->getRequestLanguage()->getId(),
					$this->getCategory()
				)
			);

			return Database::me()->resourceToArray($dbResult);
		}
	}
?>