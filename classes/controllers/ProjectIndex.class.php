<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ProjectIndex extends \ewgraFramework\Singleton
	{
		private $startTime = null;

		/**
		 * @return ProjectIndex
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		public function setStartTime($time)
		{
			$this->startTime = $time;
			return $this;
		}

		public function catchException(\Exception $e)
		{
			$debugStored = false;

			try {
				Debug::me()->addRequestDebugItem();
				// FIXME XXX: debug queue storage
				// var_dump(Debug::me()->getAsXml());
				$debugStored = true;
			} catch (Exception $exception) {
				// very bad... even write debug failed
			}

			$exceptionString =
				$this->getRunUrl()
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

			error_log($exceptionString);

			return $this;
		}

		/**
		 * @return ProjectIndex
		 */
		public function catchEcho($echo)
		{
			error_log(
				$this->getRunUrl().PHP_EOL
				.$echo.PHP_EOL.PHP_EOL.PHP_EOL
			);

			if (Debug::me()->isEnabled()) {
				$debugItem =
					DebugItem::create()->
					setAlias('echo')->
					setTrace(Debug::traceToDisplay(debug_backtrace()))->
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

		private function getRunUrl()
		{
			return
				PHP_SAPI != 'cli'
					? $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
					: 'file://'.gethostname().getcwd().'/'.$_SERVER['argv'][0];
		}
	}
?>