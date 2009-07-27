<?php
	/* $Id$ */

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * // FIXME: tested?
	*/
	final class ModuleDispatcher extends Module
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
		public function addModule(Module $module, $section, $position)
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
			
			foreach($pageModules as $index => $module)
			{
				$moduleInstance = new $module['name'];

				$moduleInstance->
					setRequest($this->getRequest())->
					setDispatcher($this);
				
				$pageModules[$index]['instance'] = $moduleInstance;
			}

			foreach($pageModules as $module)
			{
				$moduleInstance = $module['instance'];
				
				$module['module_settings'] =
					is_null($module['module_settings'])
						? array()
						: unserialize($module['module_settings']);

				if(!is_null($module['settings']))
				{
					$module['module_settings'] =
						array_merge(
							$module['module_settings'],
							unserialize($module['settings'])
						);
				}
				
				$moduleInstance->importSettings($module['module_settings']);
				
				if($module['view_file_id'])
				{
					$view = ViewFactory::createByFileId($module['view_file_id']);
					$moduleInstance->setView($view);
				}
				
				$this->addModule(
					$moduleInstance,
					$module['section_id'],
					$module['position_in_section']
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