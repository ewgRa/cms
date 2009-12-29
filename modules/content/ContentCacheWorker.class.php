<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ContentCacheWorker extends ModuleCacheWorker
	{
		/**
		 * @return ContentCacheWorker
		 */
		public static function create()
		{
			return new self;
		}
		
		protected function getKey()
		{
			return array(
				$this->getModule()->getUnits(),
				$this->getRequestLanguage()->getId(),
				$this->getLocalizer()->getSource(),
				$this->getBaseUrl()->getPath(),
				defined('MEDIA_HOST') ? MEDIA_HOST : null
			);
		}
	}
?>