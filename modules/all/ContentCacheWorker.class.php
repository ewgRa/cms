<?php
	/* $Id$ */

	class ContentCache extends CacheWorker
	{
		/**
		 * @return ContentCache
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
			
			if($this->cache()->hasTicketParams('content'))
			{
				$localizer = $request->getAttached(AttachedAliases::LOCALIZER);
				$page = $request->getAttached(AttachedAliases::PAGE);
				
				$result =
					$this->cache()->createTicket('content')->
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