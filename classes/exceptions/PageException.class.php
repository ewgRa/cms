<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageException extends DefaultException
	{
		const PAGE_NOT_FOUND		= 1001;
		const NO_RIGHTS_TO_ACCESS	= 1002;
		
		private $url		= null;
		private $pageRights	= null;
		
		/**
		 * @return PageException
		 */
		public static function create($message = null, $code = null)
		{
			return new self($message, $code);
		}
		
		/**
		 * @return PageException
		 */
		public static function pageNotFound($message = null)
		{
			return self::create($message, self::PAGE_NOT_FOUND);
		}
		
		/**
		 * @return PageException
		 */
		public static function noRightsToAccess($message = null)
		{
			return self::create($message, self::NO_RIGHTS_TO_ACCESS);
		}
		
		/**
		 * @return PageException
		 */
		public function setUrl($url)
		{
			$this->url = $url;
			return $this;
		}
		
		/**
		 * @return PageException
		 */
		public function setPageRights($rights)
		{
			$this->pageRights = $rights;
			return $this;
		}
		
		public function getPageRights()
		{
			return $this->pageRights;
		}
		
		public function __toString()
		{
			$resultString = array(parent::__toString());
			
			switch ($this->code) {
				case self::PAGE_NOT_FOUND:

					if (!$this->message)
						$this->setMessage('Page not found!');
					
					$resultString = array(
						__CLASS__ . ": [{$this->code}]:",
						$this->message,
						"Url: {$this->url}"
					);

					break;

				case self::NO_RIGHTS_TO_ACCESS:

					if (!$this->message)
						$this->setMessage('No rights for access to page');
					
					$resultString = array(
						__CLASS__ . ": [{$this->code}]:",
						$this->message,
						"Page rights: " . serialize($this->pageRights)
					);
					
					break;
			}
			
			$resultString[] = '';
			
			return join(PHP_EOL . PHP_EOL, $resultString);
		}
	}
?>