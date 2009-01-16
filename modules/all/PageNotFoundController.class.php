<?php
	/* $Id$ */

	class PageNotFoundController extends Controller
	{
		public function getModel(HttpRequest $request)
		{
			Page::me()->getHeader()->add($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
			return null;
		}
	}
?>