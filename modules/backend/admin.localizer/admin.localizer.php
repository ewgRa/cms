<?php
	/**
	 * Класс, реализующий оформление заказа
	 */
	class AdminLocalizer
	{
		function GetData( $Settings, $Params )
		{
			$Result = array();
			$DB = Registry::Get( 'DB' );
			switch( $Settings['mode'] )
			{
				case 'get_language_list':
					$dbq = "SELECT * FROM " . $DB->TABLES['Languages'];
					$dbr = $DB->Query( $dbq );
					$Result = $DB->ResourceToArray( $dbr );
				break;
			}
			return $Result;
		}
	}
?>