<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DefaultCacheWorker extends \ewgraFramework\Singleton
		implements CacheWorkerInterface
	{
		/**
		 * @return DefaultCacheWorker
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		public function restoreTicketData(
			\ewgraFramework\CacheTicket $cacheTicket,
			CacheableRequesterInterface $requester,
			array $relatedRequesters = array()
		)
		{
			$result = $cacheTicket->restoreData();

			$this->catchTicketVersion($requester, $cacheTicket, $result);

			if (!$cacheTicket->isExpired()) {
				foreach ($relatedRequesters as $relatedRequester) {
					$this->catchTicketVersion($relatedRequester, $cacheTicket, $result);

					if ($cacheTicket->isExpired())
						break;
				}
			}

			return
				$cacheTicket->isExpired()
					? null
					: $result['data'];
		}

		public function storeTicketData(
			\ewgraFramework\CacheTicket $cacheTicket,
			$data,
			CacheableRequesterInterface $requester,
			array $relatedRequesters = array()
		)
		{
			$tagVersions = array();

			$tagVersions[get_class($requester)] =
				$this->addTicketToTag($cacheTicket, $requester);

			foreach ($relatedRequesters as $relatedRequester) {
				$tagVersions[get_class($relatedRequester)] =
					$this->addTicketToTag($cacheTicket, $relatedRequester);
			}

			$data = array(
				'tagVersions' => $tagVersions,
				'data' => $data
			);

			return $cacheTicket->storeData($data);
		}

		public function getCustomCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			$cacheTicket = $this->createTicket($requester);

			$result =
				$cacheTicket->
				setKey(__FUNCTION__, $dbQuery)->
				restoreData();

			$this->catchTicketVersion($requester, $cacheTicket, $result);

			if ($cacheTicket->isExpired()) {
				$result = array(
					'tagVersions' => array(
						get_class($requester) =>
							$this->addTicketToTag($cacheTicket, $requester)
					),
					'data' => $requester->getCustomByQuery($dbQuery)
				);

				$cacheTicket->storeData($result);
			}

			return $result['data'];
		}

		public function getCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			$cacheTicket = $this->createTicket($requester);

			$result =
				$cacheTicket->
				setKey(__FUNCTION__, $dbQuery)->
				restoreData();

			$this->catchTicketVersion($requester, $cacheTicket, $result);

			if ($cacheTicket->isExpired()) {
				$result = array(
					'tagVersions' => array(
						get_class($requester) =>
							$this->addTicketToTag($cacheTicket, $requester)
					),
					'data' => $requester->getByQuery($dbQuery)
				);

				$cacheTicket->storeData($result);
			}

			return $result['data'];
		}

		public function getCustomListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			$cacheTicket = $this->createTicket($requester);

			$result =
				$cacheTicket->
					setKey(__FUNCTION__, get_class($requester), $dbQuery)->
					restoreData();

			$this->catchTicketVersion($requester, $cacheTicket, $result);

			if ($cacheTicket->isExpired()) {
				$result = array(
					'tagVersions' => array(
						get_class($requester) =>
							$this->addTicketToTag($cacheTicket, $requester)
					),
					'data' => $requester->getCustomListByQuery($dbQuery)
				);

				$cacheTicket->storeData($result);
			}

			return $result['data'];
		}

		public function getListCachedByQuery(
			\ewgraFramework\DatabaseQueryInterface $dbQuery,
			CacheableRequesterInterface $requester
		)
		{
			$cacheTicket = $this->createTicket($requester);

			$result =
				$cacheTicket->
					setKey(__FUNCTION__, get_class($requester), $dbQuery)->
					restoreData();

			$this->catchTicketVersion($requester, $cacheTicket, $result);

			if ($cacheTicket->isExpired()) {
				$result = array(
					'tagVersions' => array(
						get_class($requester) =>
							$this->addTicketToTag($cacheTicket, $requester)
					),
					'data' => $requester->getListByQuery($dbQuery)
				);

				$cacheTicket->storeData($result);
			}

			return $result['data'];
		}

		/**
		 * @return DefaultCacheWorker
		 */
		public function dropCache(CacheableRequesterInterface $requester)
		{
			$tagTicket = $this->createTicket($requester);

			$data = $tagTicket->setKey('tag')->restoreData();

			if (!$tagTicket->isExpired())
				$tagTicket->drop();

			return $this;
		}

		/**
		 * @return CacheWorkerTicket
		 */
		public function createWorkerTicket(
			CacheableRequesterInterface $requester,
			array $relatedRequesters = array()
		)
		{
			return CacheWorkerTicket::create(
				$this,
				$requester,
				$this->createTicket($requester, $relatedRequesters),
				$relatedRequesters
			);
		}


		/**
		 * @return CacheTicket
		 */
		private function createTicket(
			CacheableRequesterInterface $requester,
			array $relatedRequesters = array()
		)
		{
			\ewgraFramework\Assert::isTrue(
				\ewgraFramework\Cache::me()->hasPool($requester->getPoolAlias()),
				'define pool for '.$requester->getPoolAlias()
			);

			$pool = \ewgraFramework\Cache::me()->getPool($requester->getPoolAlias());

			$prefix = array(get_class($requester));

			foreach ($relatedRequesters as $relatedRequester)
				$prefix[] = get_class($relatedRequester);

			return $pool->createTicket()->setPrefix(join('-', $prefix));
		}

		/**
		 * @return DefaultCacheWorker
		 */
		private function addTicketToTag(
			\ewgraFramework\CacheTicket $cacheTicket,
			CacheableRequesterInterface $requester
		)
		{
			$tagTicket = $this->createTicket($requester);
			$tagTicket->setKey('tag');

			$data = $tagTicket->restoreData();

			if ($tagTicket->isExpired())
				$data = array('version' => microtime(true));

			$tagTicket->storeData($data);

			return $data['version'];
		}

		/**
		 * @return DefaultCacheWorker
		 */
		private function getTagVersion(CacheableRequesterInterface $requester)
		{
			$tagTicket = $this->createTicket($requester);
			$tagTicket->setKey('tag');

			$data = $tagTicket->restoreData();

			return
				$tagTicket->isExpired()
					? null
					: $data['version'];
		}

		private function catchTicketVersion(
			CacheableRequesterInterface $requester,
			\ewgraFramework\CacheTicket $cacheTicket,
			$result
		)
		{
			if ($cacheTicket->isExpired())
				return $this;

			$tagVersion = $this->getTagVersion($requester);

			if (
				!isset($result['tagVersions'][get_class($requester)])
				|| $tagVersion != $result['tagVersions'][get_class($requester)]
			)
				$cacheTicket->drop();

			return $this;
		}
	}
?>