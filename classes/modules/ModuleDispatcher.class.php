<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ModuleDispatcher extends CmsModule
	{
		private $modules 	 = array();
		private $pageModules = array();
		
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
			$this->modules[] = $module;
			$this->pageModules[] = $pageModule;
			
			return $this;
		}
		
		public function getModules()
		{
			return $this->modules;
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
						$pageModule->getViewFile()->createView()
					);
				} else {
					$moduleInstance->setView(NullTransformView::create());
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
			
			foreach ($this->getModules() as $keyModule => $module) {
				$pageModule = $this->pageModules[$keyModule];
				
				$result->append(
					array(
						'data' => $module->getRenderedModel(),
						'section' => $pageModule->getSection(),
						'position' => $pageModule->getPosition()
					)
				);
			}
			
			return $result;
		}
	}
?>