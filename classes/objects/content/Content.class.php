<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Content
	{
		private $id			= null;
		
		/**
		 * @var ContentStatus
		 */
		private $status 	= null;
		
		/**
		 * @return Content
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return ContentDA
		 */
		public static function da()
		{
			return ContentDA::me();
		}

		/**
		 * @return Content
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
		 * @return Content
		 */
		public function setStatus(ContentStatus $status)
		{
			$this->status = $status;
			return $this;
		}
		
		/**
		 * @return ContentStatus
		 */
		public function getStatus()
		{
			return $this->status;
		}
	}
?>