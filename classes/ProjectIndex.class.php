<?php
	/* $Id$ */

	if(!class_exists('Singleton', false))
		require_once FRAMEWORK_DIR . '/patterns/Singleton.class.php';
	
	function exceptionHandler($data)
	{
		return ProjectIndex::me()->exceptionHandler($data);
	}
	
	class ProjectIndex extends Singleton
	{
		private $errorTemplate = null;
		private $canShowDebug = true;
		
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
			$startTime = microtime(true);
		
			ob_start('exceptionHandler');
			
			$renderedOutput = null;
			
			try
			{
				$request = init();
				$renderedOutput = run($request);
			}
			catch(Exception $e)
			{
				echo $this->catchException($e);
				die;
			}
			
			$engineEcho = ob_get_clean();
		
			if(strlen($engineEcho))
				$this->catchEngineEcho($engineEcho, $renderedOutput);
			else
				echo $renderedOutput;
			
			if(Singleton::hasInstance('Debug') && Debug::me()->isEnabled())
			{
				$this->buildDebug($startTime, $engineEcho);
				
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
			$localizer = $request->getAttached(AttachedAliases::LOCALIZER);
			
			if($e->getCode() == PageException::PAGE_NOT_FOUND)
			{
				$localizer->setPath('/page-not-found.html');

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
			return DebugItem::create()->
				setType(DebugItem::REQUEST)->
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
		
		public function catchEngineEcho($engineEcho, $renderedOutput)
		{
			$fileName = LOG_DIR . '/engineEcho.txt';
			
			$logTime = file_exists($fileName) ? filemtime($fileName) : 0;
			
			file_put_contents(
				$fileName,
				date('Y-m-d h:i:s  ') . $_SERVER['REQUEST_URI'] . PHP_EOL . $engineEcho
					. PHP_EOL . PHP_EOL,
				FILE_APPEND
			);
			
			if(time() - $logTime > 180)
				mail(
					ADMIN_EMAIL,
					'Engine echo is not empty on host ' . $_SERVER['HTTP_HOST'],
					'Check log ' . $fileName . PHP_EOL . PHP_EOL
					. 'Last engine echo:' . PHP_EOL . $engineEcho
				);
			
			$hasBody = preg_match('/<body.*?>.*?<\/body>/is', $renderedOutput);
			
			if(Singleton::hasInstance('Debug') && Debug::me()->isEnabled())
				echo $hasBody
					? preg_replace('/(<body.*?>)/', '$1' . $engineEcho, $renderedOutput, 1)
					: $engineEcho . $renderedOutput;
			else
				echo $renderedOutput;
		}
		
		public function buildDebug($startTime, $engineEcho)
		{
			Debug::me()->addItem($this->createRequestDebugItem());
				
			$debugItem = DebugItem::create()->
				setType(DebugItem::ENGINE_ECHO)->
				setData($engineEcho)->
				setStartTime($startTime)->
				setEndTime(microtime(true));
		
			Debug::me()->addItem($debugItem);
		}

		public function exceptionHandler($data)
		{
		    $error = error_get_last();

		    if(
		    	in_array(
		    		$error['type'],
		    		array(E_ERROR, E_PARSE, E_RECOVERABLE_ERROR)
		    	)
		    )
		    	return $this->catchException(new DefaultException($data));
		    
		    return $data;
		}
	}
?>