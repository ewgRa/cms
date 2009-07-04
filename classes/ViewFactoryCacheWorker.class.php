<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class ViewFactoryCacheWorker extends CacheWorker
	{
		/**
		 * @return ViewFactoryCacheWorker
		 */
		public static function create()
		{
			return new self;
		}

		protected function getAlias()
		{
			return __CLASS__;
		}
		
		protected function getKey()
		{
			$projectConfig = Config::me()->getOption('project');
				
			return array(
				isset($projectConfig['charset'])
					? $projectConfig['charset']
					: null
			);
		}
	}
?>