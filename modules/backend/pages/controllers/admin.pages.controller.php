<?php
	class AdminPagesController extends EngineModuleController
	{
		function DataProvider()
		{
			$Localizer = Registry::Get( 'Localizer' );

			$Result = AdminPages::GetData( $this->Settings, $Localizer->GetLanguageID(), $this->ControlParams );

			return $Result;
		}
	}

?>