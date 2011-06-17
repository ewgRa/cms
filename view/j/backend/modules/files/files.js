var Tree1, Tree2;

function nodeLoader( drag, drop )
{
	var TreeLoader = new Ext.tree.TreeLoader(
	{
		drag: drag,
		drop: drop,
		processResponse: nodeLoaderProcessResponse,
		dataUrl:'/json/files/get_files',
		createNode: function( NodeArray )
		{
			var Params = 
			{
				text: NodeArray.alias, 
		        FileId: NodeArray.type == 'file' ? NodeArray.file_id : null,
		        Position: NodeArray.type == 'file' ? NodeArray.position : null,
		        NodeAlias: NodeArray.alias,
		        NodeType: NodeArray.type,
		        loader: nodeLoader( this.drag, this.drop ),
		        allowDrag: false,
		        allowDrop: false
			};
			
			if( NodeArray.type == 'file' )
			{
				Params['icon'] = '/lib/j/ext2/resources/images/default/tree/leaf.gif';
				if( this.drag )
				{
			        Params['allowDrag'] = true;
				}
				if( this.drop )
				{
			        Params['allowDrop'] = true;
				}
		        Params['isFile'] = true;
		        if( NodeArray.include_files_count == 0 )
		        {
		        	Params['has_children'] = false;
		        }
			}
			else
			{
		        Params['allowDrag'] = false;
			}
		    return new Ext.tree.AsyncTreeNode( Params );
		}
	} );

	TreeLoader.on( "beforeload", function(treeLoader, node) 
	{
		treeLoader.baseParams.node_path = getNodePath( node );
        treeLoader.baseParams.node_type = node.attributes.NodeType;
        treeLoader.baseParams.file_id = node.attributes.FileId;
        treeLoader.baseParams.position = node.attributes.Position;
    }, this);
      
    
    return TreeLoader
}

function getNodePath( Node )
{
	if( Node.attributes.NodeType == 'directory' || Node.attributes.NodeType == 'file' )
	{
    	var alias = new Array();
    	while( Node.parentNode )
    	{
    		alias.push( Node.attributes.NodeAlias );
    		Node = Node.parentNode;
    	}
    	
    	return '/' + alias.reverse().join( '/' );
	}
}

function getNodeMaxChildPosition( Node )
{
	var max = 0;
	for( var i=0; i< Node.childNodes.length; i++ )
	{
		if( Node.childNodes[i].attributes.Position && Node.childNodes[i].attributes.Position > max ) max = Node.childNodes[i].attributes.Position;
	}
	
	return max;
}

function nodeLoaderProcessResponse( response, node, callback )
{
	var json = response.responseText;
    try {
        var o = eval("("+json+")");
        for(var i = 0, len = o['JsonAdminFilesController'].length; i < len; i++){
            var n = this.createNode(o['JsonAdminFilesController'][i]);
            if(n){
                node.appendChild(n); 
            }
        }
        if(typeof callback == "function"){
            callback(this, node);
        }
    }catch(e){
        this.handleFailure(response);
    }
}
		

