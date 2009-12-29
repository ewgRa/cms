<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Navigation
	{
		private $id			= null;
		
		private $categoryId = null;
		
		/**
		 * @var HttpUrl
		 */
		private $uri 	= null;
		
		/**
		 * @return Navigation
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return NavigationDA
		 */
		public static function da()
		{
			return NavigationDA::me();
		}

		/**
		 * @return Navigation
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}

		public function getId()
		{
			Assert::isNotNull($this->id);
			return $this->id;
		}
		
		/**
		 * @return Navigation
		 */
		public function setCategoryId($categoryId)
		{
			$this->categoryId 	= $categoryId;
			return $this;
		}
		
		public function getCategoryId()
		{
			return $this->categoryId;
		}
		
		/**
		 * @return Category
		 */
		public function getCategory()
		{
			return Category::da()->getById($this->getCategoryId());
		}
		
		/**
		 * @return Navigation
		 */
		public function setUri(HttpUrl $uri)
		{
			$this->uri = $uri;
			return $this;
		}
		
		/**
		 * @return HttpUrl
		 */
		public function getUri()
		{
			return $this->uri;
		}
	}
?>