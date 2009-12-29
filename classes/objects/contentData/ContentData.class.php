<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ContentData
	{
		private $contentId	= null;
		
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
			Assert::isNotNull($this->getContentId());
			Assert::isNotNull($this->getLanguageId());
			
			return $this->getContentId().'_'.$this->getLanguageId();
		}
		
		/**
		 * @return ContentData
		 */
		public function setContentId($contentId)
		{
			$this->contentId = $contentId;
			return $this;
		}
		
		public function getContentId()
		{
			return $this->contentId;
		}
		
		/**
		 * @return Content
		 */
		public function getContent()
		{
			return Content::da()->getById($this->getContentId());
		}
		
		/**
		 * @return ContentData
		 */
		public function setLanguageId($languageId)
		{
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