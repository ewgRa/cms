<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Page
	{
		private $id				= null;
		private $layoutFileId	= null;
		private $preg			= null;
		private $rights				= null;
		private $inheritanceRights	= null;
		private $path			= null;
		
		private $baseUrl		= null;
		
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
		public function setLayoutFileId($fileId)
		{
			$this->layoutFileId = $fileId;
			return $this;
		}

		public function getLayoutFileId()
		{
			return $this->layoutFileId;
		}

		/**
		 * @return Page
		 */
		public function setLayoutId($fileId)
		{
			$this->layoutFileId = $fileId;
			return $this;
		}

		public function getLayoutId()
		{
			return $this->layoutFileId;
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
		public function setBaseUrl(HttpUrl $url)
		{
			$this->baseUrl = $url;
			return $this;
		}

		public function getBaseUrl()
		{
			return $this->baseUrl;
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
		 * @return Page
		 */
		public function setRights(array $rights)
		{
			$this->rights = $rights;
			return $this;
		}

		public function getRights()
		{
			return $this->rights;
		}

		public function getRightIds()
		{
			$result = array();
			
			foreach ($this->getRights() as $pageRight) {
				$result[] = $pageRight->getRightId();
			}
			
			return $result;
		}
		
		public function getInheritanceRightIds()
		{
			return
				$this->inheritanceRights
					? array_keys($this->inheritanceRights)
					: null;
		}
		
		/**
		 * @return Page
		 */
		public function loadRights()
		{
			$this->rights = PageRight::da()->getByPage($this);
			$this->inheritanceRights = array();
			
			$inheritanceRights = Right::da()->getByInheritanceIds($this->getRightIds());
			
			while ($inheritanceRights) {
				$inheritanceIds = array();
				
				foreach ($inheritanceRights as $right) {
					if (!isset($this->inheritanceRights[$right->getId()])) {
						$this->inheritanceRights[$right->getId()] = $right;
						$inheritanceIds[] = $right->getId();
					}
				}

				$inheritanceRights = Right::da()->getByInheritanceIds($inheritanceIds);
			}
			
			return $this;
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