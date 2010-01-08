<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Page
	{
		private $id			= null;
		private $path		= null;
		private $preg		= null;
		private $layoutId	= null;
		
		/**
		 * @var PageStatus
		 */
		private $status		= null;
		
		private $modified	= null;
		
		/**
		 * @return Page
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return PageDA
		 */
		public static function da()
		{
			return PageDA::me();
		}
		
		/**
		 * @return Page
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
		 * @return Page
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
		 * @return Page
		 */
		public function setPreg($preg = true)
		{
			$this->preg = ($preg === true);
			return $this;
		}

		/**
		 * @return Page
		 */
		public function getPreg()
		{
			return $this->preg;
		}
		
		public function isPreg()
		{
			return ($this->preg === true);
		}
		
		/**
		 * @return Page
		 */
		public function setLayoutId($fileId)
		{
			$this->layoutId = $fileId;
			return $this;
		}

		public function getLayoutId()
		{
			return $this->layoutId;
		}
		
		/**
		 * @return Layout
		 */
		public function getLayout()
		{
			return Layout::da()->getById($this->getLayoutId());
		}
		
		/**
		 * @return Page
		 */
		public function setStatus(PageStatus $status)
		{
			$this->status = $status;
			return $this;
		}

		/**
		 * @return PageStatus
		 */
		public function getStatus()
		{
			return $this->status;
		}
		
		/**
		 * @return Page
		 */
		public function setModified($modified)
		{
			$this->modified = $modified;
			return $this;
		}

		public function getModified()
		{
			return $this->modified;
		}
	}
?>