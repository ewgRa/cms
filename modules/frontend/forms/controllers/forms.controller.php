<?php
	class FormsController extends EngineModuleController
	{
		function DataProvider()
		{
			$Localizer = Registry::Get( 'Localizer' );
			$Result = Forms::GetData( $this->Settings, $this->ControlParams, $Localizer->GetLanguageID() );
			return $Result;
		}
	}