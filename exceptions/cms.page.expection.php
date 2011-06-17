<?php
	class CMSPageException extends PageException
	{
		public function __construct( $Message, $Code )
		{
			parent::__construct( $Message, $Code );	

			switch( $Code )
			{
				case self::NO_RIGHTS:
					$message = unserialize( $this->message );
					$EngineDispatcher = Registry::Get( 'EngineDispatcher' );
					$EngineDispatcher->ForwardToURI( $message['redirect_page'] );
					die;
				break;
				case self::PAGE_NOT_DEFINED:
					$EngineDispatcher = Registry::Get( 'EngineDispatcher' );
					$EngineDispatcher->ForwardToURI( '/page-not-found.html' );
					die;
				break;
			}
		}
	}
?>