<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoLayoutDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Layout';
		
		/**
		 * @return Layout
		 */
		protected function build(array $array)
		{
			return
				Layout::create()->
					setId($array['id'])->
					setViewFileId($array['view_file_id']);
		}
	}
?>