<?php
	class AdminLocalizerController extends EngineModuleController
	{
		function DataProvider()
		{
			$Result = AdminLocalizer::GetData( $this->Settings, $this->ControlParams );
			
			return $Result;
		}
	}

?>