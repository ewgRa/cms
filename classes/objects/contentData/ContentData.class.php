<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ContentData
	{
		/**
		 * @var Content
		 */
		private $content 	= null;
		
		private $contentId	= null;
		
		/**
		 * @var Language
		 */
		private $language		= null;
		
		private $languageId		= null;
		
		private $text 	= null;
		
		/**
		 * @return ContentData
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return ContentDataDA
		 */
		public static function da()
		{
			return ContentDataDA::me();
		}
		
		public function getId()
		{
			return $this->contentId.'_'.$this->languageId;
		}
		
		/**
		 * @return ContentData
		 */
		public function setContent(Content $content)
		{
			$this->content = $content;
			$this->contentId = $content->getId();
			return $this;
		}
		
		/**
		 * @return Content
		 */
		public function getContent()
		{
			if (!$this->content && $this->getContentId()) {
				$this->setContent(
					Content::da()->getById($this->getContentId())
				);
			}
			
			return $this->content;
		}
		
		/**
		 * @return ContentData
		 */
		public function setContentId($contentId)
		{
			$this->content = null;
			$this->contentId = $contentId;
			
			return $this;
		}
		
		public function getContentId()
		{
			return $this->contentId;
		}
		
		/**
		 * @return ContentData
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
		 * @return ContentData
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
		 * @return ContentData
		 */
		public function setText($text)
		{
			$this->text = $text;
			return $this;
		}
		
		public function getText()
		{
			return $this->text;
		}
	}
?>