<?php
	/* $Id$ */

	class ProjectIndex extends Singleton
	{
		private $errorTemplate 	= null;
		private $canShowDebug 	= true;
		
		/**
		 * @return ProjectIndex
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
			
		public function __construct()
		{
			$this->errorTemplate = SITE_DIR . '/view/html/haserror.html';
			return parent::__construct();
		}
		
		/**
		 * @return ProjectIndex
		 */
		public function showDebug()
		{
			$this->canShowDebug = true;
			return $this;
		}
		
		/**
		 * @return ProjectIndex
		 */
		public function hideDebug()
		{
			$this->canShowDebug = false;
			return $this;
		}
		
		public function canShowDebug()
		{
			return $this->canShowDebug;
		}
		
		/**
		 * @return ProjectIndex
		 */
		public function setErrorTemplate($template)
		{
			$this->errorTemplate = $template;
			return $this;
		}
		
		public function getErrorTemplate()
		{
			return $this->errorTemplate;
		}
		
		public function run()
		{
			$startTime 	= microtime(true);
			$output 	= null;
			
			ob_start('outputHandler');
			
			try
			{
				$request = init();
				$output = run($request);
			}
			catch(Exception $e)
			{
				error_log($e);
				$this->obEnd($this->catchException($e));
				die;
			}
			
			$echo = $this->obEnd($output);

			if(Singleton::hasInstance('Debug') && Debug::me()->isEnabled())
			{
				$this->buildDebug($startTime, $echo);
				
				if(Session::me()->isStarted())
					Debug::me()->store();
			}
		}
		
		public function catchException(Exception $e, $storeDebug = true)
		{
			header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Temporarily Unavailable');
			header('Status: 503 Service Temporarily Unavailable');
			header('Retry-After: 3600');
			
			$fileName = LOG_DIR . '/errors.txt';
			$logTime = file_exists($fileName) ? filemtime($fileName) : 0;
			$debugItemId = null;
			
			if($storeDebug)
			{
				try
				{
					if(Singleton::hasInstance('Debug'))
					{
						Debug::me()->addItem($this->createRequestDebugItem());
						$debugItemId = Debug::me()->store();
					}
				}
				catch(Exception $exception)
				{
					// very bad... even write debugItem failed
					$this->catchException($exception, false);
				}
			}
			
			$exceptionString = date('Y-m-d h:i:s ') . $_SERVER['HTTP_HOST']
				. $_SERVER['REQUEST_URI']
				. ($debugItemId ? ' (debugItemId: ' . $debugItemId . ')' : null)
				. PHP_EOL . $e . PHP_EOL . PHP_EOL;
			
			file_put_contents(
				$fileName,
				$exceptionString,
				FILE_APPEND
			);
	
			if(time() - $logTime > 180)
				mail(
					ADMIN_EMAIL,
					'Exception on ' . $_SERVER['HTTP_HOST'],
					'Check log: ' . $fileName . PHP_EOL . PHP_EOL . 'Last exception:'
						. PHP_EOL . $exceptionString
				);
						
			$result = null;

			if(
				Singleton::hasInstance('Debug')
				&& Debug::me()->isEnabled()
				&& $this->canShowDebug()
			)
				$result = ($e instanceof DefaultException) ? $e->toHtmlString() : $e;
			else
				$result = file_get_contents($this->getErrorTemplate());
				
			return $result;
		}
		
		public function catchPageNotFoundException(
			HttpRequest $request,
			PageException $e
		) {
			if($e->getCode() == PageException::PAGE_NOT_FOUND)
			{
				$request->setUrl(HttpUrl::create()->parse('/page-not-found.html'));

				$chainController = createCommonChain();
	
				$modelAndView = ModelAndView::create();
				
				$chainController->handleRequest($request, $modelAndView);
				
				return $modelAndView->render();
			}
			else
				throw $e;
		}
		
		public function createRequestDebugItem()
		{
			return CmsDebugItem::create()->
				setType(CmsDebugItem::REQUEST)->
				setData(
					array(
						'get'	 	=> $_GET,
						'post'	 	=> $_POST,
						'server' 	=> $_SERVER,
						'cookie' 	=> $_COOKIE,
						'session'	=> isset($_SESSION) ? $_SESSION : array()
					)
				);
		}
		
		public function catchEcho($echo, $output)
		{
			$fileName = LOG_DIR . '/echo.txt';
			
			$logTime = file_exists($fileName) ? filemtime($fileName) : 0;
			
			file_put_contents(
				$fileName,
				date('Y-m-d h:i:s  ') . $_SERVER['REQUEST_URI'] . PHP_EOL . $echo
					. PHP_EOL . PHP_EOL,
				FILE_APPEND
			);
			
			if(time() - $logTime > 180)
				mail(
					ADMIN_EMAIL,
					'Echo is not empty on host ' . $_SERVER['HTTP_HOST'],
					'Check log ' . $fileName . PHP_EOL . PHP_EOL
					. 'Last echo:' . PHP_EOL . $echo
				);
			
			$hasBody = preg_match('/<body.*?>.*?<\/body>/is', $output);
			
			if(Singleton::hasInstance('Debug') && Debug::me()->isEnabled())
				echo $hasBody
					? preg_replace('/(<body.*?>)/', '$1' . $echo, $output, 1)
					: $echo . $output;
			else
				echo $output;
		}
		
		public function handleOutput($data)
		{
		    $error = error_get_last();

		    if(
		    	in_array(
		    		$error['type'],
		    		array(E_ERROR, E_PARSE, E_RECOVERABLE_ERROR)
		    	)
		    )
		    	return $this->catchException(DefaultException::create($data));
		    
		    return $data;
		}
		
		private function obEnd($output)
		{
			$echo = ob_get_clean();

			if(strlen($echo))
				$this->catchEcho($echo, $output);
			else
				echo $output;
				
			return $echo;
		}

		private function buildDebug($startTime, $echo)
		{
			Debug::me()->addItem($this->createRequestDebugItem());
				
			$debugItem = CmsDebugItem::create()->
				setType(CmsDebugItem::ENGINE_ECHO)->
				setData($echo)->
				setStartTime($startTime)->
				setEndTime(microtime(true));
		
			Debug::me()->addItem($debugItem);
		}
	}
?>