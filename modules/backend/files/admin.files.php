<?php
	class AdminFiles
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
				case 'get_files_tree':
				break;
				case 'get_files_by_node':
					$NodePath = $_POST['node_path'];
					$NodeType = $_POST['node_type'];
					
					$PathParts = explode( '/', $NodePath );
					array_shift( $PathParts );
					$SourceType = array_shift( $PathParts );
					
					switch( $NodeType )
					{
						case 'directory':
							switch( $SourceType )
							{
								case 'site':
									$DB = Registry::Get( 'DB' );
									$dbq = "
										SELECT IF( POSITION( '/'
												IN path ) !=0, SUBSTRING( path, 1, POSITION( '/'
												IN path ) -1 ) , path ) as alias, id as file_id,
												IF( POSITION( '/' IN path ) !=0, 'directory', 'file' ) as type,
												( SELECT count( * ) FROM TViewFilesIncludes WHERE file_id = tbl.id ) as include_files_count
										FROM (
											SELECT IF( path REGEXP ?, SUBSTRING( path, LENGTH( ? ) + 1 ) , SUBSTRING( path, LENGTH( ? ) + 1 ) ) AS path, id
											FROM `TViewFiles`
											WHERE path REGEXP ?
											OR path REGEXP ?
										) AS tbl
										GROUP BY alias
									";
									if( count( $PathParts ) )
									{
										$dbr = $DB->Query( $dbq, array( '^view/' . join( '/', $PathParts) . '/.*$', 'view/' . join( '/', $PathParts ) . '/', '/site/' . join( '/', $PathParts ) . '/', '^view/' . join( '/', $PathParts) . '/.*$', '^/site/' . join( '/', $PathParts) . '/.*$' ) );
									}
									else $dbr = $DB->Query( $dbq, array( '^view/.*$', 'view/', '/site/', '^view/.*$', '^/site/.*$' ) );
				
									$nodes = $DB->ResourceToArray( $dbr );
								break;
							}
						break;
						case 'file':
							$DB = Registry::Get( 'DB' );
							$FileID = $_POST['file_id'];
							$dbq = "SELECT t2.path as alias, t2.id as file_id, 'file' as type, ( SELECT count( * ) FROM TViewFilesIncludes WHERE file_id = t2.id ) as include_files_count, t1.position FROM TViewFilesIncludes t1 INNER JOIN TViewFiles t2 ON( t2.id = t1.include_file_id ) WHERE t1.file_id = ?";
							$dbr = $DB->Query( $dbq, array( $FileID ) );
							$nodes = $DB->ResourceToArray( $dbr );
						break;
					}
					
					$Result = $nodes;
				break;
				case 'update':
					switch( $_POST['action'] )
					{
						case 'append_file':
							$dbq = "INSERT IGNORE INTO TViewFilesIncludes SET file_id = ?, include_file_id = ?, position = ?";
//							$DB->Query( $dbq, array( $_POST['parent_file_id'], $_POST['child_file_id'], $_POST['position'] ) );
						break;
					}
				break;
			}
			return $Result;
		}
	}
?>