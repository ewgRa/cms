<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class ModuleCacheWorker extends CacheWorker
	{
		private $module  = null;

		/**
		 * @return Module
		 */
		public function setModule(CmsModule $module)
		{
			$this->module = $module;
			return $this;
		}
		
		/**
		 * @return CmsModule
		 */
		public function getModule()
		{
			return $this->module;
		}
		
		/**
		 * @return Page
		 */
		protected function getPage()
		{
			return $this->getModule()->getPage();
		}

		/**
		 * @return HttpUrl
		 */
		protected function getBaseUrl()
		{
			return $this->getModule()->getBaseUrl();
		}
		
		/**
		 * @return Localizer
		 */
		protected function getLocalizer()
		{
			return $this->getModule()->getLocalizer();
		}

		/**
		 * @return Language
		 */
		protected function getRequestLanguage()
		{
			return $this->getLocalizer()->getRequestLanguage();
		}
	}
?>