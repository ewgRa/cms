function FCKeditor_OnComplete( editorInstance )
{
    editorInstance.LinkedField.form.onsubmit = ContentEdit.Save;
	editorInstance.Events.AttachEvent( 'OnSelectionChange', FCKeditor_OnSelectionChange ) ;
	editorInstance.Events.AttachEvent( "OnChangeLanguage", FCKeditor_ChangeLanguage );
	FCKeditor_FillLanguages( editorInstance );
}

function FCKeditor_OnSelectionChange( editorInstance )
{
	var oFCKeditor = FCKeditorAPI.GetInstance( 'text' );
	if( oFCKeditor.IsDirty() ) oFCKeditor.EditorWindow.parent.FCKToolbarItems.LoadedItems['Save'].Enable();
}


FCKeditor_FillLanguages = function( editorInstance )
{
	var Combo = editorInstance.EditorWindow.parent.FCKToolbarItems.GetItem('ChangeLanguage')._Combo;
    // Get the format names from the language file.
    var Languages = Localizer.GetLanguages();
    for( LangID in Languages )
    {
		Combo.AddItem( LangID, '<div class="BaseFont"><a>' + Languages[LangID] + '</a></div>', Languages[LangID] ) ;
    }
	Combo.SelectItemByLabel( LanguageAbbr, true );
}

FCKeditor_ChangeLanguage = function( editorInstance )
{
	var Combo = editorInstance.EditorWindow.parent.FCKToolbarItems.GetItem('ChangeLanguage')._Combo;
	var elem = DOM.getElementById( 'content_data[' + Combo.value + ']' );
	if( elem )
	{
		var oFCKeditor = FCKeditorAPI.GetInstance( 'text' );
		oFCKeditor.SetHTML( elem.value );
		DOM.getElementById( 'language_id' ).value = Combo.value;
	}
}



var ContentEdit = new Object();

ContentEdit.Save = function( event )
{
	var oFCKeditor = FCKeditorAPI.GetInstance( 'text' );
	oFCKeditor.EditorWindow.parent.FCKToolbarItems.LoadedItems['Save'].Disable();
	AjaxLoad(
		{
			'value' : { 'q' : DOM.getElementById( 'edit_form' ) },
			'script' : '/ajax/admin/content/edit/post',
			'method' : 'POST',
			'caching' : false,
			'callback_function' : [ ContentEdit.AfterSave ]
		}
	);
	return false;
}

ContentEdit.AfterSave = function( result, Settings )
{
	if( result['AjaxAdminContent']['content_edit_post']['Data']['result'] == 1 ) alert( 'Сохранено' );
	else alert( 'Что-то не сработало' );

	var oFCKeditor = FCKeditorAPI.GetInstance( 'text' );
	oFCKeditor.ResetIsDirty();

	var elem = DOM.getElementById( 'content_data[' + result['AjaxAdminContent']['content_edit_post']['Data']['language_id'] + ']' );
	elem.value = oFCKeditor.GetHTML();
}

EventDispatcher.attachEvent( window, 'load', 
	function()
	{
		var sBasePath = '/lib/j/fckeditor/';
		var oFCKeditor = new FCKeditor( 'text', '100%', 600 ) ;
		oFCKeditor.Config.CustomConfigurationsPath = '/site/j/all/fckeditor/fckconfig.js';
		oFCKeditor.Config.StylesXmlPath		= '/site/j/all/fckeditor/fckstyles.xml';
		oFCKeditor.BasePath	= sBasePath ;
		oFCKeditor.Value	= '';
		oFCKeditor.ReplaceTextarea();
	}
);