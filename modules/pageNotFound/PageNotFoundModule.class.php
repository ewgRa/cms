<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageNotFoundModule extends CmsModule
	{
		public function getModel()
		{
			$this->getPage()->
				getHeader()->
				add($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
			
			return null;
		}
	}
?>