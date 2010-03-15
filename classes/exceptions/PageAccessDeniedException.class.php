<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageAccessDeniedException extends DefaultException
	{
		private $pageId		= null;
		private $pageRights	= null;
		
		/**
		 * @return PageAccessDeniedException
		 */
		public static function create(
			$message = 'No rights for access to page',
			$code = 1
		) {
			return new self($message, $code);
		}

		/**
		 * @return PageAccessDeniedException
		 */
		public function setPageId($id)
		{
			$this->pageId = $id;
			return $this;
		}
		
		/**
		 * @return PageAccessDeniedException
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
			$result = array(
				__CLASS__.": [{$this->code}]:",
				$this->message,
				"Page id: ".$this->pageId,
				"Page rights: ".serialize($this->pageRights)
			);
			
			return join(PHP_EOL.PHP_EOL, $result).PHP_EOL.PHP_EOL;
		}
	}
?>