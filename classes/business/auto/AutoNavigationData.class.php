<?php
	/* $Id$ */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoNavigationData
	{
		private $navigationId = null;
		
		private $languageId = null;
		
		private $text = null;
		
		/**
		 * @return NavigationDataDA
		 */
		public static function da()
		{
			return NavigationDataDA::me();
		}
		
		/**
		 * @return AutoNavigationData
		 */
		public function setNavigationId($navigationId)
		{
			$this->navigationId = $navigationId;
			return $this;
		}
		
		public function getNavigationId()
		{
			return $this->navigationId;
		}
		
		/**
		 * @return AutoNavigationData
		 */
		public function setNavigation(Navigation $navigation)
		{
			$this->navigationId = $navigation->getId();
			return $this;
		}
		
		/**
		 * @return Navigation
		 */
		public function getNavigation()
		{
			return Navigation::da()->getById($this->getNavigationId());
		}
		
		/**
		 * @return AutoNavigationData
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
		 * @return AutoNavigationData
		 */
		public function setLanguage(Language $language)
		{
			$this->languageId = $language->getId();
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
		 * @return AutoNavigationData
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