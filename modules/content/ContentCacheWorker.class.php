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
			return array(
				$this->getModule()->getUnits(),
				$this->getRequestLanguage(),
				$this->getLocalizer()->getSource(),
				$this->getPage()->getBaseUrl()->getPath(),
				defined('MEDIA_HOST') ? MEDIA_HOST : null
			);
		}
	}
?>