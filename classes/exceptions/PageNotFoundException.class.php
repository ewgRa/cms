<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageNotFoundException extends DefaultException
	{
		private $url = null;
		
		/**
		 * @return PageNotFoundException
		 */
		public static function create($message = 'Page not found!', $code = 1)
		{
			return new self($message, $code);
		}
		
		/**
		 * @return PageNotFoundException
		 */
		public function setUrl($url)
		{
			$this->url = $url;
			return $this;
		}
		
		public function __toString()
		{
			$result = array(
				__CLASS__.": [{$this->code}]:",
				$this->message,
				"Url: {$this->url}"
			);
			
			return join(PHP_EOL.PHP_EOL, $result).PHP_EOL.PHP_EOL;
		}
	}
?>