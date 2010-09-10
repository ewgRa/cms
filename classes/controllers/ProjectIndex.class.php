<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ProjectIndex extends Singleton
	{
		private $startTime = null;
		
		/**
		 * @return ProjectIndex
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function setStartTime($time)
		{
			$this->startTime = $time;
			return $this;	
		}
		
		public function catchException(Exception $e)
		{
			$debugStored = false;

			try {
				$this->storeDebug();
				$debugStored = true;
			} catch (Exception $exception) {
				// very bad... even write debug failed
			}
			
			$fileName = LOG_DIR.'/errors.txt';
			
			$logTime =
				file_exists($fileName)
					? filemtime($fileName)
					: 0;
			
			$exceptionString =
				date('Y-m-d h:i:s ').$_SERVER['HTTP_HOST']
				.$_SERVER['REQUEST_URI']
				.(
					Debug::me()->isEnabled()
						? 
							' (debug hash: '.Debug::me()->getHash()
							. (
								$debugStored
									? ' [stored]'
									: ' [store failed]'
							)
							.')'
						: ' (without debug)'
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
					'Check log '.$fileName
					.(
						Debug::me()->isEnabled()
							? ' (debug hash: '.Debug::me()->getHash().')'
							: ' (without debug)'
					).PHP_EOL.PHP_EOL
					.'Last echo:'.PHP_EOL.$echo
				);
			}
			
			if (Debug::me()->isEnabled()) {
				$debugItem =
					EngineEchoDebugItem::create()->
					setData($echo)->
					setStartTime($this->startTime)->
					setEndTime(microtime(true));
				
				Debug::me()->addItem($debugItem);
			}

			return $this;
		}
		
		public function getOutput($output, $echo)
		{
			$result = $output;
			
			if ($echo && ini_get('display_errors')) {
				$hasBody = preg_match('/<body.*?>.*?<\/body>/is', $output);
			
				$result =
					$hasBody
						? preg_replace('/(<body.*?>)/', '$1'.$echo, $result, 1)
						: $echo.$result;
			}

			return $result;
		}

		public function storeDebug()
		{
			if (Debug::me()->isEnabled()) {
				Debug::me()->addItem(Debug::me()->createRequestDebugItem());

				DebugData::da()->insert(
					DebugData::create()->
						setSession(
							Session::me()->isStarted()
								? Session::me()->getId()
								: null
							
						)->
						setData(Debug::me()->getItems())
				);
			}	
			
			return $this;
		}
	}
?>