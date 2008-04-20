// Диалог редактирования страницы
var EditPageDialog = new Object();
EditPageDialog.dialog = null;

// Инициализация диалога
EditPageDialog.Init = function()
{
	Ext.MessageBox.buttonText.yes = 'Да';
	Ext.MessageBox.buttonText.no = 'Нет';

	var dialog = new Ext.BasicDialog( 
		'edit-page-dlg', { modal:true, autoTabs:true, shadow:true, width:800, height:480, minWidth:300, minHeight:300 }
	);
	dialog.addKeyListener( 27, dialog.hide, dialog );
	tabs = dialog.getTabs();
	this.dialog = dialog;
	
	this.PageTab.Init();
	this.FilesTab.Init();
	
}

// Показать диалог
EditPageDialog.Show = function()
{
	this.dialog.show();
	tabs = this.dialog.getTabs();
	tabs.activate( 'page_tab' );
}


/*********************************************************************************
 * Вкладка: Страница
 */
EditPageDialog.PageTab = new Object();

//Инициализация вкладки
EditPageDialog.PageTab.Init = function()
{
    var tb = new Ext.Toolbar( 'page_tab_toolbar' );
    tb.add({
    	icon: '/cms/i/frontend/design/ico/close.gif',
		cls: 'x-btn-text-icon',
        id:'close_page',
        text:'Закрыть',
        disabled:false,
        handler:function(){EditPageDialog.dialog.hide();},
        tooltip:'Close'
    },'-' );
    tb.add({
    	icon: '/cms/i/frontend/design/ico/save.jpg',
		cls: 'x-btn-text-icon',
        id:'save_page',
        text:'Сохранить',
        disabled:false,
        handler:function(){Pages.PostPage();},
        tooltip:'Save'
    },'-');
    tb.add({
    	icon: '/cms/i/frontend/design/ico/delete.png',
		cls: 'x-btn-text-icon',
        id:'delete_page',
        text:'Удалить страницу',
        disabled:false,
        handler:function(){Ext.MessageBox.confirm('Удаление страницы', 'Вы действительно хотите удалить страницу?', EditPageDialog.DeletePage );},
        tooltip:'Delete'
    }, '-' );
}

EditPageDialog.DeletePage = function( DialogResult )
{
	var PageID = DOM.getElementById( 'page_id' ).value;
	if( DialogResult == 'yes' )
	{
		AjaxLoad( 
			{ 
				'value' : { 'page_id' : PageID }, 
				'script' : '/ajax/admin/page/delete', 
				'method' : 'POST', 
				'caching' : false, 
				'callback_function' : [ EditPageDialog.AfterDeletePage ] 
			}
		);
	}
}

EditPageDialog.AfterDeletePage = function()
{
	EditPageDialog.dialog.hide();
	setTimeout ( EditPageDialog.AfterDeletePagesLoad, 0 );
}

EditPageDialog.AfterDeletePagesLoad = function()
{
	AjaxLoad( { 'value' : { 'page' : Pages.ActivePage }, 'script' : '/ajax/admin/pages/list', 'method' : 'POST', 'callback_function' : [ Pages.Load ] } );
}

// Смена типа отображения AJAX на XSLT и т.п.
EditPageDialog.PageTab.ChangeViewType = function( Select )
{
	var layout_select = DOM.getElementById( 'layout_id' );
	if( Select.value == 'AJAX' || Select.value == 'Redirect' )
	{
		EditPageDialog.dialog.getTabs().disableTab( 'page_files_tab' );
		layout_select.disabled = true;
	}
	else
	{
		EditPageDialog.dialog.getTabs().enableTab( 'page_files_tab' );
		layout_select.disabled = false;
	}
}

// Смена языка редактирования
EditPageDialog.PageTab.ChangeLanguage = function( Select )
{
	var PageID = DOM.getElementById( 'page_id' ).value;
	Pages.LoadPage( PageID, Select.value );
}

