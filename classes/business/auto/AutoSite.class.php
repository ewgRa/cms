<?php
	/* $Id$ */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoSite
	{
		private $id = null;
		
		private $alias = null;
		
		/**
		 * @return SiteDA
		 */
		public static function da()
		{
			return SiteDA::me();
		}
		
		/**
		 * @return AutoSite
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
		 * @return AutoSite
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