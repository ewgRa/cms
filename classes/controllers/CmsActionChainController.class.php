<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class CmsActionChainController extends ActionChainController
	{
		/**
		 * @return CmsActionChainController
		 */
		public function importSettings(array $settings = null)
		{
			$this->setRequestAction(
				isset($settings['action'])
					? $settings['action']
					: null
			);
			
			return $this;
		}
	}
?>