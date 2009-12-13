<?php
	/* $Id$ */

	final class NavigationCacheWorker extends ModuleCacheWorker
	{
		/**
		 * @return NavigationCacheWorker
		 */
		public static function create()
		{
			return new self;
		}

		protected function getAlias()
		{
			return __CLASS__;
		}
		
		protected function getKey()
		{
			return array(
				$this->getModule()->getCategoryAlias(),
				$this->getRequestLanguage(),
				$this->getLocalizer()->getSource(),
				$this->getPage()->getBaseUrl()->getPath()
			);
		}
	}
?>