<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageNotFoundException extends \ewgraFramework\DefaultException
	{
		/**
		 * @return PageNotFoundException
		 */
		public static function create($message = 'Page not found!', $code = 1)
		{
			return new self($message, $code);
		}
	}
?>