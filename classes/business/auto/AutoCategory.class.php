<?php
	/* $Id$ */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoCategory
	{
		private $id = null;
		
		private $alias = null;
		
		/**
		 * @return CategoryDA
		 */
		public static function da()
		{
			return CategoryDA::me();
		}
		
		/**
		 * @return AutoCategory
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
		 * @return AutoCategory
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