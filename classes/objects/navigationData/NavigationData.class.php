<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NavigationData
	{
		/**
		 * @var Navigation
		 */
		private $navigation 	= null;
		
		private $navigationId	= null;
		
		/**
		 * @var Language
		 */
		private $language		= null;
		
		private $languageId		= null;
		
		private $text 	= null;
		
		/**
		 * @return NavigationData
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return NavigationDataDA
		 */
		public static function da()
		{
			return NavigationDataDA::me();
		}
		
		public function getId()
		{
			return $this->navigationId.'_'.$this->languageId;
		}
		
		/**
		 * @return NavigationData
		 */
		public function setNavigation(Navigation $navigation)
		{
			$this->navigation = $navigation;
			$this->navigationId = $navigation->getId();
			return $this;
		}
		
		/**
		 * @return Navigation
		 */
		public function getNavigation()
		{
			if (!$this->navigation && $this->getNavigationId()) {
				$this->setNavigation(
					Navigation::da()->getById($this->getNavigationId())
				);
			}
			
			return $this->navigation;
		}
		
		/**
		 * @return NavigationData
		 */
		public function setNavigationId($navigationId)
		{
			$this->navigation = null;
			$this->navigationId = $navigationId;
			
			return $this;
		}
		
		public function getNavigationId()
		{
			return $this->navigationId;
		}
		
		/**
		 * @return NavigationData
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
		 * @return NavigationData
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
		 * @return NavigationData
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