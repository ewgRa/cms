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
			$localizer =
				$this->getModule()->
					getRequest()->
					getAttachedVar(AttachedAliases::LOCALIZER);

			$page =
				$this->getModule()->
					getRequest()->
					getAttachedVar(AttachedAliases::PAGE);
				
			return array(
				$this->getModule()->getCategoryAlias(),
				$localizer->getRequestLanguage(),
				$localizer->getSource(),
				$page->getBaseUrl()->getPath()
			);
		}
	}
?>