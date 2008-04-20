<?php
	class AdminPagesRedirectController extends EngineModuleRedirectController
	{
		function RedirectProvider()
		{
			AdminPages::GetData( $this->Settings, null, null );
			return '/admin';

		}
	}
?>