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
		 * @var Layout
		 */
		private $layout		= null;
		
		private $status		= null;
		private $modified	= null;


		
		private $header			= null;
		
		/**
		 * @return Page
		 */
		public static function create()
		{
			return new self;
		}
		
		public function __construct()
		{
			$this->header = PageHeader::create();
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
			return $this->id;
		}

		/**
		 * @return Page
		 */
		public function setLayoutId($fileId)
		{
			$this->layoutId = $fileId;
			$this->layout = null;
			return $this;
		}

		public function getLayoutId()
		{
			return $this->layoutId;
		}
		
		/**
		 * @return Page
		 */
		public function setLayout(Layout $layout)
		{
			$this->layoutId = $layout->getId();
			$this->layout = $layout;
			return $this;
		}

		/**
		 * @return Layout
		 */
		public function getLayout()
		{
			if (!$this->layout && $this->getLayoutId()) {
				$this->setLayout(
					Layout::da()->getById($this->getLayoutId())
				);
			}
			
			return $this->layout;
		}
		
		/**
		 * @return Page
		 */
		public function setStatus($status)
		{
			$this->status = $status;
			return $this;
		}

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
		public function setPreg($preg)
		{
			$this->preg = $preg;
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
			return $this->preg == true;
		}
		
		/**
		 * @return PageHeader
		 */
		public function getHeader()
		{
			return $this->header;
		}
	}
?>