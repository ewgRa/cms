<?php
	class AdminFilesController extends EngineModuleController
	{
		function DataProvider()
		{
			$Localizer = Registry::Get( 'Localizer' );

			$Result = AdminFiles::GetData( $this->Settings, $Localizer->GetLanguageID(), $this->ControlParams );

			return $Result;
		}
	}

?>