<?php
	/* $Id$ */

	class CmsPageException extends PageException
	{
		public function __toString()
		{
			if($this->getCode() == self::PAGE_NOT_FOUND)
			{
				EngineDispatcher::me()->redirectToUri('/not-found.html');
				die();
			}
			
			return parent::__toString();
		}
	}
?>