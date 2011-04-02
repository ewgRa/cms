<?php
	namespace ewgraCms;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoFileSource
	{
		private $id = null;

		private $alias = null;

		/**
		 * @return FileSourceDA
		 */
		public static function da()
		{
			return FileSourceDA::me();
		}

		/**
		 * @return AutoFileSource
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
		 * @return AutoFileSource
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