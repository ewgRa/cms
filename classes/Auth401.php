<?php
	/**
	 * Класс, реализующий авторизацию через Basic Realm
	 * @example
	 */
	class Auth401
	{
		/**
		 * Пользователь неавторизован, посылаем заголовки, на которые браузер ответит выводом формы ввода логина/пароля
		 * @param string $Realm
		 * @param string $CancelMessage
		 */
		static function UnAuthorized( $Realm, $CancelMessage )
		{
			header( 'WWW-Authenticate: Basic realm="' . $Realm . '"' );
			header( 'HTTP/1.0 401 Unauthorized' );
			echo $CancelMessage;
			exit;
		}

		/**
		 * Проверка введенных данных
		 * @param array $RequireRights
		 * @return boolean
		 */
		static function CheckAuth( $RequireRights = array() )
		{
			if( !array_key_exists( 'PHP_AUTH_USER', $_SERVER ) ) return false;
//			self::ProcessREDIRECT_REMOTE_USER();
			$User = new User();
			if( $User->Login( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] ) == User::SUCCESS_LOGIN )
			{
				$User->GetLogin();
				$User->LoadRights();
				$UserRights = $User->GetRights();
				if( array_values( array_intersect( array_values( $UserRights ), array_values( $RequireRights ) ) ) == array_values( $RequireRights ) ) return true;
			}
			return false;
		}
		

		/**
		 * костыльный Peterhost
		 * @see http://peterhost.ru/instr3_5.shtml {А что-то у меня не работают функции http-авторизации.; А как все-таки заставить работать http-авторизацию при работе с PHP в режиме CGI?}
		 */
		static function ProcessREDIRECT_REMOTE_USER()
		{
			if( !array_key_exists( 'REDIRECT_REMOTE_USER', $_SERVER ) || !$_SERVER['REDIRECT_REMOTE_USER'] ) return;

			$a = base64_decode( substr( $_SERVER["REDIRECT_REMOTE_USER"] , 6 ) ) ;
			if( !( ( strlen( $a ) == 0 ) || ( strcasecmp( $a, ":" ) == 0 ) ) )
			{
				list($name, $password) = explode(':', $a);
				$_SERVER['PHP_AUTH_USER'] = $name;
				$_SERVER['PHP_AUTH_PW'] = $password;
			}
		}
		
	}
?>
