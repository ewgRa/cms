<?php
	/**
	 * Класс, реализующий оформление заказа
	 */
	class AdminContent
	{
		const UNIT_PER_PAGE = 12;
		const UNIT_NAVIGATION_RADIUS = 3;
		
		function GetData( $Settings, $Language, $Params )
		{
			$Result = array();
			$DB = Registry::Get( 'DB' );
			switch( $Settings['mode'] )
			{
				case 'contents':
				break;
				/**
				 * Список контентов постранично
				 */
				case 'content_list':
					$WhereSQL = array( '?' );
					$WhereParams = array( $Language, '1' );
					if( array_key_exists( 'id', $Params['filter'] ) && $Params['filter']['id'] )
					{
						$WhereSQL[] = 'AND t1.id = ?'; 
						$WhereParams[] = $Params['filter']['id'];
					}
					if( array_key_exists( 'page', $Params['filter'] ) && $Params['filter']['page'] )
					{
						$Localizer = new Localizer();
						$Localizer->DefineLanguage( $Params['filter']['page'] );
						$Params['filter']['page'] = str_replace( $Localizer->GetLanguageURL(), '', $Params['filter']['page'] );
						$dbq = "SELECT * FROM TPages t1 INNER JOIN " . $DB->TABLES['PagesModules_ref'] . " t2 ON( t2.page_id = t1.id ) INNER JOIN TModules t3 ON( t3.id = t2.module_id AND t3.name = 'ContentController' ) WHERE t1.id = ? OR t1.url LIKE ? OR IF( t1.preg = 1, ? REGEXP t1.url, 0 )";
						$dbr = $DB->Query( $dbq, array( $Params['filter']['page'], '%' . $Params['filter']['page'] . '%', $Params['filter']['page'] ) );
						$Units = array();
						while( $db_row = $DB->FetchArray( $dbr ) )
						{
							if( $db_row['module_settings'] )
							{
								$Settings = unserialize( $db_row['module_settings'] );
								if( array_key_exists( 'units', $Settings ) )
								{
									$Units = array_merge( $Units, $Settings['units'] );
								}
							}
						}
						$WhereSQL[] = 'AND t1.id IN( ? )'; 
						$WhereParams[] = $Units;
					}
					$dbq = "SELECT SQL_CALC_FOUND_ROWS t1.id, t2.language_id, SUBSTRING( t2.text, 1, 350 ) as text FROM " . $DB->TABLES['Contents'] . " t1 LEFT JOIN " . $DB->TABLES['ContentsData'] . " t2 ON( t2.content_id = t1.id AND t2.language_id = ? ) WHERE " . join( ' ', $WhereSQL ) . " ORDER BY id DESC " . $DB->GetLimit( self::UNIT_PER_PAGE , ($Params['page']-1)*self::UNIT_PER_PAGE  );
					$dbr = $DB->Query( $dbq, $WhereParams );
					$Result['items'] = $DB->ResourceToArray( $dbr );


					$dbq = "SELECT FOUND_ROWS() as found_rows";
					$dbr = $DB->Query( $dbq );
					$row = $DB->FetchArray( $dbr );
					$Result['found_rows'] = $row['found_rows'];
					$Result['pager'] = Pager::Get( $Result['found_rows'], self::UNIT_PER_PAGE , $Params['page'], self::UNIT_NAVIGATION_RADIUS  );
				break;
				case 'content_edit':
					$dbq = "SELECT t1.id, t2.id AS language_id, t2.abbr as language_abbr, t3.text FROM " . $DB->TABLES['Contents'] . " t1 LEFT JOIN " . $DB->TABLES['Languages'] . " t2 ON ( 1 ) LEFT JOIN " . $DB->TABLES['ContentsData'] . " t3 ON ( t3.content_id = t1.id AND t3.language_id = t2.id ) WHERE t1.id = ?";
					$dbr = $DB->Query( $dbq, array( $Params['content_id'] ) );
					$Result = $DB->ResourceToArray( $dbr );
				break;
				case 'content_edit_post':
					$dbq = "REPLACE " . $DB->TABLES['ContentsData'] . " SET text = ?, content_id = ?, language_id = ?";
					$dbr = $DB->Query( $dbq, array( $_POST['text'], $_POST['id'], $_POST['language_id'] ) );
					$Result = array( 'language_id' => $_POST['language_id'], 'result' => 1 );
					ClearCache::Clear( array( CACHE_DIR . '/modules/content' ) );
				break;
			}
			return $Result;
		}
	}
?>
