<?php
	/* $Id$ */

	class ContentCacheWorker extends ModuleCacheWorker
	{
		/**
		 * @return ContentCacheWorker
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
			$localizer =
				$this->getModule()->
					getRequest()->
					getAttached(AttachedAliases::LOCALIZER);

			$page =
				$this->getModule()->
					getRequest()->
					getAttached(AttachedAliases::PAGE);
				
			return array(
				$this->getModule()->getUnits(),
				$localizer->getRequestLanguage(),
				$localizer->getSource(),
				$page->getBaseUrl()->getPath(),
				defined('MEDIA_HOST') ? MEDIA_HOST : null
			);
		}
	}
?>