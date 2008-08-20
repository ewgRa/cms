<?php
	/* $Id$ */

	class PageHeadController extends Controller
	{
		public function getModel()
		{
			return array(
				'title' => Page::me()->getTitle(),
				'description' => Page::me()->getDescription(),
				'keywords' => Page::me()->getKeywords()
			);
		}
	}
?>