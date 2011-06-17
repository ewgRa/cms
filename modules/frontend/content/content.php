<?php
	class Content
	{
		function GetData( $Settings, $Language )
		{
			$DB = Registry::Get( 'DB' );
			$dbq = "SELECT 
						t1.id, 
						IF( t2.text IS NOT NULL, t2.text, CONCAT( 'Data Not Defined in " . $DB->TABLES['ContentsData'] . " for ID=', t1.id, ' and language_id: ', ? ) ) as text
					FROM
						" . $DB->TABLES['Contents'] . " t1
						LEFT JOIN " . $DB->TABLES['ContentsData'] . " t2 ON( t1.id = t2.content_id AND t2.language_id = ? ) 
					WHERE t1.id IN( ? )
			";
			$dbr = $DB->Query( $dbq, array( $Language, $Language, $Settings['units'] ) );
			$Result = $DB->ResourceToArray( $dbr );
			return $Result;
		}
	}
?>