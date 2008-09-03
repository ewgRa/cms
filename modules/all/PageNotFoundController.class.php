<?php
	/* $Id$ */

	class PageNotFoundController extends Controller
	{
		public function getModel()
		{
			// FIXME: magic constant and http version
			Page::me()->getHeader()->add('HTTP/1.1 404 Not Found');
			return null;
		}
	}
?>