<?php
	namespace ewgraCms;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoController
	{
		private $id = null;

		private $name = null;

		/**
		 * @var array
		 */
		private $settings = null;

		/**
		 * @return ControllerDA
		 */
		public static function da()
		{
			return ControllerDA::me();
		}

		/**
		 * @return ControllerProto
		 */
		public static function proto()
		{
			return ControllerProto::me();
		}

		/**
		 * @return AutoController
		 */
		public function setId($id)
		{
			$this->id = $id;

			return $this;
		}

		public function getId()
		{
			\ewgraFramework\Assert::isNotNull($this->id);
			return $this->id;
		}

		/**
		 * @return AutoController
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
		 * @return AutoController
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