<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NavigationCacheWorker extends ModuleCacheWorker
	{
		/**
		 * @return NavigationCacheWorker
		 */
		public static function create()
		{
			return new self;
		}

		protected function getKey()
		{
			return array(
				$this->getModule()->getCategoryAlias(),
				$this->getRequestLanguage()->getid(),
				$this->getLocalizer()->getSource(),
				$this->getPage()->getBaseUrl()->getPath()
			);
		}
	}
?>