<?php
	/* $Id$ */

	class PageNotFoundController extends Controller
	{
		public function getModel()
		{
			Page::me()->getHeader()->add($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
			return null;
		}
	}
?>