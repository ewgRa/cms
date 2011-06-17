var Pages = new Object();
Pages.ActivePage = 1;

/**
 * Загрузка страниц в таблицу
 */
Pages.Load = function( result, Settings )
{
	var Items = result['AjaxAdminPages']['pages_list']['Data']['items'];
	var PageItems = DOM.getElementById( 'page_list' );
	while( PageItems.rows.length > 1 ) PageItems.deleteRow( 1 );

	if( Items && Items.length )
	{
		for( var i=0; i<Items.length; i++ )
		{
			var TR = PageItems.insertRow( -1 );
			if( i%2 )
			{
				TR.className = 'btw';
			}
			var Cell = TR.insertCell( -1 );
			Cell.style.textAlign = 'center';
			Cell.innerHTML = Items[i]['id'];
			var Cell = TR.insertCell( -1 );
			if( Items[i]['title'] )
			{
				var DIV = DOM.createElement( 'div', { 'style' : 'overflow: hidden;' } );
				DIV.innerHTML = Items[i]['title'].htmlspecialchars();
				Cell.appendChild( DIV );
			}
			var Cell = TR.insertCell( -1 );
			var DIV = DOM.createElement( 'div', { 'style' : 'overflow: hidden;' } );
			if( Items[i]['preg'] || Items[i]['view_type'] == 'AJAX' || Items[i]['view_type'] == '' )
			{
				DIV.innerHTML = Items[i]['url'].htmlspecialchars();
			}
			else
			{
				var A = DOM.createElement( 'a', { 'target' : '_blank', 'href' : Items[i]['url'] } );
				A.innerHTML = Items[i]['url'].htmlspecialchars();
				DIV.appendChild( A );
			}
			Cell.appendChild( DIV );

			var Cell = TR.insertCell( -1 );
			Cell.style.textAlign = 'center';
			Cell.innerHTML = Items[i]['view_type'];
			var Cell = TR.insertCell( -1 );
			Cell.style.textAlign = 'center';
			Cell.innerHTML = Items[i]['layout_name'];

			var Cell = TR.insertCell( -1 );
			Cell.style.textAlign = 'center';
			var A = DOM.createElement( 'a', { 'href' : '#' } );
			eval(
				"EventDispatcher.attachEvent( A, 'click', function( event )"+
					'{'+
						'Pages.LoadPage( ' + Items[i]['id'] + ', null );'+
					"}"+
				");"
			);
			A.innerHTML = 'Редактировать';
			Cell.appendChild( A );
		}
	}
	
	Pages.ActivePage = result['AjaxAdminPages']['pages_list']['Data']['pager']['active'];
	var PagerResult = Pager.Load( result['AjaxAdminPages']['pages_list']['Data']['pager'], { 'url' : '#%PAGE%', 'onclick' : "EventDispatcher.cancelEvent( event ); return AjaxLoad( { 'value' : { 'page' : %PAGE% }, 'script' : '/ajax/admin/pages/list', 'method' : 'POST', 'callback_function' : [ Pages.Load ] } );" } );
	if( PagerResult )
	{
		DOM.getElementById( 'pager_page_items' ).parentNode.replaceChild( PagerResult, DOM.getElementById( 'pager_page_items' ) );
		PagerResult.id = 'pager_page_items';
		PagerResult.className = 'pager';
		PagerResult.parentNode.style.display = ''; 
	}
	else DOM.getElementById( 'pager_page_items' ).style.display = 'none';
}


/**
 * Загрузка данных о странице ( для редактирования )
 */
Pages.LoadPage = function( PageID, LanguageID )
{
	AjaxLoad( 
		{
			'script' : '/ajax/admin/page/' + PageID + ( LanguageID ? '?language_id=' + LanguageID : '' ),
			'method' : 'GET',
			'caching' : false, 
			'callback_function' : [ 'EditPageDialog.PageTab.FillForm', 'EditPageDialog.FilesTab.FillForm', 'EditPageDialog.ModulesTab.FillForm', 'EditPageDialog.Show' ]
		}
	);
}

/**
 * Сохранение данных о странице
 */
Pages.PostPage = function()
{
	Ext.MessageBox.show(
		{
			title: 'Please wait...',
			msg: 'Сохраняем...',
			width:240,
			progress:true,
			closable:false,
			animEl: 'save_page'
		}
	);

	AjaxLoad( 
		{ 
			'value' : { 'q' : DOM.getElementById( 'page_form' ) }, 
			'script' : '/ajax/admin/page/edit/post', 
			'method' : 'POST', 
			'caching' : false, 
			'callback_function' : [ Pages.AfterPostPage ] 
		}
	);
}

/**
 * Прогресс бар сохранения страницы
 */
Pages.AfterPostPage = function( result, Settings )
{
	Ext.MessageBox.updateProgress( 1, 'Сохранено');
	setTimeout ( Pages.Hide, 500 );
}

Pages.Hide = function()
{
	Ext.MessageBox.hide();
}