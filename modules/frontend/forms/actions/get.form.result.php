<?php
	class ActionFormsGetFormResult
	{
		/**
		 * @param $Params = array( 'result_id' => ? )
		 * @return unknown
		 */
		function Execute( $Params )
		{
			$Result = array();

			$DB = Registry::Get( 'DB' );
			
			$dbq = "SELECT * FROM TFormsResults WHERE id = ?";
			$dbr = $DB->Query( $dbq, array( $Params['result_id'] ) );
			$Result['form_result'] = $DB->FetchArray( $dbr );

			$dbq = "SELECT * FROM TFormsResultsValues WHERE result_id = ?";
			$dbr = $DB->Query( $dbq, array( $Params['result_id'] ) );
			$Result['form_result_values'] = $DB->ResourceToArray( $dbr );

			return $Result;
		}
	}
?>