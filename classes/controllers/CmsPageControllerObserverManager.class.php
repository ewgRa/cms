<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class CmsPageControllerObserverManager extends Observable
	{
		const PAGE_DEFINED_EVENT = 1;
		
		private $controller = null;
		
		/**
		 * @return CmsPageControllerObserverManager
		 */
		public static function create(CmsPageController $controller)
		{
			return new self($controller);
		}
		
		/**
		 * @return CmsPageControllerObserverManager
		 */
		public function __construct(CmsPageController $controller)
		{
			$this->controller = $controller;
		}
		
		/**
		 * @return CmsPageControllerObserverManager
		 */
		public function notifyPageDefined(Page $page)
		{
			$this->notifyObservers(
				self::PAGE_DEFINED_EVENT,
				Model::create()->
				set('page', $page)
			);
			
			return $this;
		}
	}
?>