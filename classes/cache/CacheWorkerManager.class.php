<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class CacheWorkerManager extends Singleton
	{
		private $default = null;
		
		private $requesterMap = null;
		
		/**
		 * @return CacheWorkerManager
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
		
		public function setDefault(CacheWorkerInterface $cacheWorker)
		{
			$this->default = $cacheWorker;
			return $this;
		}
		
		public function getDefault()
		{
			Assert::isNotNull(
				$this->default, 
				'you must define default cache worker'
			);
			
			return $this->default;	
		}
		
		public function addRequesterMap(
			CmsDatabaseRequester $requester, 
			CacheWorkerInterface $cacheWorker
		) {
			$this->requester[get_class($requester)] = $cacheWorker;
			return $this;
		}
		
		public function getFor(CmsDatabaseRequester $requester)
		{
			if (isset($this->requesterMap[get_class($requester)]))
				return $this->requesterMap[get_class($requester)];

			return null;
		}
	}	
?>