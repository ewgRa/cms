<?php
	class Navigation
	{
		function GetData( $Settings, $Language )
		{
			$DB = Registry::Get( 'DB' );
			
			$Result = array();
			$dbq = "
				SELECT t1.id, t1.parent_id, t1.url, IF( t3.name IS NOT NULL, t3.name, CONCAT( 'Data Not Defined in " . $DB->TABLES['NavigationsData'] . " for ID=', t1.id, ' and language_id: ', ? ) ) as name  
				FROM 
					" . $DB->TABLES['Navigations'] . " t1
					INNER JOIN " . $DB->TABLES['Categories'] . " t2 ON( t2.object_alias = 'navigation' AND t2.alias IN( ? ) AND t1.category_id = t2.id )
					LEFT JOIN " . $DB->TABLES['NavigationsData'] . " t3 ON( t1.id = t3.navigation_id AND t3.language_id = ? )
				ORDER BY t1.position
			";
			$dbr = $DB->Query( $dbq, array( $Language, $Settings['alias'], $Language ) );
			$Result = $DB->ResourceToArray( $dbr );
			return $Result;
		}
	}
?>