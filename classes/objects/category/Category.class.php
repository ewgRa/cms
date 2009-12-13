<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Category
	{
		private $id		= null;
		private $alias 	= null;
		
		/**
		 * @return Category
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return CategoryDA
		 */
		public static function da()
		{
			return CategoryDA::me();
		}
		
		/**
		 * @return Category
		 */
		public function setId($id)
		{
			$this->id = $id;
			return $this;
		}

		public function getId()
		{
			return $this->id;
		}
		
		/**
		 * @return Category
		 */
		public function setAlias($alias)
		{
			$this->alias = $alias;
			return $this;
		}

		public function getAlias()
		{
			return $this->alias;
		}
	}
?>