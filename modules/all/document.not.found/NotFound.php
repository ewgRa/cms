<?php
	/**
	 * Модуль, для правильного оформления ошибки 404 (документ не найден на сервере)
	 */
	class NotFound404
	{
		function ToEngine()
		{
			header( 'HTTP/1.1 404 Not Found' );
		}
	}
?>