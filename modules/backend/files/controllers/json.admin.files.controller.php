<?php
	class JsonAdminFilesController extends EngineModuleJsonController
	{
		function DataProvider()
		{
			$Localizer = Registry::Get( 'Localizer' );

			$Result = AdminFiles::GetData( $this->Settings, $Localizer->GetLanguageID(), $this->ControlParams );

			return array( 'Data' => $Result, 'Prefix' => 'JsonAdminFilesController' );
		}
	}
?>