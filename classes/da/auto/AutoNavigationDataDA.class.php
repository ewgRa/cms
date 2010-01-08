<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoNavigationDataDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'NavigationData';
		
		/**
		 * @return NavigationData
		 */
		protected function build(array $array)
		{
			return
				NavigationData::create()->
					setNavigationId($array['navigation_id'])->
					setLanguageId($array['language_id'])->
					setText($array['text']);
		}
	}
?>