<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Config extends \ewgraFramework\Singleton
	{
		private $options = null;

		/**
		 * @return Config
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		/**
		 * @return Config
		 */
		public function setOption($alias, $value)
		{
			$this->options[$alias] = $value;
			return $this;
		}

		public function getOption($alias)
		{
			$result = null;

			if (isset($this->options[$alias]))
				$result = $this->options[$alias];

			return $result;
		}
	}
?>