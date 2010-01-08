<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageData
	{
		private $pageId	= null;
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
			Assert::isNotNull($this->getPageId());
			Assert::isNotNull($this->getLanguageId());
			
			return $this->getPageId().'_'.$this->getLanguageId();
		}
		
		/**
		 * @return PageData
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
			return Page::da()->getById($this->getPageId());
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
		 * @return Language
		 */
		public function getLanguage()
		{
			return Language::da()->getById($this->getLanguageId());
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