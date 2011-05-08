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
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
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
					'tagVersion' => $this->addTicketToTag($cacheTicket, $requester),
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
					'tagVersion' => $this->addTicketToTag($cacheTicket, $requester),
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
					'tagVersion' => $this->addTicketToTag($cacheTicket, $requester),
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
					'tagVersion' => $this->addTicketToTag($cacheTicket, $requester),
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

			if ($tagTicket->isExpired())
				$data = array('keys' => array());

			foreach ($data['keys'] as $cacheKey => $value)
				$tagTicket->getCacheInstance()->dropByKey($cacheKey);

			$tagTicket->drop();

			return $this;
		}

		/**
		 * @return CacheTicket
		 */
		private function createTicket(CacheableRequesterInterface $requester)
		{
			\ewgraFramework\Assert::isTrue(
				\ewgraFramework\Cache::me()->hasPool($requester->getPoolAlias()),
				'define pool for '.$requester->getPoolAlias()
			);

			$pool = \ewgraFramework\Cache::me()->getPool($requester->getPoolAlias());

			return $pool->createTicket()->setPrefix(get_class($requester));
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
				$data = array('version' => microtime(true), 'keys' => array());

			$key = $cacheTicket->getCacheInstance()->compileKey($cacheTicket);

			$data['keys'][$key] = 1;

			// FIXME: life time for tag ticket must be equal max lifetime for it keys
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
				!isset($result['tagVersion'])
				|| $tagVersion != $result['tagVersion']
			)
				$cacheTicket->drop();

			return $this;
		}
	}
?>