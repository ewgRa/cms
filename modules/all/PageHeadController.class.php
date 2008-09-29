<?php
	/* $Id$ */

	class PageHeadController extends Controller
	{
		public function importSettings($settings)
		{
			if(Cache::me()->hasTicketParams('page'))
			{
				$this->setCacheTicket(
					Cache::me()->createTicket('page')->
						setKey(Page::me()->getId(), __CLASS__, __FUNCTION__)
				);
			}
			
			return $this;
		}
		
		public function getModel()
		{
			$dbQuery = "
				SELECT title, description, keywords
				FROM " . Database::me()->getTable('PagesData') . "
				WHERE
					page_id = ?
			";
			
			$dbResult = Database::me()->query(
				$dbQuery,
				array(Page::me()->getId())
			);

			return Database::me()->fetchArray($dbResult);
		}
	}
?>