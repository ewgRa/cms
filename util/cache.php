<?php
	class ClearExpireCache
	{
		function Clear( $dirs )
		{
			foreach( $dirs as $dir )
			{
				$sub_dir = ClearCache::DirList( $dir );
				foreach( $sub_dir as $file )
				{
					if( is_file( $dir . '/' . $file ) && filemtime( $dir . '/' . $file ) < time() )
					{
						unlink( $dir . '/' . $file );
					}
					elseif( is_dir( $dir . '/' . $file ) )
					{
						self::Clear( array( $dir . '/' . $file ) );
					}
					else
					{
//						echo $dir . '/' . $file . ' ' . filemtime( $dir . '/' . $file ) . ' ' . time() . '<br/>';
					}
				}
				@rmdir( $dir );
			}
		}
	}


	class ClearCache
	{
		function DirList( $Dir )
		{
			$Files = array();
			if( $handle = opendir( $Dir ) )
			{
				while( false !== ( $file = readdir( $handle ) ) )
				{
					if ( $file != "." && $file != ".." )
					{
						$Files[] = $file;
					}
				}
				closedir($handle);
			}
			return $Files;
		}

		function Clear( $Directories )
		{
			if( is_array( $Directories ) && count( $Directories ) )
			{
				foreach( $Directories as $directory )
				{
					if( file_exists( $directory ) && !is_file( $directory ) )
					{
						if( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' )
						{
							pclose( popen( 'start cmd /C rmdir /Q/S "' . $directory . '"', 'r' ) );
						} else {
							exec( 'rm -R "' . $directory . '" > /dev/null &' );
						}
					}
					elseif( file_exists( $directory ) && is_file( $directory ) )
					{
						unlink( $directory );
					}
				}
			}
		}

		function ViewForm( $cache_dir )
		{
			$DataCollector = new XMLDataCollector();

			$Result = array();
			$Files = array_reverse( self::DirList( $cache_dir ) );
			foreach ( $Files as $file )
			{
				if( $file != '.txt' && $file != '.htaccess' )
				$Result[str_replace( '.', '_', $file )] = array( 'file' => $cache_dir . '/' . $file, 'filemtime' => date( 'Y-m-d H:i:s', filemtime( $cache_dir . '/' . $file ) ), 'difftime' => time() - filemtime( $cache_dir . '/' . $file ) );
				if( is_dir( $cache_dir . '/' . $file ) )
				{
					$Files2 = array_reverse( self::DirList( $cache_dir . '/' . $file ) );
					foreach ( $Files2 as $file2 )
					{
						$Result[str_replace( '.', '_', $file )]['subfiles'][] = array( 'name' => $file2, 'cache' => $cache_dir . '/' . $file . '/' . $file2, 'filemtime' =>  date( 'Y-m-d H:i:s', filemtime( $cache_dir . '/' . $file . '/' . $file2 ) ), 'difftime' => time() - filemtime( $cache_dir . '/' . $file . '/' . $file2 ) );
					}
				}
			}

			$DataCollector->Set( $Result, array( 'FILE_LIST' ) );
			$View = new XSLTView();
			$View->LayoutFile = CMS_DIR . '/view/xsl/backend/cache.xsl';
			$View->Process( $DataCollector->GetData() );
			$View->Shutdown();
		}
	}
?>