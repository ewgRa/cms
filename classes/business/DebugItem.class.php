<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DebugItem
	{
		private $alias 		= null;
		
		private $trace 		= null;

		private $data  		= null;
		
		private $startTime  = null;
		private $endTime	= null;
		
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return DebugItem
		 */
		public function setAlias($alias)
		{
			$this->alias = $alias;
			return $this;
		}
		
		public function getAlias()
		{
			return $this->alias;
		}
		
		/**
		 * @return DebugItem
		 */
		public function setStartTime($time)
		{
			$this->startTime = $time;
			return $this;
		}
		
		public function getStartTime()
		{
			return $this->startTime;
		}
		
		/**
		 * @return DebugItem
		 */
		public function setEndTime($time)
		{
			$this->endTime = $time;
			return $this;
		}
		
		public function getEndTime()
		{
			return $this->endTime;
		}
		
		/**
		 * @return DebugItem
		 */
		public function setTrace($trace)
		{
			$this->trace = $trace;
			return $this;
		}
		
		public function getTrace()
		{
			return $this->trace;
		}
		
		/**
		 * @return DebugItem
		 */
		public function setData($data)
		{
			$this->data = $data;
			return $this;
		}
		
		public function getData()
		{
			return $this->data;
		}
	}
?>