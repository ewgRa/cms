<?php
	namespace ewgraCms;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoPageController
	{
		private $id = null;

		private $pageId = null;

		/**
		 * @var Page
		 */
		private $page = null;

		private $controllerId = null;

		/**
		 * @var Controller
		 */
		private $controller = null;

		private $section = null;

		private $position = null;

		private $priority = null;

		/**
		 * @var array
		 */
		private $settings = null;

		private $viewFileId = null;

		/**
		 * @var ViewFile
		 */
		private $viewFile = null;

		/**
		 * @return PageControllerDA
		 */
		public static function da()
		{
			return PageControllerDA::me();
		}

		/**
		 * @return AutoPageController
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
		 * @return AutoPageController
		 */
		public function setPageId($pageId)
		{
			$this->page = null;
			$this->pageId = $pageId;

			return $this;
		}

		public function getPageId()
		{
			return $this->pageId;
		}

		/**
		 * @return AutoPageController
		 */
		public function setPage(Page $page)
		{
			$this->pageId = $page->getId();
			$this->page = $page;

			return $this;
		}

		/**
		 * @return Page
		 */
		public function getPage()
		{
			if (!$this->page && $this->getPageId())
				$this->page = Page::da()->getById($this->getPageId());

			return $this->page;
		}

		/**
		 * @return AutoPageController
		 */
		public function setControllerId($controllerId)
		{
			$this->controller = null;
			$this->controllerId = $controllerId;

			return $this;
		}

		public function getControllerId()
		{
			return $this->controllerId;
		}

		/**
		 * @return AutoPageController
		 */
		public function setController(Controller $controller)
		{
			$this->controllerId = $controller->getId();
			$this->controller = $controller;

			return $this;
		}

		/**
		 * @return Controller
		 */
		public function getController()
		{
			if (!$this->controller && $this->getControllerId())
				$this->controller = Controller::da()->getById($this->getControllerId());

			return $this->controller;
		}

		/**
		 * @return AutoPageController
		 */
		public function setSection($section = null)
		{
			$this->section = $section;

			return $this;
		}

		public function getSection()
		{
			return $this->section;
		}

		/**
		 * @return AutoPageController
		 */
		public function setPosition($position = null)
		{
			$this->position = $position;

			return $this;
		}

		public function getPosition()
		{
			return $this->position;
		}

		/**
		 * @return AutoPageController
		 */
		public function setPriority($priority = null)
		{
			$this->priority = $priority;

			return $this;
		}

		public function getPriority()
		{
			return $this->priority;
		}

		/**
		 * @return AutoPageController
		 */
		public function setSettings(array $settings = null)
		{
			$this->settings = $settings;

			return $this;
		}

		/**
		 * @return array
		 */
		public function getSettings()
		{
			return $this->settings;
		}

		/**
		 * @return AutoPageController
		 */
		public function setViewFileId($viewFileId = null)
		{
			$this->viewFile = null;
			$this->viewFileId = $viewFileId;

			return $this;
		}

		public function getViewFileId()
		{
			return $this->viewFileId;
		}

		/**
		 * @return AutoPageController
		 */
		public function setViewFile(ViewFile $viewFile = null)
		{
			$this->viewFileId = $viewFile->getId();
			$this->viewFile = $viewFile;

			return $this;
		}

		/**
		 * @return ViewFile
		 */
		public function getViewFile()
		{
			if (!$this->viewFile && $this->getViewFileId())
				$this->viewFile = ViewFile::da()->getById($this->getViewFileId());

			return $this->viewFile;
		}
	}
?>