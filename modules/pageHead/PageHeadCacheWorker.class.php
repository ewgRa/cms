<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageHeadCacheWorker extends ModuleCacheWorker
	{
		/**
		 * @return PageHeadCacheWorker
		 */
		public static function create()
		{
			return new self;
		}
		
		protected function getKey()
		{
			return array(
				$this->getPage()->getId(),
				$this->getRequestLanguage()->getId(),
			);
		}
	}
?>