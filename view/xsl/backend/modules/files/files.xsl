<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes" encoding="utf-8"/>

<xsl:template match="ADMINFILES[@mode='get_files_tree']">
	<div id="files_layout" style="height: 100%">
		<div id="files_west" style="height: 100%">
			<div id="admin_files_container" style="height: 100%; overflow: auto;"/>
		</div>
		<div id="files_center" style="height: 100%; overflow: auto;">
			<div id="admin_files_list_container" style="height: 100%;"/>
		</div>    
	</div>
	<style>
		.height100percent .x-panel-body, .height100percent .x-panel-bwrap
		{
			height: 100% ! important;
			overflow: visible ! important;
		}
	</style>
	<script>
		function addFilesTreeNodes( tree )
		{
			var FileSource, FilePath;
			<xsl:variable name="view_files" select="DATA/VIEW_FILES/ITEM"/>
			<xsl:variable name="view_files_includes" select="DATA/VIEW_FILES_INCLUDES/ITEM"/>
			<xsl:for-each select="$view_files">
				FilePath = '<xsl:value-of select="PATH"/>';
				FileParams = DefineFileParams( FilePath );
				if( FileParams.Nodes.length )
				{
					var Node = FindOrCreateTree( tree.root, FileParams.Nodes[0] );
					FileParams.Nodes[0] = Node;
					for( var i=1; i&lt;FileParams.Nodes.length; i++ )
					{
						Node = FindOrCreateTree( FileParams.Nodes[i-1], FileParams.Nodes[i] );
						FileParams.Nodes[i] = Node;
					}
					var TreeNode = new Ext.tree.TreeNode({text: FileParams.ShortPath, isFile: true, icon: '/lib/j/ext/resources/images/default/tree/leaf.gif' });
					var AppendNode = Node.appendChild( TreeNode );
					Node.renderChildren();
					<xsl:call-template name="append_sub_files">
						<xsl:with-param name="sub_files" select="$view_files[ID = $view_files_includes[FILE_ID = current()/ID]/INCLUDE_FILE_ID]"/>
					</xsl:call-template>
				}
				else alert( 'No define for ' + FilePath );
			</xsl:for-each>		
		}

		function DefineFileParams( path )
		{
			var ShortPath = path;
			var Nodes = new Array();
			var path_part = path.split( '/' );
			
			switch( path_part[0] )
			{
				case '':
					switch( path_part[1] )
					{
						case 'site':
							Nodes = [ 'site' ];
							for( var i=2; i&lt;path_part.length-1; i++ )
							{
								Nodes.push( path_part[i] );
							}
							ShortPath = path_part[path_part.length-1];
						break;
						case 'lib':
							Nodes = [ 'lib' ];
							for( var i=2; i&lt;path_part.length-1; i++ )
							{
								Nodes.push( path_part[i] );
							}
							ShortPath = path_part[path_part.length-1];
						break;
						case 'cms':
							Nodes = [ 'cms' ];
							for( var i=2; i&lt;path_part.length-1; i++ )
							{
								Nodes.push( path_part[i] );
							}
							ShortPath = path_part[path_part.length-1];
						break;
					}
				break;
				case 'view':
					Nodes = [ 'site' ];
					for( var i=1; i&lt;path_part.length-1; i++ )
					{
						Nodes.push( path_part[i] );
					}
					ShortPath = path_part[path_part.length-1];
				break;
				case '%CMS_DIR%':
					Nodes = [ 'cms' ];
					for( var i=2; i&lt;path_part.length-1; i++ )
					{
						Nodes.push( path_part[i] );
					}
					ShortPath = path_part[path_part.length-1];
				break;
			}
			return { 'Nodes': Nodes, 'ShortPath': ShortPath };
		}
		
		function FindOrCreateTree( NodeWhereFind, WhatFind )
		{
			var Node = NodeWhereFind.findChild( 'text', WhatFind );
			if( !Node )
			{
				Node = new Ext.tree.TreeNode({text: WhatFind });
				NodeWhereFind.appendChild( Node );
				NodeWhereFind.renderChildren();
			}
			return Node;
		}
	</script>
</xsl:template>

<xsl:template name="append_sub_files">
	<xsl:param name="sub_files"/>
	<xsl:for-each select="$sub_files">
		var TreeNode = new Ext.tree.TreeNode({text: '<xsl:value-of select="PATH"/>' });
		AppendNode.appendChild( TreeNode );
	</xsl:for-each>	
</xsl:template>

</xsl:stylesheet>