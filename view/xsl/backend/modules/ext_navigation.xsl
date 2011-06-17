<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes" encoding="utf-8"/>

<xsl:template match="NAVIGATION[@mode='default']">
	<script>
		function appendTreeNodes( tree )
		{
		var root = tree.root;
			<xsl:for-each select="DATA/ITEM[PARENT_ID = '']">
				var Node = root.appendChild( 
					new Ext.tree.TreeNode({
						text: '<xsl:value-of select="NAME"/>', 
						allowDrag:false,
						allowDrop:false,
						singleClickExpand: true
				    })
			    );
			    Node.on( 'beforeclick', function( Node ){  if( Node.isExpanded() ){ Node.collapse();} else {Node.expand(); }; return false; } )
				<xsl:variable name="sub_nav" select="../ITEM[PARENT_ID = current()/ID]"/>
				<xsl:for-each select="$sub_nav">
					var N = Node.appendChild( 
						new Ext.tree.TreeNode({
							<xsl:choose>
								<xsl:when test="/DOCUMENT/PAGE/URI != URL">
									text: '<xsl:value-of select="NAME"/>', 
									href: '<xsl:value-of select="/DOCUMENT/PAGE/LANGUAGE_URL"/><xsl:value-of select="URL"/>',
								</xsl:when>
								<xsl:otherwise>
									text: '<xsl:value-of select="NAME"/>',
									cls: 'choosed_node',
								</xsl:otherwise>
							</xsl:choose>
							allowDrag:false,
							allowDrop:false
					    })
				    );
					<xsl:if test="/DOCUMENT/PAGE/URI = URL">
						Node.expand();
						N.select();
					</xsl:if>
				</xsl:for-each>
			</xsl:for-each>
		};
	</script>
    <div id="admin_navigation_container"/>
</xsl:template>

</xsl:stylesheet>