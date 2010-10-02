<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Debug extends Singleton
	{
		private $hash = null;
		
		private $enabled = null;
		
		private $items	 = array();
		
		/**
		 * @return Debug
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public static function traceToDisplay(array $trace)
		{
			foreach ($trace as $key => &$oneTrace) {
				$resultTrace = '#'.$key.' ';
				
				if (isset($oneTrace['class']))
					$resultTrace .= $oneTrace['class'].$oneTrace['type'];
				
				$resultTrace .= $oneTrace['function'].'(';
				
				if ($oneTrace['args']) {
					$args = array();
					
					foreach ($oneTrace['args'] as $arg) {
						$type = gettype($arg);
						
						switch ($type) {
							case 'object':
								$args[] = get_class($arg);
								break;
							default;
								$args[] = $type;
								break;
						}
					}
					
					$resultTrace .= join(', ', $args);
				}
				
				$resultTrace .= ')';
				
				if (isset($oneTrace['file']))
					$resultTrace .= ' called at ['.$oneTrace['file'].':'.$oneTrace['line'].']';
				
				$oneTrace = $resultTrace;
			}
			
			return $trace; 		
		}
		
		/**
		 * @return Debug
		 */
		public function relativeStart()
		{
			if (
				ini_get('display_errors')
				|| (
					isset($_COOKIE['enableDebug'])
					&& Session::me()->relativeStart()
					&& Session::me()->isStarted()
					&& Session::me()->get('enableDebug')
				)
			)
				$this->enable();
			
			return $this;
		}
		/**
		 * @return Debug
		 */
		public function enable()
		{
			$this->enabled = true;
			return $this;
		}
		
		/**
		 * @return Debug
		 */
		public function disable()
		{
			$this->enabled = null;
			return $this;
		}
		
		public function isEnabled()
		{
			return $this->enabled;
		}
		
		/**
		 * @return Debug
		 */
		public function addItem(DebugItem $item)
		{
			$this->items[] = $item;
			return $this;
		}
		
		public function getItems()
		{
			return $this->items;
		}

		/**
		 * @return DebugItem
		 */
		public function getItem($index)
		{
			return $this->items[$index];
		}

		/**
		 *@return DebugItem
		 */
		public function addRequestDebugItem()
		{
			$this->addItem(
				DebugItem::create()->
				setAlias('request')->
				setTrace(Debug::traceToDisplay(debug_backtrace()))->
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
				)
			);
			
			return $this;
		}
		
		public function getHash()
		{
			if (!$this->hash)
				$this->hash = md5(microtime().' '.rand(0, 1000000));
			
			return $this->hash;
		}
		
		public function getAsXml()
		{
			$domDocument = ExtendedDomDocument::create();
			
			foreach ($this->getItems() as $item) {
				$domDocument->appendChild(
					$domDocument->createNodeFromVar($item->getData(), $item->getAlias())
				); 
			}
			
			return $domDocument->saveXml();
		} 
	}
?>