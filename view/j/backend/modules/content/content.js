var Contents = new Object();

/**
 * Загрузка списка юнитов в список
 */
Contents.Load = function( result, Settings )
{
	var ContentItems = DOM.getElementById( 'content_items' );
	while( ContentItems.rows.length > 1 )
	{
		ContentItems.deleteRow( 1 );
	}

	var Items = result['AjaxAdminContent']['content_list']['Data']['items'];

	if( Items && Items.length )
	{
		DOM.getElementById( 'content_items').parentNode.parentNode.style.display = '';
		
		for( var i=0; i<Items.length; i++ )
		{
			var TR = ContentItems.insertRow( -1 );
			TR.className = 'content_items_tr';
			TR.id = Items[i]['id'];
			EventDispatcher.attachEvent( TR, 'click', Contents.OnEditClick );
			if( i%2 )
			{
				TR.className = 'btw';
			}
			var Cell = TR.insertCell( -1 );
			Cell.className = 'content_id';
			Cell.style.textAlign = 'center';
			Cell.innerHTML = Items[i]['id'];
			var Cell = TR.insertCell( -1 );
			Cell.className = 'content_text';
			if( Items[i]['text'] == null ) Items[i]['text'] = '[No language Specified]';
			var DIV = DOM.createElement( 'div', { 'style' : 'overflow: hidden;' } );
			DIV.innerHTML = Items[i]['text'].replace( /<.*?>/ig, '.' );
			Cell.appendChild( DIV );
		}
	}
	
	var PagerResult = Pager.Load( result['AjaxAdminContent']['content_list']['Data']['pager'], { 'url' : '#%PAGE%', 'onclick' : "EventDispatcher.cancelEvent( event ); return AjaxLoad( { 'value' : { 'page' : %PAGE% }, 'script' : '/ajax/admin/content/list', 'method' : 'POST', 'callback_function' : [ Contents.Load ] } );" } );
	if( PagerResult )
	{
		DOM.getElementById( 'pager_content_items' ).parentNode.replaceChild( PagerResult, DOM.getElementById( 'pager_content_items' ) );
		PagerResult.id = 'pager_content_items';
		PagerResult.className = 'pager';
		PagerResult.parentNode.style.display = ''; 
	}
	else DOM.getElementById( 'pager_content_items' ).style.display = 'none'; 
}


/**
 * Отработка нажатия на редактирование
 */ 
Contents.OnEditClick = function( event )
{
	var Cell = EventDispatcher.defineTarget( event );
	var parentNode = Cell.parentNode;
	while( parentNode.nodeName.toLowerCase() != 'tr' )
	{
		parentNode = parentNode.parentNode;
	}
	var ContentID = parentNode.id;
	window.open( '/admin/modules/content/edit.html?content_id=' + ContentID, 'displayWindow',
      'top=' + ( screen.availHeight / 2 - 300 ) + ', left=' + ( screen.availWidth / 2 - 400 ) + ', width=800,height=600,status=no,toolbar=no,menubar=no,scrollbars=no' );
}