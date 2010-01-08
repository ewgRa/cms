<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoPageDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Page';
		
		/**
		 * @return Page
		 */
		protected function build(array $array)
		{
			return
				Page::create()->
					setId($array['id'])->
					setPath($array['path'])->
					setPreg($array['preg'] == 1)->
					setLayoutId($array['layout_id'])->
					setStatus(PageStatus::create($array['status']))->
					setModified($array['modified']);
		}
	}
?>