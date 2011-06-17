<?php
	/**
	 * Файл, компилирующий статические файлы в спайки и оптимизирующих их ( убирает комментарии, лишние переводы строк и т.п. )
	 */

	class CompileJoinedFiles
	{
		function Compile( $FileListFile, $Extension, $DistantionFile )
		{
			$FileList = unserialize( file_get_contents( $FileListFile ) );

			$FileData = self::CompileFileData( $FileList );
			$FileData = self::ZipFileData( $FileData, $Extension );


			file_put_contents( $DistantionFile, $FileData );

			switch( $Extension )
			{
				case 'css':
					header( 'Content-type: text/css;charset=utf-8' );
				break;
				case 'js':
					header( 'Content-type: application/x-javascript;charset=utf-8' );
				break;
			}

			echo $FileData;
		}

		
		function CompileFileData( $FileList )
		{
			$FileData = '';
			foreach ( $FileList as $File )
			{
				$filename = null;
				$type = array_shift( explode( '/', substr( $File, 1 ) ) );
				switch( $type )
				{
					case 'lib':
						$File = substr( $File, 4 );
						$filename = LIB_DIR . $File;	
					break;
					case 'cms':
						$File = substr( $File, 4 );
						$filename = CMS_DIR . '/view' . $File;	
					break;
					case 'site':
						$File = substr( $File, 5 );
						$filename = SITE_DIR . '/view' . $File;	
					break;
				}
				if( !$filename || !file_exists( $filename ) )
				{
					header( 'HTTP/1.1 404 Not Found' );
					die( 'No file ' . $filename );
				}
				$Data = file_get_contents( $filename );
				$FileData .= PHP_EOL . $Data;
			}
			return $FileData;
		}

		function ZipFileData( $FileData, $Extension )
		{
			switch( $Extension )
			{
				case 'css':
					$FileData = str_replace( "\t", '', $FileData );
					$FileData = str_replace( "\n", '', $FileData );
					$FileData = str_replace( "\r", '', $FileData );
					$FileData = str_replace( '; ', ';', $FileData );
					$FileData = str_replace( '}', '}' . PHP_EOL, $FileData );
				break;
				case 'js':
					$FileData = preg_replace( '@^([^\'"]*?)//.*?\n@m', "$1\n", $FileData );
					$FileData = preg_replace( '@//[^\'"]*?\n@m', "$1\n", $FileData );
					$comment_pattern = '/\*[^\@](.*?)\*/';
					$FileData = preg_replace( '@' . $comment_pattern . "|\n|\t|\r|  |/\*\*/@s", '', $FileData );
					$FileData = preg_replace( '@}\s*(\w)@i', "};\n$1", $FileData );
					$FileData = preg_replace( '@};\s(else|catch)@', "} $1", $FileData );
					$FileData = preg_replace( '@\n@', '', $FileData );
				break;
			}
			return $FileData;
		}
	}
?>