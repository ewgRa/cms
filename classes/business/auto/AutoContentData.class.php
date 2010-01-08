<?php
	/* $Id$ */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	class AutoContentData
	{
		private $contentId = null;
		
		/**
		 * @return Content
		 */
		private $content = null;
		
		private $languageId = null;
		
		/**
		 * @return Language
		 */
		private $language = null;
		
		private $text = null;
		
		/**
		 * @return ContentDataDA
		 */
		public static function da()
		{
			return ContentDataDA::me();
		}
		
		public function getId()
		{
			Assert::isNotNull($this->getContentId());
			Assert::isNotNull($this->getLanguageId());
			
			return $this->getContentId().'_'.$this->getLanguageId();
		}
		
		/**
		 * @return AutoContentData
		 */
		public function setContentId($contentId)
		{
			$this->contentId = $contentId;
			return $this;
		}
		
		public function getContentId()
		{
			Assert::isNotNull($this->contentId);
			return $this->contentId;
		}
		
		/**
		 * @return AutoContentData
		 */
		public function setContent(Content $content)
		{
			$this->content = $content;
			return $this;
		}
		
		/**
		 * @return Content
		 */
		public function getContent()
		{
			return Content::da()->getById($this->getContentId());
		}
		
		/**
		 * @return AutoContentData
		 */
		public function setLanguageId($languageId)
		{
			$this->languageId = $languageId;
			return $this;
		}
		
		public function getLanguageId()
		{
			Assert::isNotNull($this->languageId);
			return $this->languageId;
		}
		
		/**
		 * @return AutoContentData
		 */
		public function setLanguage(Language $language)
		{
			$this->language = $language;
			return $this;
		}
		
		/**
		 * @return Language
		 */
		public function getLanguage()
		{
			return Language::da()->getById($this->getLanguageId());
		}
		
		/**
		 * @return AutoContentData
		 */
		public function setText($text)
		{
			$this->text = $text;
			return $this;
		}
		
		public function getText()
		{
			Assert::isNotNull($this->text);
			return $this->text;
		}
	}
?>