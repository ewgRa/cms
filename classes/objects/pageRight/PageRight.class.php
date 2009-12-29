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
		
		/**
		 * @var Right
		 */
		private $right = null;

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
		 * @return PageRight
		 */
		public function setRightId($rightId)
		{
			$this->rightId 	= $rightId;
			$this->right 	= null;
			return $this;
		}
		
		public function getRightId()
		{
			return $this->rightId;
		}

		/**
		 * @return PageRight
		 */
		public function setRight(Right $right)
		{
			$this->right 	= $right;
			$this->rightId 	= $right->getId();
			return $this;
		}
		
		/**
		 * @return Right
		 */
		public function getRight()
		{
			if (!$this->right && $this->getRightId()) {
				$this->setRight(
					Right::da()->getById($this->getRightId())
				);
			}
			
			return $this->right;
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
	}
?>