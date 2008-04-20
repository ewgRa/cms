<?php
	class NavigationController extends EngineModuleController
	{
		function Initialize()
		{
			parent::Initialize();			
			$this->ControlParams['Alias'] = $this->Settings['alias'];
		}
		
		function DataProvider()
		{
			$Localizer = Registry::Get( 'Localizer' );
			$Result = Navigation::GetData( $this->Settings, $Localizer->GetLanguageID() );
			return $Result;
		}
		
		
		function SetCacheParams()
		{
			$Localizer = Registry::Get( 'Localizer' );
			$this->CacheParams = array(
				'DataProvider' => array(
					array(
						'prefix' => 'modules/navigation',
						'key' => array( $Localizer->GetLanguageID(), $this->Settings ),
						'life_time' => 24 * 60 * 60
					)
				)
			);
		}		
	}
?>	
