<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Module
	{
		private $id			= null;
		
		private $name		= null;
		
		private $settings	= null;
		
		/**
		 * @return Module
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return ModuleDA
		 */
		public static function da()
		{
			return ModuleDA::me();
		}

		/**
		 * @return Module
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
		 * @return Module
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
		 * @return Module
		 */
		public function setSettings(array $settings = null)
		{
			$this->settings = $settings;
			return $this;
		}

		public function getSettings()
		{
			return $this->settings;
		}
	}
?>