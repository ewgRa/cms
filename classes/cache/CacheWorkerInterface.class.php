<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	interface CacheWorkerInterface
	{
		/**
		 * @return CacheTicket
		 */
		public function createTicket(CmsDatabaseRequester $requester);

		public function getCachedByQuery(
			DatabaseQueryInterface $dbQuery,
			CmsDatabaseRequester $requester
		);
		
		public function getListCachedByQuery(
			DatabaseQueryInterface $dbQuery,
			CmsDatabaseRequester $requester
		);
		
		/**
		 * @return CmsDatabaseRequester
		 */
		public function addTicketToTag(
			CacheTicket $cacheTicket,
			CmsDatabaseRequester $requester
		);
		
		/**
		 * @return CmsDatabaseRequester
		 */
		public function dropCache(CmsDatabaseRequester $requester);
	}	
?>