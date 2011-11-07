<?php
	namespace ewgraCms;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoLanguage
	{
		private $id = null;

		private $abbr = null;

		/**
		 * @return LanguageDA
		 */
		public static function da()
		{
			return LanguageDA::me();
		}

		/**
		 * @return LanguageProto
		 */
		public static function proto()
		{
			return LanguageProto::me();
		}

		/**
		 * @return AutoLanguage
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
		 * @return AutoLanguage
		 */
		public function setAbbr($abbr)
		{
			$this->abbr = $abbr;

			return $this;
		}

		public function getAbbr()
		{
			return $this->abbr;
		}
	}
?>