<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoPageDataDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'PageData';
		
		/**
		 * @return PageData
		 */
		protected function build(array $array)
		{
			return
				PageData::create()->
					setPageId($array['page_id'])->
					setLanguageId($array['language_id'])->
					setTitle($array['title'])->
					setDescription($array['description'])->
					setKeywords($array['keywords']);
		}
	}
?>