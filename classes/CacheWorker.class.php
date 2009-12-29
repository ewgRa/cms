<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class CacheWorker
	{
		protected $poolAlias = 'cms';

		abstract protected function getKey();
		
		/**
		 * @return CacheWorker
		 */
		public function setPoolAlias($alias)
		{
			$this->poolAlias = $alias;
			return $this;
		}
		
		public function getPoolAlias()
		{
			return $this->poolAlias;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function createTicket()
		{
			$result = null;
			
			if ($this->cache()->hasTicketParams($this->getAlias())) {
				$result =
					$this->cache()->
						createTicket($this->getAlias())->
						setKey($this->getKey());
			}
			
			return $result;
		}
		
		/**
		 * @return BaseCache
		 */
		protected function cache()
		{
			return Cache::me()->getPool($this->getPoolAlias());
		}

		protected function getAlias()
		{
			return get_class($this);
		}
	}
?>