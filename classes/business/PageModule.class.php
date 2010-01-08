<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageModule
	{
		private $pageId = null;
		private $moduleId = null;
		private $section = null;
		private $position = null;
		private $priority = null;
		private $settings = null;
		private $viewFileId = null;
		
		/**
		 * @return PageModule
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return PageModuleDA
		 */
		public static function da()
		{
			return PageModuleDA::me();
		}

		public function getId()
		{
			Assert::isNotNull($this->getPageId());
			Assert::isNotNull($this->getModuleId());
			Assert::isNotNull($this->getSection());
			Assert::isNotNull($this->getPosition());
			
			return
				$this->getPageId().'_'.$this->getModuleId()
				.'_'.$this->getSection().'_'.$this->getPosition();
		}
		
		/**
		 * @return PageModule
		 */
		public function setPageId($pageId)
		{
			$this->pageId = $pageId;
			return $this;
		}
		
		public function getPageId()
		{
			return $this->pageId;
		}

		/**
		 * @return Page
		 */
		public function getPage()
		{
			return Page::da()->getById($this->getPageId);
		}
		
		/**
		 * @return PageModule
		 */
		public function setModuleId($moduleId)
		{
			$this->moduleId = $moduleId;
			return $this;
		}
		
		public function getModuleId()
		{
			return $this->moduleId;
		}

		/**
		 * @return Module
		 */
		public function getModule()
		{
			return Module::da()->getById($this->getModuleId());
		}

		/**
		 * @return PageModule
		 */
		public function setSection($section)
		{
			$this->section = $section;
			return $this;
		}
		
		public function getSection()
		{
			return $this->section;
		}

		/**
		 * @return PageModule
		 */
		public function setPosition($position)
		{
			$this->position = $position;
			return $this;
		}
		
		public function getPosition()
		{
			return $this->position;
		}

		/**
		 * @return PageModule
		 */
		public function setPriority($priority)
		{
			$this->priority = $priority;
			return $this;
		}
		
		public function getPriority()
		{
			return $this->priority;
		}

		/**
		 * @return PageModule
		 */
		public function setSettings(array $settings = null)
		{
			$this->settings = $settings;
			return $this;
		}
		
		public function getSettings()
		{
			return $this->settings;
		}

		/**
		 * @return PageModule
		 */
		public function setViewFileId($id)
		{
			$this->viewFileId = $id;
			$this->viewFile = null;
			return $this;
		}
		
		public function getViewFileId()
		{
			return $this->viewFileId;
		}

		/**
		 * @return ViewFile
		 */
		public function getViewFile()
		{
			return ViewFile::da()->getById($this->getViewFileId());
		}
	}
?>