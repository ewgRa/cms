<?php
	/* $Id */
	
	/*
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class AutoUserDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'User';
		
		/**
		 * @return User
		 */
		protected function build(array $array)
		{
			return
				User::create()->
					setId($array['id'])->
					setLogin($array['login'])->
					setPassword($array['password']);
		}
	}
?>