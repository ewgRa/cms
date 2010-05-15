<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageAccessDeniedException extends DefaultException
	{
		/**
		 * @return PageAccessDeniedException
		 */
		public static function create(
			$message = 'No rights for access to page',
			$code = 1
		) {
			return new self($message, $code);
		}
	}
?>