<?php
	/* $Id$ */
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Right
	{
		private $id = null;

		private $alias = null;
		
		private $name = null;
		
		private $role = null;
		
		/**
		 * @return Right
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return RightDA
		 */
		public static function da()
		{
			return RightDA::me();
		}
		
		/**
		 * @return Right
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
		 * @return Right
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
		 * @return Right
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
		 * @return Right
		 */
		public function setRole($role = true)
		{
			$this->role = ($role === true);
			return $this;
		}
		
		public function isRole()
		{
			return ($this->role === true);
		}
	}
?>