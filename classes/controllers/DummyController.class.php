<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DummyController extends \ewgraFramework\ChainController
	{
		/**
		 * @return DummyController
		 */
		public static function create(\ewgraFramework\ChainController $controller = null)
		{
			return new self($controller);
		}
	}
?>