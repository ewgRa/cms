<?php
	class AuthorizeController extends EngineModuleController
	{
		function DataProvider()
		{
			$Localizer = Registry::Get( 'Localizer' );

			$Result = Authorize::GetData( $this->Settings, $Localizer->GetLanguageID(), $this->ControlParams );

			return $Result;
		}
	}

?>