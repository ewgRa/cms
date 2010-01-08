<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoContentDataDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'ContentData';
		
		/**
		 * @return ContentData
		 */
		protected function build(array $array)
		{
			return
				ContentData::create()->
					setContentId($array['content_id'])->
					setLanguageId($array['language_id'])->
					setText($array['text']);
		}
	}
?>