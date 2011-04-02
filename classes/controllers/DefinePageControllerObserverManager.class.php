<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class DefinePageControllerObserverManager extends \ewgraFramework\Observable
	{
		const PAGE_DEFINED_EVENT = 1;

		private $controller = null;

		/**
		 * @return DefinePageControllerObserverManager
		 */
		public static function create(DefinePageController $controller)
		{
			return new self($controller);
		}

		/**
		 * @return DefinePageControllerObserverManager
		 */
		public function __construct(DefinePageController $controller)
		{
			$this->controller = $controller;
		}

		/**
		 * @return DefinePageControllerObserverManager
		 */
		public function notifyPageDefined(Page $page)
		{
			$this->notifyObservers(
				self::PAGE_DEFINED_EVENT,
				\ewgraFramework\Model::create()->
				set('page', $page)
			);

			return $this;
		}
	}
?>