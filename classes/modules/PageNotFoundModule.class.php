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
			$this->getPageHeader()->
				add(
					$this->getRequest()->getServerVar('SERVER_PROTOCOL')
					.' 404 Not Found'
				);
			
			return null;
		}
	}
?>