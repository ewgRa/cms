<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * FIXME: not used anymore? Can be dropped also with stuff in other parts?
	*/
	final class CacheWorkerTicket
	{
		/**
		 * @var CacheWorkerInterface
		 */
		private $cacheWorker = null;

		/**
		 * @var CacheTicket
		 */
		private $cacheTicket = null;

		/**
		 * @var CacheableRequesterInterface
		 */
		private $requester = array();

		private $relatedRequesters = array();

		/**
		 * @return CacheWorkerTicket
		 */
		public static function create(
			CacheWorkerInterface $cacheWorker,
			CacheableRequesterInterface $requester,
			\ewgraFramework\CacheTicket $cacheTicket,
			array $relatedRequesters = array()
		)
		{
			return new self($cacheWorker, $requester, $cacheTicket, $relatedRequesters);
		}

		public function __construct(
			CacheWorkerInterface $cacheWorker,
			CacheableRequesterInterface $requester,
			\ewgraFramework\CacheTicket $cacheTicket,
			array $relatedRequesters = array()
		) {
			$this->cacheWorker = $cacheWorker;
			$this->requester = $requester;
			$this->cacheTicket = $cacheTicket;
			$this->relatedRequesters = $relatedRequesters;
		}

		public function setKey()
		{
			$this->cacheTicket->setKey(func_get_args());
			return $this;
		}

		public function isExpired()
		{
			return $this->cacheTicket->isExpired();
		}

		/**
		 * @return CacheWorkerTicket
		 */
		public function storeData($data)
		{
			$this->cacheWorker->storeTicketData(
				$this->cacheTicket,
				$data,
				$this->requester,
				$this->relatedRequesters
			);

			return $this;
		}

		public function restoreData()
		{
			return $this->cacheWorker->restoreTicketData(
				$this->cacheTicket,
				$this->requester,
				$this->relatedRequesters
			);
		}
	}
?>