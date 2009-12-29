<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * // FIXME: tested?
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
		public function addModule(CmsModule $module, $section, $position)
		{
			$this->modules[] = array(
				'instance'	=> $module,
				'section'	=> $section,
				'position'	=> $position
			);
			
			return $this;
		}
		
		public function getModules()
		{
			return $this->modules;
		}
		
		public function getModulesByApply($applyFunction)
		{
			$result = array();
			
			foreach($this->getModules() as $module)
			{
				if(call_user_func($applyFunction, $module['instance']))
					$result[] = $module['instance'];
			}
			
			return $result;
		}
		
		/**
		 * @return ModuleDispatcher
		 */
		public function loadModules()
		{
			$page = $this->getRequest()->getAttachedVar(AttachedAliases::PAGE);
			$pageModules = $page->getModules();
			$this->modules = array();
			
			foreach($pageModules as $index => $pageModule)
			{
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


				if($pageModule->getViewFileId())
				{
					$view = ViewFactory::createByFileId($pageModule->getViewFileId());
					$moduleInstance->setView($view);
				}
				
				$this->addModule(
					$moduleInstance,
					$pageModule->getSection(),
					$pageModule->getPosition()
				);
			}
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$result = Model::create();
			
			foreach($this->getModules() as $module)
			{
				$result->append(
					array(
						'data' =>
							$module['instance']->getRenderedModel(),
						'section' => $module['section'],
						'position' => $module['position']
					)
				);
			}
			
			return $result;
		}
	}
?>