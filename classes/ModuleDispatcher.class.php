<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ModuleDispatcher extends CmsModule
	{
		private $modules = array();
		
		/**
		 * @return ModuleDispatchers
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return ModuleDispatcher
		 */
		public function addModule(CmsModule $module, PageModule $pageModule)
		{
			$this->modules[] = array(
				'instance'		=> $module,
				'pageModule'	=> $pageModule
			);
			
			return $this;
		}
		
		public function getModules()
		{
			return $this->modules;
		}
		
		public function getModulesByFilterFunction($filterFunction)
		{
			$result = array();
			
			foreach ($this->getModules() as $module) {
				if (call_user_func($filterFunction, $module['instance']))
					$result[] = $module['instance'];
			}
			
			return $result;
		}
		
		/**
		 * @return ModuleDispatcher
		 */
		public function loadModules(array $pageModules)
		{
			$this->modules = array();
			
			foreach ($pageModules as $index => $pageModule) {
				$module = $pageModule->getModule();
				
				$moduleName = $module->getName();
				
				$moduleInstance = new $moduleName;

				$settings = $module->getSettings();
				
				if ($pageModule->getSettings()) {
					if ($settings) {
						$settings = array_merge(
							$settings,
							$pageModule->getSettings()
						);
					} else {
						$settings = $pageModule->getSettings();
					}
				}

				$moduleInstance->
					setRequest($this->getRequest())->
					setDispatcher($this)->
					importSettings($settings);


				if ($pageModule->getViewFileId()) {
					$moduleInstance->setView(
						ViewFactory::createByViewFile($pageModule->getViewFile())
					);
				}
				
				$this->addModule($moduleInstance, $pageModule);
			}
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$result = Model::create();
			
			foreach ($this->getModules() as $module) {
				$result->append(
					array(
						'data' =>
							$module['instance']->getRenderedModel(),
						'section' => $module['pageModule']->getSection(),
						'position' => $module['pageModule']->getPosition()
					)
				);
			}
			
			return $result;
		}
	}
?>