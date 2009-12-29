<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ViewFile
	{
		private $id			= null;
		
		/**
		 * @var ContentType
		 */
		private $contentType = null;
		
		private $path = null;
		
		private $joinable = null;
		
		/**
		 * @return ViewFile
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return ViewFileDA
		 */
		public static function da()
		{
			return ViewFileDA::me();
		}

		/**
		 * @return ViewFile
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}

		public function getId()
		{
			Assert::isNotNull($this->id);
			return $this->id;
		}
		
		/**
		 * @return ViewFile
		 */
		public function setContentType(ContentType $contentType)
		{
			$this->contentType = $contentType;
			return $this;
		}
		
		/**
		 * @return ContentType
		 */
		public function getContentType()
		{
			return $this->contentType;
		}
		
		/**
		 * @return ViewFile
		 */
		public function setPath($path)
		{
			$this->path = $path;
			return $this;
		}
		
		public function getPath()
		{
			return $this->path;
		}

		/**
		 * @return ViewFile
		 */
		public function setJoinable($joinable)
		{
			$this->joinable = $joinable;
			return $this;
		}
		
		public function isJoinable()
		{
			return $this->joinable;
		}
	}
?>