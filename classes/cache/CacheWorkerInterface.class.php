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
		public function createTicket(DatabaseRequester $requester);

		public function getCachedByQuery(
			DatabaseQueryInterface $dbQuery,
			DatabaseRequester $requester
		);
		
		public function getListCachedByQuery(
			DatabaseQueryInterface $dbQuery,
			DatabaseRequester $requester
		);
		
		/**
		 * @return DatabaseRequester
		 */
		public function addTicketToTag(
			CacheTicket $cacheTicket,
			DatabaseRequester $requester
		);
		
		/**
		 * @return DatabaseRequester
		 */
		public function dropCache(DatabaseRequester $requester);
	}	
?>