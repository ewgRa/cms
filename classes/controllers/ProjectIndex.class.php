<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ProjectIndex extends Singleton
	{
		/**
		 * @return ProjectIndex
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
			
		public function run()
		{
			$request	= init();
			$output		= run($request);

			if(Debug::me()->isEnabled())
				Debug::me()->addItem($this->createRequestDebugItem());

			if ($request->hasAttachedVar(AttachedAliases::PAGE_HEADER))
				$request->getAttachedVar(AttachedAliases::PAGE_HEADER)->output();
			
			return $output;
		}
		
		public function sendUnavailableHeaders()
		{
			header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Temporarily Unavailable');
			header('Status: 503 Service Temporarily Unavailable');
			header('Retry-After: 3600');
			
			return $this;
		}

		public function catchException(Exception $e, $storeDebug = true)
		{
			$fileName = LOG_DIR.'/errors.txt';
			
			$logTime =
				file_exists($fileName)
					? filemtime($fileName)
					: 0;
			
			$debugItem = null;
			
			if ($storeDebug && Debug::me()->isEnabled()) {
				try {
					Debug::me()->addItem($this->createRequestDebugItem());

					$debugItem =
						DebugData::da()->insert(
							DebugData::create()->
								setSession(Session::me()->getId())->
								setData(Debug::me()->getItems())
						);
					
				} catch (Exception $exception) {
					// very bad... even write debugItem failed
					$this->catchException($exception, false);
				}
			}
			
			$exceptionString =
				date('Y-m-d h:i:s ').$_SERVER['HTTP_HOST']
				.$_SERVER['REQUEST_URI']
				.(
					$debugItem
						? ' (debugItemId: '.$debugItem->getId().')'
						: null
				)
				.PHP_EOL.$e.PHP_EOL.PHP_EOL.PHP_EOL;
			
			file_put_contents(
				$fileName,
				$exceptionString,
				FILE_APPEND
			);
	
			if (time() - $logTime > 180) {
				mail(
					ADMIN_EMAIL,
					'Exception on '.$_SERVER['HTTP_HOST'],
					'Check log: '.$fileName.PHP_EOL.PHP_EOL
					.'Last exception:'.PHP_EOL.$exceptionString
				);
			}
						
			return $this;
		}
		
		public function catchPageNotFoundException(HttpRequest $request) {
			$request->setUrl(HttpUrl::createFromString('/page-not-found.html'));

			$modelAndView = ModelAndView::create();
			
			$chainController = createCommonChain();
			$chainController->handleRequest($request, $modelAndView);
			
			return $modelAndView->render();
		}
		
		/**
		 * @return ProjectIndex
		 */
		public function catchEcho($echo)
		{
			$fileName = LOG_DIR.'/echo.txt';
			
			$logTime =
				file_exists($fileName)
					? filemtime($fileName)
					: 0;
			
			file_put_contents(
				$fileName,
				date('Y-m-d h:i:s ').$_SERVER['REQUEST_URI'].PHP_EOL
				.$echo.PHP_EOL.PHP_EOL.PHP_EOL,
				FILE_APPEND
			);
			
			if (time() - $logTime > 180) {
				mail(
					ADMIN_EMAIL,
					'Echo is not empty on host '.$_SERVER['HTTP_HOST'],
					'Check log '.$fileName.PHP_EOL.PHP_EOL
					.'Last echo:'.PHP_EOL.$echo
				);
			}
			
			return $this;
		}
		
		public function getOutput($output, $echo)
		{
			$result = $output;
			
			$hasBody = preg_match('/<body.*?>.*?<\/body>/is', $output);
			
			if ($echo && ini_get('display_errors')) {
				$result =
					$hasBody
						? preg_replace('/(<body.*?>)/', '$1'.$echo, $result, 1)
						: $echo.$result;
			}

			return $result;
		}
		
		/**
		 *@return CmsDebugItem
		 */
		private function createRequestDebugItem()
		{
			return
				RequestDebugItem::create()->
				setData(
					array(
						'get'	 	=> $_GET,
						'post'	 	=> $_POST,
						'server' 	=> $_SERVER,
						'cookie' 	=> $_COOKIE,
						'session'	=>
							isset($_SESSION)
								? $_SESSION
								: array()
					)
				);
		}
	}
?>