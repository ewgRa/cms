<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NavigationData
	{
		private $navigationId	= null;
		
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
			Assert::isNotNull($this->getNavigationId());
			Assert::isNotNull($this->getLanguageId());
			
			return $this->getNavigationId().'_'.$this->getLanguageId();
		}
		
		/**
		 * @return NavigationData
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
		 * @return Navigation
		 */
		public function getNavigation()
		{
			return Navigation::da()->getById($this->getNavigationId());
		}
		
		/**
		 * @return NavigationData
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