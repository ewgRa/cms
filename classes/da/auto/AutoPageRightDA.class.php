<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoPageRightDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'PageRight';
		
		/**
		 * @return PageRight
		 */
		protected function build(array $array)
		{
			return
				PageRight::create()->
					setPageId($array['page_id'])->
					setRightId($array['right_id'])->
					setRedirectPageId($array['redirect_page_id']);
		}
	}
?>