<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoViewFileDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'ViewFile';
		
		/**
		 * @return ViewFile
		 */
		protected function build(array $array)
		{
			return
				ViewFile::create()->
					setId($array['id'])->
					setContentType(ContentType::create($array['content_type']))->
					setPath(Config::me()->replaceVariables($array['path']))->
					setJoinable($array['joinable'] == 1);
		}
	}
?>