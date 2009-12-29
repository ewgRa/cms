<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DebugData
	{
		private $id			= null;
		private $session 	= null;
		private $data	 	= null;
		private $date		= null;
		
		/**
		 * @return DebugData
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return DebugDataDA
		 */
		public static function da()
		{
			return DebugDataDA::me();
		}
		
		/**
		 * @return DebugData
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}

		public function getId()
		{
			return $this->id;
		}
		
		/**
		 * @return DebugData
		 */
		public function setSession($session)
		{
			$this->session = $session;
			return $this;
		}

		public function getSession()
		{
			return $this->session;
		}

		/**
		 * @return DebugData
		 */
		public function setData(array $data)
		{
			$this->data = $data;
			return $this;
		}

		public function getData()
		{
			return $this->data;
		}

		/**
		 * @return DebugData
		 */
		public function setDate($date)
		{
			$this->date = $date;
			return $this;
		}

		public function getDate()
		{
			return $this->date;
		}
	}
?>