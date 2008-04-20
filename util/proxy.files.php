<?php
	/**
	 * Файл проксирования файлов из различных Хранилищ
	 *
	 */
	class ProxyFiles
	{
		function Proxy( $RequestFileName, $Storage )
		{
			$RealFileName = self::GetRealFilePath( $RequestFileName, $Storage );
			
			if( !empty( $RealFileName ) )
			{
				$Extension = array_pop( explode( '.', $RequestFileName ) );
				switch( $Extension )
				{
					case 'php':
						chdir( dirname( $RealFileName ) );
						include_once( $RealFileName );
					break;
					default:
						Cache::CreatePreDirs( CACHE_DIR . '/proxy_files/' . $Storage . $RequestFileName );
						copy( $RealFileName, CACHE_DIR . '/proxy_files/' . $Storage . $RequestFileName );
						self::OutputHeaders( $Extension );
						echo file_get_contents( $RealFileName );
					break;
				}
			}
			else
			{
				throw new ExceptionMap::$Classes['PageExceptionClass']( 'Page "' . $_SERVER['REQUEST_URI'] . '" is not defined', PageException::PAGE_NOT_DEFINED );
			}
		}
		
		
		function GetRealFilePath( $RequestFileName, $Storage )
		{
			$RealFileName = $additional_cache_dir = '';
			switch( $Storage )
			{
				case 'lib':
					$RealFileName = LIB_DIR . $RequestFileName;
				break;
				case 'cms':
					$RealFileName = CMS_DIR . '/view' . $RequestFileName;
				break;
			}
		
			$RealFileName = realpath( $RealFileName );
			
			return $RealFileName;
		}
		

		function OutputHeaders( $Extension )
		{
			switch( $Extension )
			{
				case 'css':
					header( 'Content-type: text/css;charset=utf-8' );
				break;
				case 'js':
					header( 'Content-type: application/x-javascript;charset=utf-8' );
				break;
			}			
		}
	}
?>