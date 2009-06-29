<?php
	/* $Id$ */

	class PageViewFilesCacheWorker extends ModuleCacheWorker
	{
		/**
		 * @return PageViewFilesCacheWorker
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
				$this->getModule()->getRequest()->
					getAttached(AttachedAliases::PAGE)->getId()
			);
		}
		
	}
?>