function AdminFiles_Class()
{
	this.init = function()
	{
		this.createLayout();
		Tree1 = this.createTree( 'admin_files_container', false, true );
		Tree1.on( "nodedrop", function( e ) 
		{
			Node = e.target;
			newNode = e.dropNode;
			
			//Сохраняем добавление узла
            Ext.Ajax.request({
                method: 'post',
                url: '/json/admin/files/update',
                params: { 'action': 'append_file', 'parent_file_id': Node.attributes.FileId, 'child_file_id': newNode.attributes.FileId, 'position': newNode.attributes.Position }
            });
			
			if( Node.attributes.FileId && Tree2.file_nodes[Node.attributes.FileId] && Tree2.file_nodes[Node.attributes.FileId].length )
			{
				for( var i=0; i < Tree2.file_nodes[Node.attributes.FileId].length; i++ )
				{
					if( Tree2.file_nodes[Node.attributes.FileId][i].isLoaded() )
					{
					    var copy = new Ext.tree.AsyncTreeNode( // copy it
					          Ext.apply({}, newNode.attributes ) 
					    );
					    copy.id = Ext.id(null, "ynode-");
					    copy.attributes.id = copy.id;
						Tree2.file_nodes[Node.attributes.FileId][i].appendChild( copy );
					}
				}
			}

			if(newNode.attributes.has_children == false) {
				newNode.render();
				newNode.loaded = true;
				newNode.expand( false );
			}			
		} );

		Tree2 = this.createTree( 'admin_files_list_container', true, false );
		
		Tree2.on( "append", function( Tree, Node, newNode ) 
		{
			if( newNode.attributes.FileId )
			{
				if( !Tree.file_nodes[newNode.attributes.FileId] ) Tree.file_nodes[newNode.attributes.FileId] = new Array();
				Tree.file_nodes[newNode.attributes.FileId].push( newNode );
			}
	    } );
	}
	
	this.createLayout = function()
	{
		new Ext.MyViewport({
			layout:'border',
            applyTo: 'files_layout',
            autoWidth: true,
            autoScroll: true,
			items:[
				{
					region:'west',
					contentEl: 'files_west',
					split: true,
					title:'Структура файлов',
					width: 200
				},{
					region:'center',
					contentEl: 'files_center',
					split:true,
					title:'Список файлов'
				}
			]
		});		
	}
	
	this.createTree = function( container, drag, drop )
	{
		var Tree = Ext.tree;
		var tree = new Tree.TreePanel({
			el: container,
	        rootVisible: false,
			enableDD:true,
	        border: false,
	        bodyBorder: false,
			autoScroll: true,
			cls: 'height100percent',
			dropConfig: { allowParentInsert:true }
	    });

		// add a tree sorter in folder mode
		var TreeSorter = new Tree.TreeSorter( tree, { folderSort: true, leafAttr: 'isFile' } );
		var oldSortFn = TreeSorter.sortFn;
		TreeSorter.sortFn = function(n1, n2){
			if( n1.attributes.NodeType == 'file' && n2.attributes.NodeType == 'file' )
			{
				if( n1.attributes.Position > n2.attributes.Position ) return 1;
				else if( n1.attributes.Position < n2.attributes.Position ) return -1;
			}
			return oldSortFn( n1, n2 );
	    };

		tree.file_nodes = new Array();

		var root = new Tree.TreeNode({
	        text: 'root', 
	        allowDrag:false,
	        allowDrop:false,
	        expanded : true,
	        NodeType : 'root'
	    });
	    tree.setRootNode(root);
	    
	    tree.on
	    ( 
	    	'click', 
	    	function( Node )
			{
		    }
	    );

		tree.on( 'append', function(tree, parentNode, newNode, newNodeIndex) {
			if(newNode.attributes.has_children == false) {
				newNode.render();
				newNode.loaded = true;
				newNode.expand( false );
			}
		});

		tree.on( 'nodedragover', function(e)
		{
			if( !e.target.isLoaded() ) return false;
			parentNode = e.target;
			var FilesID = new Array();
			FilesID[e.dropNode.attributes.FileId] = true;
			for( var i=0; i < e.dropNode.childNodes.length; i++ ) FilesID[e.dropNode.childNodes[i].attributes.FileId] = true;

			var i=0;
			while( i < 1000 )
			{
				if( FilesID[parentNode.attributes.FileId] ) return false;
				if( !parentNode.parentNode || !parentNode.parentNode.attributes.FileId ) break;
				parentNode = parentNode.parentNode;
				i++;
			}
			
			for( var i=0; i < e.target.childNodes.length; i++ ) 
			{
				if( e.target.childNodes[i].attributes.FileId == e.dropNode.attributes.FileId ) return false;
			}
			
			if( e.dropNode.attributes.FileId == e.target.attributes.FileId ) return false;
		}
		);
		
		tree.on('beforenodedrop', function(e){
			var n = e.dropNode; // the node that was dropped
		    var newNodeAttributes = n.attributes;
		    newNodeAttributes.allowDrag = false;
		    newNodeAttributes.allowDrop = true;
/*		    if( !n.isExpanded() )
		    {
		    	newNodeAttributes.expandable = true;
		    }*/
		    
		    var copy = new Ext.tree.AsyncTreeNode( // copy it
		          Ext.apply({}, newNodeAttributes ) 
		    );
		    copy.id = Ext.id(null, "ynode-");
		    copy.attributes.id = copy.id;

		    for( var i=0; i< n.childNodes.length; i++ )
		    {
			    var copy2 = new Ext.tree.TreeNode( // copy it
			          Ext.apply({}, n.childNodes[i].attributes) 
			    );
			    copy2.id = Ext.id(null, "ynode-");
			    copy2.attributes.id = copy2.id;
			    copy.appendChild( copy2 );
		    }
		    copy.text = getNodePath( n );
		    copy.attributes.Position = parseInt( getNodeMaxChildPosition( e.target ) ) + 1;
		    e.dropNode = copy; // assign the copy as the new dropNode
		});

		tree.render();
		
		var createNodes = new Array( 'cms', 'lib', 'site', 'other sources' );
		
		for( var i=0; i<createNodes.length; i++ )
		{
			tree.root.appendChild( new Ext.tree.AsyncTreeNode({
		        text: createNodes[i], 
		        NodeAlias: createNodes[i],
		        NodeType: 'directory',
		        allowDrag:false,
		        allowDrop:false,
		        loader: nodeLoader( drag, drop )	        
		    }) );			
		}
		
		function appendToOther( AppendsNode, CurrentNode )
		{
			if( CurrentNode.isLoaded() && CurrentNode.attributes.FileId && CurrentNode.attributes.FileId == AppendsNode.attributes.FileId )
			{
				CurrentNode.loaded = true;
				for( var i=0; i< AppendsNode.childNodes.length; i++ )
				{
				    var copy = new Ext.tree.AsyncTreeNode( // copy it
				          Ext.apply({}, AppendsNode.childNodes[i].attributes ) 
				    );
				    copy.id = Ext.id(null, "ynode-");
				    copy.attributes.id = copy.id;
					CurrentNode.appendChild( copy );
				}
			}
			else
			{
				CurrentNode.eachChild( appendToOther, AppendsNode );
			}
			
		}	  

	    return tree;
	}
}



Ext.onReady(
	function()
	{
		var adminFiles = new AdminFiles_Class();
		adminFiles.init();
	}
);