<?php
	class ContentController extends EngineModuleController
	{
		function DataProvider()
		{
			$Localizer = Registry::Get( 'Localizer' );
			$Result = Content::GetData( $this->Settings, $Localizer->GetLanguageID() );
			return $Result;
		}

		
		function SetCacheParams()
		{
			$Localizer = Registry::Get( 'Localizer' );

			$this->CacheParams = array(
				'DataProvider' => array(
					array(
						'prefix' => 'modules/content',
						'key' => array( $Localizer->GetLanguageID(), $this->Settings ),
						'life_time' => 24 * 60 * 60
					)
				)
			);
		}	
	}