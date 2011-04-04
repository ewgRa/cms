<?php
	namespace ewgraCms;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class ActionChainController extends \ewgraFramework\ActionChainController
	{
		private $settingsAction = null;

		/**
		 * @return ActionChainController
		 */
		public function importSettings(array $settings = null)
		{
			$this->settingsAction =
				isset($settings['action'])
					? $settings['action']
					: null;

			return $this;
		}

		protected function defineActionFromRequest(\ewgraFramework\HttpRequest $request)
		{
			$action = parent::defineActionFromRequest($request);

			if (!$action)
				$action = $this->settingsAction;

			return $action;
		}
	}
?>