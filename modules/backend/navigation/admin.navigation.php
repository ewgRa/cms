<?php
	/**
	 * Класс, реализующий backend управления навигацией
	 */
	class AdminNavigation
	{
		function GetData( $Settings, $Language, $Params )
		{
			$Result = array();
			$DB = Registry::Get( 'DB' );
			switch( $Settings['mode'] )
			{
				case 'navigations':
				break;
				default:
				break;
			}
			return $Result;
		}
	}
?>