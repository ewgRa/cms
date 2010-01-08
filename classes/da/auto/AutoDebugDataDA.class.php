<?php
	/* $Id */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoDebugDataDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'DebugData';
		
		/**
		 * @return DebugData
		 */
		protected function build(array $array)
		{
			return
				DebugData::create()->
					setId($array['id'])->
					setSession($array['session'])->
					setData($array['data'] ? unserialize($array['data']) : null)->
					setDate($array['date']);
		}
	}
?>