<?php
	/* $Id */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoLanguageDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'Language';
		
		/**
		 * @return Language
		 */
		protected function build(array $array)
		{
			return
				Language::create()->
					setId($array['id'])->
					setAbbr($array['abbr']);
		}
	}
?>