<?php
	/* $Id$ */

	final class PageNotFoundModule extends CmsModule
	{
		public function getModel()
		{
			$this->getPage()->
				getHeader()->
				// FIXME: user getRequest()->getServerVar
				add($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
			
			return null;
		}
	}
?>