//Заполнение формы редактирования самой страницы
EditPageDialog.PageTab.FillForm = function( Result, Settings )
{
	DOM.getElementById( 'page_form' ).reset();

	this.Current = Result['AjaxAdminPages']['page']['Params']['page'];
	var PageData = Result['AjaxAdminPages']['page']['Data'][0];
	DOM.getElementById( 'language_id' ).value = PageData['language_id'];
	DOM.getElementById( 'page_id' ).value = this.Current;
	DOM.getElementById( 'url' ).value = PageData['url'];
	if( PageData['preg'] )
	{
		DOM.getElementById( 'preg' ).checked = true;
		DOM.getElementById( 'preg' ).defaultChecked = true;
	}
	else
	{
		DOM.getElementById( 'preg' ).checked = false;
		DOM.getElementById( 'preg' ).defaultChecked = false;
	}
	DOM.getElementById( 'url' ).value = PageData['url'];
	DOM.getElementById( 'title' ).value = PageData['title'];
	DOM.getElementById( 'keywords' ).value = PageData['keywords'];
	DOM.getElementById( 'description' ).value = PageData['description'];
	
	var layout_select = DOM.getElementById( 'layout_id' );
	if( PageData['view_type'] == 'AJAX' || PageData['view_type'] == 'Redirect' )
	{
		EditPageDialog.dialog.getTabs().disableTab( 'page_files_tab' );
		layout_select.disabled = true;
	}
	else
	{
		EditPageDialog.dialog.getTabs().enableTab( 'page_files_tab' );
		layout_select.disabled = false;
		for( var i=0; i<layout_select.options.length; i++ )
		{
			if( layout_select.options[i].value == PageData['layout_id'] )
			{
				layout_select.options[i].selected = true;
				break;
			}
		}
	}

	var view_type_select = DOM.getElementById( 'view_type' );
	for( var i=0; i<view_type_select.options.length; i++ )
	{
		if( view_type_select.options[i].value == PageData['view_type'] )
		{
			view_type_select.options[i].selected = true;
			break;
		}
	}
}
/**********************************************************************************
 * Вкладка: Подключенные файлы
 *
 */
EditPageDialog.FilesTab = new Object();

EditPageDialog.FilesTab.ViewFiles = null;	// Общий список view файлов
EditPageDialog.FilesTab.ViewFilesMap = null;	// Карта view файлов
EditPageDialog.FilesTab.TreeViewFiles = null;	// Дерево view файлов
EditPageDialog.FilesTab.TreePageViewFiles = null;	// Дерево view файлов, прикрепленных к странице

EditPageDialog.FilesTab.Init = function()
{
    var tb = new Ext.Toolbar( 'page_files_tab_toolbar' );
    tb.add({
    	icon: '/cms/i/frontend/design/ico/close.gif',
		cls: 'x-btn-text-icon',
        id:'close_page_files',
        text:'Закрыть',
        disabled:false,
        handler:function(){EditPageDialog.dialog.hide();},
        tooltip:'Close'
    },'-');
    tb.add({
    	icon: '/cms/i/frontend/design/ico/save.jpg',
		cls: 'x-btn-text-icon',
        id:'save_page_files',
        text:'Сохранить',
        disabled:false,
        handler:EditPageDialog.FilesTab.SaveFiles,
        tooltip:'Save'
    },'-');


    // Заполняем дерево с доступными файлами отображения
    var Tree = Ext.tree;

	var tree = new Tree.TreePanel( 'tree_files', {
        animate:true, 
        enableDD:true,
        containerScroll: true,
        ddGroup: 'treefilesDD',
        rootVisible:true
    });
    var root = new Tree.TreeNode({
        text: 'Файлы представления', 
        allowDrag:false,
        allowDrop:true
    });
    tree.setRootNode(root);
    
	tree.on('beforenodedrop', function( e ){
		Node = e.dropNode;
		var CurEl = Ext.dd.DragDropMgr.dragCurrent.proxy.el;
		CurEl.setStyle( 'visibility', 'hidden' );
		Node.parentNode.removeChild( Node );
		e.cancel = true;
		return false;
	});

	tree.render();

    var Files = EditPageDialog.FilesTab.ViewFiles;
    for( File in Files )
	{
		if( typeof( Files[File]['path'] ) != 'string' || !Files[File]['path'] ) continue;
		var TreeNode = new Ext.tree.TreeNode({text: Files[File]['path'], cls:'file-node', allowDrop:false });
		var ext = Files[File]['path'].split( '.' );
		ext = ext[ext.length-1];
		var Node = tree.root.findChild( 'text', ext );
		if( !Node )
		{
			var TreeNodeExt = new Ext.tree.TreeNode({text: ext, cls:'file-node', allowDrop:true, allowDrag : false });
			Node = tree.root.appendChild( TreeNodeExt );
			tree.root.renderChildren();
		}
		Node.appendChild( TreeNode );
		Node.renderChildren();
		this.AppendFileChildToTreeFiles( TreeNode, this.ViewFilesMap[File] );
	}
	tree.root.expand();
	    

	// Инициализируем дерево файлов, прикрепленных к странице
	var tree = new Tree.TreePanel( 'tree_page_files', {
        animate:true, 
        enableDD:true,
        containerScroll: true,
        ddGroup: 'treefilesDD',
        rootVisible:true
    });
    var root = new Tree.TreeNode({
        text: 'Файлы, прикрепленные к странице', 
        allowDrag:false,
        allowDrop:true
    });
    
    tree.setRootNode(root);
    tree.render();
    root.expand();
	
    tree.on( 'checkchange', EditPageDialog.FilesTab.checkchange );
	tree.on('beforenodedrop', function(e){
	    var n = e.dropNode; // the node that was dropped
	    n.attributes.checked = false;
	    var copy = new Ext.tree.TreeNode( // copy it
	          Ext.apply({}, n.attributes) 
	    );
	    for( var i=0; i< n.childNodes.length; i++ )
	    {
		    var copy2 = new Ext.tree.TreeNode( // copy it
		          Ext.apply({}, n.childNodes[i].attributes) 
		    );
		    copy2.disabled = true;
		    copy.appendChild( copy2 );
	    }
	    e.dropNode = copy; // assign the copy as the new dropNode
	});
    this.TreePageViewFiles = tree;
   
}


