<?php
	class ActionFormsGetForm
	{
		/**
		 * @param $Params = array( 'language_id' => ?, 'form_alias' => '?' )
		 * @return unknown
		 */
		function Execute( $Params )
		{
			$Result = array();
			
			# Сама форма
			$DB = Registry::Get( 'DB' );
			$dbq = "SELECT * FROM TForms WHERE alias = ?";
			$dbr = $DB->Query( $dbq, array( $Params['form_alias'] ) );
			$Result['form'] = $DB->FetchArray( $dbr );
			
			# Группы этой формы
			$dbq = "SELECT 
						t1.group_id, t1.position, t1.required, 
						t2.type as display_type, t2.view_path, t2.with_free_answer, t2.with_chooser, t2.with_deleter, t5.chooser_title, t5.deleter_title,
						t3.category_id, t3.alias as group_alias, t3.value_type, t4.title as group_title
					FROM TFormsFieldsGroups_ref t1
					INNER JOIN TFormsGroupDisplayTypes t2 ON( t2.id = t1.display_type )
					LEFT JOIN TFormsGroupDisplayTypesData t5 ON( t5.display_id = t2.id AND t5.language_id = ? )
					INNER JOIN TFormsFieldsGroups t3 ON( t3.id = t1.group_id )
					INNER JOIN TFormsFieldsGroupsData t4 ON( t4.group_id = t3.id AND t4.language_id = ? )
					WHERE t1.form_id = ?";
			$dbr = $DB->Query( $dbq, array( $Params['language_id'], $Params['language_id'], $Result['form']['id'] ) );
			$Result['form_groups'] = $DB->ResourceToArray( $dbr );

			# Поля групп
			$dbq = "SELECT t1.fields_group_id as group_id, t1.position, t2.id as field_id, t2.alias as field_alias, t3.title as field_title, IF( t4.field_id, 1, 0 ) as default_value_join_marker, t4.value as default_falue FROM TFormsFieldsGroupsFields_ref t1 INNER JOIN TFormsFields t2 ON( t2.id = t1.field_id ) INNER JOIN TFormsFieldsData t3 ON( t3.field_id = t2.id AND t3.language_id = ? ) LEFT JOIN TFormsFieldsDefaultValues t4 ON( t4.field_id = t2.id AND t4.group_id = t1.fields_group_id AND t4.form_id = ? ) WHERE t1.fields_group_id IN( SELECT group_id FROM TFormsFieldsGroups_ref WHERE form_id = ? )";
			$dbr = $DB->Query( $dbq, array( $Params['language_id'], $Result['form']['id'], $Result['form']['id'] ) );
			$Result['form_groups_fields'] = $DB->ResourceToArray( $dbr );

			return $Result;
		}
	}
?>