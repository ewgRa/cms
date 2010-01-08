<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageRight
	{
		private $pageId = null;
		
		private $rightId = null;
		
		private $redirectPageId = null;
				
		/**
		 * @return PageRight
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return PageRightDA
		 */
		public static function da()
		{
			return PageRightDA::me();
		}

		public function getId()
		{
			Assert::isNotNull($this->getPageId());
			Assert::isNotNull($this->getRightId());

			return $this->getPageId().'_'.$this->getRightId();
		}
		
		/**
		 * @return PageRight
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
		 * @return PageRight
		 */
		public function setRightId($rightId)
		{
			$this->rightId 	= $rightId;
			return $this;
		}
		
		public function getRightId()
		{
			return $this->rightId;
		}

		/**
		 * @return Right
		 */
		public function getRight()
		{
			return Right::da()->getById($this->getRightId());
		}

		/**
		 * @return PageRight
		 */
		public function setRedirectPageId($pageId)
		{
			$this->redirectPageId = $pageId;
			return $this;
		}
		
		public function getRedirectPageId()
		{
			return $this->redirectPageId;
		}

		/**
		 * @return Page
		 */
		public function getRedirectPage()
		{
			return Page::da()->getById($this->getRedirectPageId());
		}
	}
?>