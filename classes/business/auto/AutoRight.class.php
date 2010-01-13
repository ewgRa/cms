<?php
	/* $Id$ */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoRight extends IdentifierObject
	{
		private $id = null;
		
		private $alias = null;
		
		private $name = null;
		
		/**
		 * @var boolean
		 */
		private $role = null;
		
		/**
		 * @return RightDA
		 */
		public static function da()
		{
			return RightDA::me();
		}
		
		/**
		 * @return AutoRight
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
		 * @return AutoRight
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
		
		/**
		 * @return AutoRight
		 */
		public function setName($name)
		{
			$this->name = $name;
			return $this;
		}
		
		public function getName()
		{
			return $this->name;
		}
		
		/**
		 * @return AutoRight
		 */
		public function setRole($role = true)
		{
			$this->role = ($role === true);
			return $this;
		}
		
		/**
		 * @return boolean
		 */
		public function getRole()
		{
			return $this->role;
		}
		
		public function isRole()
		{
			return ($this->getRole() === true);
		}
	}
?>