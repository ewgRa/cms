Ext.onReady( function()
{
	var Tree = Ext.tree;
	var tree = new Tree.TreePanel(
	{
		el: 'admin_navigation_container',
        animate:true, 
        enableDD:true,
        containerScroll: true,
        ddGroup: 'treeDD',
        rootVisible: false,
        border: false,
        bodyBorder: false,
        autoHeight: true
    });

    var root = new Tree.TreeNode({
        text: '', 
        allowDrag:false,
        allowDrop:true,
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
    
    tree.render();

    appendTreeNodes( tree );
} );