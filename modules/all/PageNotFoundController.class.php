<?php
	/* $Id$ */

	class PageNotFoundController extends Controller
	{
		public function getModel()
		{
			$this->getRequest()->getAttached(AttachedAliases::PAGE)->
				getHeader()->
				add($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
			
			return null;
		}
	}
?>