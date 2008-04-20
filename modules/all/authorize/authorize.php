<?php
	class Authorize
	{
		/**
		 * Функция возвращающая данные
		 * @param array $Settings - настройки модуля
		 * @param int $Language - текущий язык
		 * @param array $Params - параметры от контроллера
		 * @return array
		 */
		function GetData( $Settings, $Language, $Params )
		{
			$DB = Registry::Get( 'DB' );

			$Result = array();
			switch( $Settings['mode'] )
			{
				case 'login_form':
				break;
				case 'login':
					$User = Registry::Get( 'User' );
					if( $User->Login( $_POST['login'], $_POST['password'] ) ) $Result = true;											
					else $Result = null;
				break;
			}
			return $Result;
		}
	}
?>