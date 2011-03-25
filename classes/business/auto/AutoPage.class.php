<?php
	namespace ewgraCms;
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoPage
	{
		private $id = null;
		
		private $path = null;
		
		/**
		 * @var boolean
		 */
		private $preg = null;
		
		private $layoutId = null;
		
		/**
		 * @var Layout
		 */
		private $layout = null;
		
		/**
		 * @var array
		 */
		private $layoutSettings = null;
		
		/**
		 * @var PageStatus
		 */
		private $status = null;
		
		private $modified = null;
		
		/**
		 * @return PageDA
		 */
		public static function da()
		{
			return PageDA::me();
		}
		
		/**
		 * @return AutoPage
		 */
		public function setId($id)
		{
			$this->id = $id;

			return $this;
		}
		
		public function getId()
		{
			\ewgraFramework\Assert::isNotNull($this->id);
			return $this->id;
		}
		
		/**
		 * @return AutoPage
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
		 * @return AutoPage
		 */
		public function setPreg($preg = true)
		{
			$this->preg = ($preg === true);

			return $this;
		}
		
		/**
		 * @return boolean
		 */
		public function getPreg()
		{
			return $this->preg;
		}
		
		public function isPreg()
		{
			return ($this->getPreg() === true);
		}
		
		/**
		 * @return AutoPage
		 */
		public function setLayoutId($layoutId)
		{
			$this->layout = null;
			$this->layoutId = $layoutId;

			return $this;
		}
		
		public function getLayoutId()
		{
			return $this->layoutId;
		}
		
		/**
		 * @return AutoPage
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
			if (!$this->layout && $this->getLayoutId())
				$this->layout = Layout::da()->getById($this->getLayoutId());
				
			return $this->layout;
		}
		
		/**
		 * @return AutoPage
		 */
		public function setLayoutSettings(array $layoutSettings = null)
		{
			$this->layoutSettings = $layoutSettings;

			return $this;
		}
		
		/**
		 * @return array
		 */
		public function getLayoutSettings()
		{
			return $this->layoutSettings;
		}
		
		/**
		 * @return AutoPage
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
		 * @return AutoPage
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