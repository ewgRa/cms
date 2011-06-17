<?php
	class AdminNavigationController extends EngineModuleController
	{
		function DataProvider()
		{
			$Localizer = Registry::Get( 'Localizer' );
			$Result = AdminNavigation::GetData( $this->Settings, $Localizer->GetLanguageID(), $this->ControlParams );
			
			return $Result;
		}
	}

?>