<?php
	/* $Id$ */

	class PageNotFoundModule extends Module
	{
		public function getModel()
		{
			$this->getRequest()->getAttachedVar(AttachedAliases::PAGE)->
				getHeader()->
				add($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
			
			return null;
		}
	}
?>