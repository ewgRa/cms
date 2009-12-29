<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageData
	{
		/**
		 * @var Page
		 */
		private $page 	= null;
		
		private $pageId	= null;
		
		/**
		 * @var Language
		 */
		private $language		= null;
		
		private $languageId		= null;
		
		private $title			= null;
		private $description	= null;
		private $keywords		= null;
		
		/**
		 * @return PageData
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return PageDataDA
		 */
		public static function da()
		{
			return PageDataDA::me();
		}
		
		public function getId()
		{
			return $this->pageId.'_'.$this->languageId;
		}
		
		/**
		 * @return PageData
		 */
		public function setPage(Page $page)
		{
			$this->page = $page;
			$this->pageId = $page->getId();
			return $this;
		}
		
		/**
		 * @return Page
		 */
		public function getPage()
		{
			if (!$this->page && $this->getPageId()) {
				$this->setPage(
					Page::da()->getById($this->getPageId())
				);
			}
			
			return $this->page;
		}
		
		/**
		 * @return PageData
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
		 * @return PageData
		 */
		public function setLanguage(Language $language)
		{
			$this->language = $language;
			$this->languageId = $language->getId();
			return $this;
		}
		
		/**
		 * @return Language
		 */
		public function getLanguage()
		{
			if (!$this->language && $this->getLanguageId()) {
				$this->setLanguage(
					Language::da()->getById($this->getLanguageId())
				);
			}
			
			return $this->language;
		}
		
		/**
		 * @return PageData
		 */
		public function setLanguageId($languageId)
		{
			$this->language = null;
			$this->languageId = $languageId;
			
			return $this;
		}
		
		public function getLanguageId()
		{
			return $this->languageId;
		}
		
		/**
		 * @return PageData
		 */
		public function setTitle($title)
		{
			$this->title = $title;
			return $this;
		}
		
		public function getTitle()
		{
			return $this->title;
		}

		/**
		 * @return PageData
		 */
		public function setDescription($description)
		{
			$this->description = $description;
			return $this;
		}
		
		public function getDescription()
		{
			return $this->description;
		}

		/**
		 * @return PageData
		 */
		public function setKeywords($keywords)
		{
			$this->keywords = $keywords;
			return $this;
		}
		
		public function getKeywords()
		{
			return $this->keywords;
		}
	}
?>