EditPageDialog.FilesTab.checkchange = function( Node, Checked )
{
	if( Checked ) Node.getUI().collapse();
	else Node.getUI().expand();
}


EditPageDialog.FilesTab.ClearPageFiles = function()
{
	var tree = this.TreePageViewFiles;
	while( tree.root.childNodes.length ) tree.root.removeChild( tree.root.childNodes[0] );
}


EditPageDialog.FilesTab.FillForm = function( Result, Settings )
{
	var Files = Result['AjaxAdminPages']['page_view_files']['Data']['direct_files'];
	this.ClearPageFiles();

	for( File in Files )
	{
		if( typeof( Files[File]['path'] ) != 'string' || !Files[File]['path'] ) continue;
		var TreeNode = new Ext.tree.TreeNode({text: Files[File]['path'], cls:'file-node', allowDrop:false, checked : ( Files[File]['only_this_file'] ? true : false ) });
	    this.TreePageViewFiles.root.appendChild( TreeNode );
		this.TreePageViewFiles.root.renderChildren();
		this.AppendFileChild( TreeNode, this.ViewFilesMap[File] );
		if( !TreeNode.attributes.checked ) TreeNode.expand();
	}
	this.TreePageViewFiles.root.expand();
}


EditPageDialog.FilesTab.AppendFileChild = function( Node, ChildFiles )
{
	for( FileID in ChildFiles )
	{
		if( typeof( ChildFiles[FileID] ) != 'string' ) continue;
		var TreeNode = new Ext.tree.TreeNode({text:ChildFiles[FileID], cls:'file-node', id: 'tr' + ChildFiles[FileID], allowDrop:false });
		Node.appendChild( TreeNode );
		Node.renderChildren();
		if( this.ViewFilesMap[FileID] ) 
		{
			this.AppendFileChild( TreeNode, this.ViewFilesMap[FileID] );
		}
		TreeNode.disable();
	}
}


EditPageDialog.FilesTab.AppendFileChildToTreeFiles = function( Node, ChildFiles )
{
	for( FileID in ChildFiles )
	{
		if( typeof( ChildFiles[FileID] ) != 'string' ) continue;
		var TreeNode = new Ext.tree.TreeNode({text:ChildFiles[FileID], cls:'file-node', id: 'tr' + ChildFiles[FileID], allowDrop:false });
		Node.appendChild( TreeNode );
		Node.renderChildren();
		if( this.ViewFilesMap[FileID] ) 
		{
			this.AppendFileChild( TreeNode, this.ViewFilesMap[FileID] );
		}
		TreeNode.disable();
	}
}


EditPageDialog.FilesTab.SaveFiles = function()
{
	Ext.MessageBox.show(
		{
			title: 'Please wait...',
			msg: 'Сохраняем...',
			width:240,
			progress:true,
			closable:false,
			animEl: 'save_page_files'
		}
	);

	var TreeRoot = EditPageDialog.FilesTab.TreePageViewFiles.root;
	var files = new Array();
	for( var i=0; i<TreeRoot.childNodes.length; i++ )
	{
		files.push( { 'path' : TreeRoot.childNodes[i].text, 'only_this_file' : TreeRoot.childNodes[i].attributes.checked } );
	}
	
	AjaxLoad( 
		{ 
			'value' : { 'files' : files, 'page_id' : EditPageDialog.PageTab.Current }, 
			'script' : '/ajax/admin/page/edit/post_files', 
			'method' : 'POST', 
			'caching' : false, 
			'callback_function' : [ EditPageDialog.AfterPostFiles ] 
		}
	);
}


EditPageDialog.AfterPostFiles = function()
{
	Ext.MessageBox.updateProgress( 1, 'Сохранено');
	setTimeout ( Pages.Hide, 500 );
}

/**********************************************************************************
 * Вкладка: Подключенные модули
 *
 */
EditPageDialog.ModulesTab = new Object();
EditPageDialog.ModulesTab.FillForm = function( Result, Settings )
{
	this.Current = Result['AjaxAdminPages']['page']['Params']['page'];
	DOM.getElementById( 'frm_add_content_modules' ).page_id.value = this.Current;
}