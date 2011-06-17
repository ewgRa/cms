<?php
	class AuthorizeRedirectController extends EngineModuleRedirectController
	{
		function RedirectProvider()
		{
			Authorize::GetData( $this->Settings, null, null );
			return $_SERVER['HTTP_REFERER'];
		}
	}
?>