<?php
	/* $Id$ */

	class ContentCacheWorker extends CacheWorker
	{
		/**
		 * @return ContentCacheWorker
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return CacheTicket
		 */
		public function createTicket(HttpRequest $request, array $units)
		{
			$result = null;
			
			if($this->cache()->getPool()->hasTicketParams('content'))
			{
				$localizer = $request->getAttached(AttachedAliases::LOCALIZER);
				$page = $request->getAttached(AttachedAliases::PAGE);
				
				$result =
					$this->cache()->getPool()->createTicket('content')->
						setKey(
							$units,
							$localizer->getRequestLanguage(),
							$localizer->getSource(),
							$page->getBaseUrl()->getPath(),
							defined('MEDIA_HOST') ? MEDIA_HOST : null
						);
			}
			
			return $result;
		}
	}
?>