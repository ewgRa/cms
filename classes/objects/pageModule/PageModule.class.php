<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageModule
	{
		private $pageId = null;
		
		private $page = null;
		
		private $moduleId = null;
		
		private $module = null;
		
		private $section = null;
		
		private $position = null;
		
		private $priority = null;
		
		private $settings = null;
		
		private $viewFileId = null;
		
		private $viewFile = null;
		
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
		 * @return PageModule
		 */
		public function setPage(Page $page)
		{
			$this->page 	= $mage;
			$this->pageId	= $page->getId();
			return $this;
		}
		
		/**
		 * @return Page
		 */
		public function getPage()
		{
			return $this->page;
		}
		
		/**
		 * @return PageModule
		 */
		public function setModuleId($moduleId)
		{
			$this->moduleId = $moduleId;
			$this->module	= null;
			return $this;
		}
		
		public function getModuleId()
		{
			return $this->moduleId;
		}

		/**
		 * @return PageModule
		 */
		public function setModule(Module $module)
		{
			$this->module 	= $module;
			$this->moduleId = $module->getId();
			return $this;
		}
		
		/**
		 * @return Module
		 */
		public function getModule()
		{
			if (!$this->module && $this->getModuleId()) {
				$this->setModule(
					Module::da()->getById($this->getModuleId())
				);
			}
			
			return $this->module;
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
		 * @return PageModule
		 */
		public function setViewFile(ViewFile $viewFile)
		{
			$this->viewFile = $viewFile;
			$this->viewFileId = $viewFile->getId();
			return $this;
		}
		
		/**
		 * @return ViewFile
		 */
		public function getViewFile()
		{
			if (!$this->viewFile && $this->getViewFileId()) {
				$this->setViewFile(
					ViewFile::da()->getById($this->getViewFileId())
				);
			}
			
			return $this->viewFile;
		}
	}
?>