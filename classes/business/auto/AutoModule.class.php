<?php
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoModule
	{
		private $id = null;
		
		private $name = null;
		
		/**
		 * @var array
		 */
		private $settings = null;
		
		/**
		 * @return ModuleDA
		 */
		public static function da()
		{
			return ModuleDA::me();
		}
		
		/**
		 * @return AutoModule
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
		 * @return AutoModule
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
		 * @return AutoModule
		 */
		public function setSettings(array $settings = null)
		{
			$this->settings = $settings;
			return $this;
		}
		
		/**
		 * @return array
		 */
		public function getSettings()
		{
			return $this->settings;
		}
	}
?>