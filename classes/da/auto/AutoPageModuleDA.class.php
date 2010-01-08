<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoPageModuleDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'PageModule';
		
		/**
		 * @return PageModule
		 */
		protected function build(array $array)
		{
			return
				PageModule::create()->
					setPageId($array['page_id'])->
					setModuleId($array['module_id'])->
					setSection($array['section'])->
					setPosition($array['position'])->
					setPriority($array['priority'])->
					setSettings($array['settings'] ? unserialize($array['settings']) : null)->
					setViewFileId($array['view_file_id']);
		}
	}
?>