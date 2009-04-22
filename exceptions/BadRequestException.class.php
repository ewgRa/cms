<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class BadRequestException extends DefaultException
	{
		/**
		 * @return BadRequestException
		 */
		public static function create($code = null, $message = null)
		{
			return new self($message, $code);
		}
	}
?>