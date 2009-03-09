<?php
	/* $Id$ */

	class ContentCacheWorker extends ControllerCacheWorker
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
				$this->getController()->
					getRequest()->
					getAttached(AttachedAliases::LOCALIZER);

			$page =
				$this->getController()->
					getRequest()->
					getAttached(AttachedAliases::PAGE);
				
			return array(
				$this->getController()
					? $this->getController()->getUnits()
					: null,
				$localizer->getRequestLanguage(),
				$localizer->getSource(),
				$page->getBaseUrl()->getPath(),
				defined('MEDIA_HOST') ? MEDIA_HOST : null
			);
		}
	}
?>