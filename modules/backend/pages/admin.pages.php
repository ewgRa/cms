<?php
	class AdminPages
	{
		const PAGES_PER_PAGE = 12;
		const PAGES_NAVIGATION_RADIUS = 3;
		
		static $FileIncludeMaps = array();
		
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
				/**
				 * Информация о layout'ах
				 */
				case 'layouts':
					$dbq = "SELECT SQL_CALC_FOUND_ROWS t1.*, t2.* FROM " . $DB->TABLES['Layouts'] . " t1 LEFT JOIN " . $DB->TABLES['LayoutsData'] . " t2 ON( t2.layout_id = t1.id AND t2.language_id = ? )";
					$dbr = $DB->Query( $dbq, array( $Language ) );
					$Result = $DB->ResourceToArray( $dbr );
				break;				
				/**
				 * Информация о view_type для страниц
				 */
				case 'page_view_type':
					$dbq = "DESCRIBE " . $DB->TABLES['Pages'] . " view_type";
					$dbr = $DB->Query( $dbq );
					$db_row = $DB->FetchArray( $dbr );
					$Type = preg_replace( '/^enum\((.*)\)$/', '$1', $db_row['Type'] );
					$Type = explode( ",", str_replace( "'", "", $Type ) );
					$Result = $Type;
				break;
				case 'pages_list':
					$WhereSQL = array( '?' );
					$WhereParams = array( $Language, $Language, 1 );
					if( array_key_exists( 'page', $Params['filter'] ) && $Params['filter']['page'] )
					{
						$Localizer = new Localizer();
						$Localizer->DefineLanguage( $Params['filter']['page'] );
						$Params['filter']['page'] = str_replace( $Localizer->GetLanguageURL(), '', $Params['filter']['page'] );
						$dbq = "SELECT id FROM TPages WHERE id = ? OR url LIKE ? OR IF( preg = 1, ? REGEXP url, 0 )";
						$dbr = $DB->Query( $dbq, array( $Params['filter']['page'], '%' . $Params['filter']['page'] . '%', $Params['filter']['page'] ) );
						$Pages = $DB->ResourceToArray( $dbr, 'id' );
						$WhereSQL[] = 'AND t1.id IN( ? )'; 
						$WhereParams[] = $Pages;
					}
					$dbq = "SELECT SQL_CALC_FOUND_ROWS t1.*, if( t2.title is NULL, '[No language specified]', t2.title ) as title, t3.name as layout_name FROM " . $DB->TABLES['Pages'] . " t1 LEFT JOIN " . $DB->TABLES['PagesData'] . " t2 ON( t2.page_id = t1.id AND t2.language_id = ? ) LEFT JOIN " . $DB->TABLES['LayoutsData'] . " t3 ON( t3.layout_id = t1.layout_id AND t3.language_id = ? ) WHERE " . join( ' ', $WhereSQL ) . " ORDER BY t1.id DESC " . $DB->GetLimit( self::PAGES_PER_PAGE , ($Params['page']-1)*self::PAGES_PER_PAGE  );
					$dbr = $DB->Query( $dbq, $WhereParams );
					$Result['items'] = $DB->ResourceToArray( $dbr );

					$dbq = "SELECT FOUND_ROWS() as found_rows";
					$dbr = $DB->Query( $dbq );
					$row = $DB->FetchArray( $dbr );
					$Result['found_rows'] = $row['found_rows'];
					$Result['pager'] = Pager::Get( $Result['found_rows'], self::PAGES_PER_PAGE , $Params['page'], self::PAGES_NAVIGATION_RADIUS  );
				break;
				case 'page':
					$dbq = "SELECT t1.*, t2.description, t2.keywords, t2.title, IF( t2.language_id IS NULL, ?, t2.language_id ) as language_id FROM " . $DB->TABLES['Pages'] . " t1 LEFT JOIN " . $DB->TABLES['PagesData'] . " t2 ON( t2.page_id = t1.id AND t2.language_id = ? ) WHERE t1.id = ?";
					$lng = $Language;
					if( $Params['language_id'] ) $lng = $Params['language_id'];
					$dbr = $DB->Query( $dbq, array( $lng, $lng, $Params['page'] ) );
					$Result = $DB->ResourceToArray( $dbr );
				break;
				case 'page_view_files':
					$dbq = 'SELECT t2.id, t2.path, t1.only_this_file FROM ' . $DB->TABLES['PagesViewFiles_ref'] . ' t1 INNER JOIN ' . $DB->TABLES['ViewFiles'] . ' t2 ON( t1.page_id = ? AND t2.id = t1.file_id ) ORDER BY path';
					$dbr = $DB->Query( $dbq, array( $Params['page'] ) );
					$Result['direct_files'] = array();
					while( $db_row = $DB->FetchArray( $dbr ) )
					{
						$Result['direct_files'][$db_row['id']] = array( 'path' => $db_row['path'], 'only_this_file' => $db_row['only_this_file'] );
					}
				break;
				case 'view_files':
					$dbq = 'SELECT * FROM ' . $DB->TABLES['ViewFiles'] . ' ORDER BY path';
					$dbr = $DB->Query( $dbq );
					$Result['direct_files'] = array();
					$Files = array();
					while( $db_row = $DB->FetchArray( $dbr ) )
					{
						$Files[] = $db_row['id'];
						$Result['direct_files'][$db_row['id']] = array( 'path' => $db_row['path'] );
					}
					self::GetFileMap( $Files );
					$Result['file_map'] = self::$FileIncludeMaps; 
				break;
				case 'page_edit_post':
					if( !array_key_exists( 'preg', $_POST ) ) $_POST['preg'] = null;
					
					$dbq = 'UPDATE ' . $DB->TABLES['Pages'] . ' SET url = ?, preg = ?, view_type = ?, layout_id = ? WHERE id = ?';
					$DB->Query( $dbq, array( $_POST['url'], $_POST['preg'], $_POST['view_type'], $_POST['layout_id'], $_POST['page_id'] ) );
					
					$dbq = 'REPLACE INTO ' . $DB->TABLES['PagesData'] . ' SET title = ?, keywords = ?, description = ?, page_id = ?, language_id = ?';
					$DB->Query( $dbq, array( $_POST['title'], $_POST['keywords'], $_POST['description'], $_POST['page_id'], $_POST['language_id'] ) );
					ClearCache::Clear( array( CACHE_DIR . '/engine/page' ) );
				break;
				case 'page_add':
					if( !array_key_exists( 'preg', $_POST ) ) $_POST['preg'] = null;
					
					if( is_null( $_POST['preg'] ) )
					{
						$dbq = 'SELECT * FROM ' . $DB->TABLES['Pages'] . ' WHERE url = ? AND preg IS ?';
					}
					else 
					{
						$dbq = 'SELECT * FROM ' . $DB->TABLES['Pages'] . ' WHERE url = ? AND preg = ?';
					}
					$dbr = $DB->Query( $dbq, array( $_POST['url'], $_POST['preg'] ) );
					if( $DB->RecordCount( $dbr ) )
					{
						$Result = array( 'add_status' => 'already_added' );
						break;
					}
					$dbq = 'INSERT INTO ' . $DB->TABLES['Pages'] . ' SET url = ?, preg = ?, view_type = ?, layout_id = ?';
					$DB->Query( $dbq, array( $_POST['url'], $_POST['preg'], $_POST['view_type'], $_POST['layout_id'] ) );
					$Result = array( 'add_status' => 'success_added', 'id' => $DB->InsertID() );
					ClearCache::Clear( array( CACHE_DIR . '/engine/content' ) );
				break;
				case 'page_delete':
					$dbq = 'DELETE FROM ' . $DB->TABLES['PagesViewFiles_ref'] . ' WHERE page_id = ?';
					$DB->Query( $dbq, array( $_POST['page_id'] ) );
					$dbq = 'DELETE FROM ' . $DB->TABLES['PagesRights_ref'] . ' WHERE page_id = ?';
					$DB->Query( $dbq, array( $_POST['page_id'] ) );
					$dbq = 'DELETE FROM ' . $DB->TABLES['PagesData'] . ' WHERE page_id = ?';
					$DB->Query( $dbq, array( $_POST['page_id'] ) );
					$dbq = 'DELETE FROM ' . $DB->TABLES['PagesModules_ref'] . ' WHERE page_id = ?';
					$DB->Query( $dbq, array( $_POST['page_id'] ) );
					$dbq = 'DELETE FROM ' . $DB->TABLES['PagesParams'] . ' WHERE page_id = ?';
					$DB->Query( $dbq, array( $_POST['page_id'] ) );
					$dbq = 'DELETE FROM ' . $DB->TABLES['Pages'] . ' WHERE id = ?';
					$DB->Query( $dbq, array( $_POST['page_id'] ) );
					$Result = true;
					ClearCache::Clear( array( CACHE_DIR . '/engine/content' ) );
				break;
				case 'page_edit_files_post':
					$dbq = 'DELETE FROM ' . $DB->TABLES['PagesViewFiles_ref'] . ' WHERE page_id = ?';
					$DB->Query( $dbq, array( $_POST['page_id'] ) );
					foreach( $_POST['files']  as $File )
					{
						$dbq = 'INSERT INTO ' . $DB->TABLES['PagesViewFiles_ref'] . ' SET page_id = ?, only_this_file = ?, file_id = ( SELECT id FROM ' . $DB->TABLES['ViewFiles'] . ' WHERE path = ? )';
						if( !$File['only_this_file'] || $File['only_this_file'] == 'false' ) $File['only_this_file'] = null;
						elseif( $File['only_this_file'] == 'true' ) $File['only_this_file'] = 1;
						$DB->Query( $dbq, array( $_POST['page_id'], $File['only_this_file'], $File['path'] ) );
					}
					ClearCache::Clear( array( CACHE_DIR . '/engine/content' ) );
				break;
				/**
				 * hack for delpress
				 * добавляем контент странице
				 */
				case 'add_content_modules':
					$dbq = "SELECT * FROM " . $DB->TABLES['PagesModules_ref'] . " WHERE page_id = ? and module_id = 2";
					$dbr = $DB->Query( $dbq, array( $_POST['page_id'] ) );
					if( $DB->RecordCount( $dbr ) )
					{
						$Result = 'already_added';
					}
					else 
					{
						$dbq = "INSERT INTO " . $DB->TABLES['Contents'] . " SET id = NULL";
						$dbr = $DB->Query( $dbq );
						$ContentID = $DB->InsertID();
						
						$dbq = "INSERT INTO " . $DB->TABLES['PagesModules_ref'] . " SET page_id = ?, module_id = 2, section_id = 6, module_settings = ?";
						$dbr = $DB->Query( $dbq, array( $_POST['page_id'], serialize( array( 'units' => array( $ContentID ) ) ) ) );

						$dbq = "INSERT INTO " . $DB->TABLES['PagesModules_ref'] . " SET page_id = ?, module_id = 3, section_id = 2, module_settings = ?";
						$dbr = $DB->Query( $dbq, array( $_POST['page_id'], 'a:2:{s:5:"alias";a:1:{i:0;s:4:"main";}s:4:"mode";s:4:"main";}' ) );

						$dbq = "INSERT INTO " . $DB->TABLES['PagesModules_ref'] . " SET page_id = ?, module_id = 5, section_id = 1, module_settings = ?";
						$dbr = $DB->Query( $dbq, array( $_POST['page_id'], 'a:1:{s:4:"mode";s:12:"page_heading";}' ) );

						$dbq = "INSERT INTO " . $DB->TABLES['PagesModules_ref'] . " SET page_id = ?, module_id = 5, section_id = 3, module_settings = ?";
						$dbr = $DB->Query( $dbq, array( $_POST['page_id'], 'a:2:{s:4:"mode";s:30:"theme_catalog_and_section_list";s:10:"categories";a:1:{i:0;s:10:"index_page";}}' ) );

						$dbq = "INSERT INTO " . $DB->TABLES['PagesModules_ref'] . " SET page_id = ?, module_id = 6, section_id = 4";
						$dbr = $DB->Query( $dbq, array( $_POST['page_id'] ) );
					}
					
					ClearCache::Clear( array( CACHE_DIR . '/engine/page' ) );
				break;				
			}
			return $Result;
		}
		
		function GetFileMap( $Files )
		{
			$Files = array_diff( $Files, array_keys( self::$FileIncludeMaps ) );
			
			$DB = Registry::Get( 'DB' );
			$dbq = 'SELECT t1.file_id, t1.include_file_id, t2.path FROM ' . $DB->TABLES['ViewFilesIncludes'] . ' t1 INNER JOIN ' . $DB->TABLES['ViewFiles'] . ' t2 ON ( t2.id = t1.include_file_id ) WHERE t1.file_id IN ( ? ) ';
			$dbr = $DB->Query( $dbq, array( $Files ) );
			$Files = array();
			while( $db_row = $DB->FetchArray( $dbr ) )
			{
				$Files[] = $db_row['include_file_id'];
				if( !array_key_exists( $db_row['file_id'], self::$FileIncludeMaps ) ) self::$FileIncludeMaps[$db_row['file_id']] = array();
				self::$FileIncludeMaps[$db_row['file_id']][$db_row['include_file_id']] = $db_row['path'];
			}
			if( count( $Files ) ) self::GetFileMap( $Files );
		}
	}
?>