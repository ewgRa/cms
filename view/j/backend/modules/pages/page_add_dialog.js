// Диалог добавления страницы
var AddPageDialog = new Object();
AddPageDialog.dialog = null;

// Инициализация диалога
AddPageDialog.Init = function()
{
	var dialog = new Ext.BasicDialog( 
		'add-page-dlg', { modal:true, autoshadow:true, width:800, height:269 }
	);
	dialog.addKeyListener( 27, dialog.hide, dialog );
	dialog.body.appendChild( DOM.getElementById( 'page_add_dialog_container' ) );
	this.dialog = dialog;

    var tb = new Ext.Toolbar( 'page_add_tab_toolbar' );
    tb.add({
    	icon: '/cms/i/frontend/design/ico/close.gif',
		cls: 'x-btn-text-icon',
        id:'close_page',
        text:'Закрыть',
        disabled:false,
        handler:function(){AddPageDialog.dialog.hide();},
        tooltip:'Close'
    },'-' );
    tb.add({
    	icon: '/cms/i/frontend/design/ico/save.jpg',
		cls: 'x-btn-text-icon',
        id:'save_page',
        text:'Добавить',
        disabled:false,
        handler:function(){AddPageDialog.PostPage();},
        tooltip:'Save'
    },'-');
}

// Показать диалог
AddPageDialog.Show = function()
{
	this.dialog.show();
}

AddPageDialog.PostPage = function()
{
	AjaxLoad( 
		{ 
			'value' : { 'q' : DOM.getElementById( 'page_add_form' ) }, 
			'script' : DOM.getElementById( 'page_add_form' ).action, 
			'method' : 'POST', 
			'caching' : false, 
			'callback_function' : [ AddPageDialog.AfterPostPage ] 
		}
	);
}

AddPageDialog.AddedPageID = null;
AddPageDialog.AfterPostPage = function( result )
{
	if( result['AjaxAdminPages']['page_add']['Data']['add_status'] == 'already_added' )
	{
		alert( 'Такая страница уже существует' );
	}
	else if( result['AjaxAdminPages']['page_add']['Data']['add_status'] == 'success_added' )
	{
		AddPageDialog.dialog.hide();
		AddPageDialog.AddedPageID = result['AjaxAdminPages']['page_add']['Data']['id'];

		setTimeout ( AddPageDialog.LoadPostPages, 0 );
		setTimeout ( AddPageDialog.LoadPostPage, 0 );
	}
}

AddPageDialog.LoadPostPages = function()
{
		AjaxLoad( { 'value' : { 'page' : Pages.ActivePage }, 'script' : '/ajax/admin/pages/list', 'method' : 'POST', 'callback_function' : [ Pages.Load ] } );
}

AddPageDialog.LoadPostPage = function()
{
	Pages.LoadPage( AddPageDialog.AddedPageID, null );
}

// Смена типа отображения AJAX на XSLT и т.п.
AddPageDialog.ChangeViewType = function( Select )
{
	var layout_select = DOM.getElementById( 'layout_add_id' );
	if( Select.value == 'AJAX' || Select.value == 'Redirect' )
	{
		layout_select.disabled = true;
	}
	else
	{
		layout_select.disabled = false;
	}
}