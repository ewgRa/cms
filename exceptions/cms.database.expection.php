<?php
	class CMSDatabaseException extends DatabaseException
	{
		public function __construct( $Message, $Code )
		{
			parent::__construct( $Message, $Code );	
			switch( $Code )
			{
				case self::CONNECT:
				case self::SELECT_DATABASE:
				case self::SQL_QUERY_ERROR:
					$Project = Config::getOption( 'Project' );
					$ProjectName = $_SERVER['HTTP_HOST'];
					if( array_key_exists( 'Name', $Project ) )
					{
						$ProjectName = $Project['Name'];
					}
					
					$DB = Registry::Get( 'DB' );
					
					$Subject = $ProjectName . ' database error report';
					$Body = $this->__toString();
					$Body .= "\nTime: " . date( 'Y-m-d h:i:s' );
					$Body .= "\nURL: " . $_SERVER['REQUEST_URI'];
					$Body .= "\nHost: " . $_SERVER['HTTP_HOST'];
					$Body .= "\nWorking Area: " . Config::$BuildType;
							
					foreach( $DB->ErrorReportEmails as $email )
					{
						mail( $email, $Subject, $Body );
					}
				break;
			}
		}
	}
?>