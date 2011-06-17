<?php
	class AdminContentController extends EngineModuleController
	{
		function DataProvider()
		{
			$Localizer = Registry::Get( 'Localizer' );
			$Result = AdminContent::GetData( $this->Settings, $Localizer->GetLanguageID(), $this->ControlParams );
			
			return $Result;
		}
	}

?>