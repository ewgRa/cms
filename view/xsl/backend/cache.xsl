<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes" encoding="utf-8"/>

<xsl:template match="/DOCUMENT">
<html>
<body>
<script>
	function check_subitems( DIVName, checked )
	{
		var DIV = document.getElementById( DIVName );
		for( var i=0; i&lt;DIV.childNodes.length; i++ )
		{
			if( DIV.childNodes[i].type == 'checkbox' )
			{
				DIV.childNodes[i].checked = checked;
			}
		}
	}
	
	function uncheck_parent( input_object, checked )
	{
		var children = input_object;
		while( children.parentNode.tagName.toUpperCase() != 'DIV' )
		{
			children = children.parentNode;
		}
		var DIV = children.parentNode;
		for( var i=0; i&lt;DIV.parentNode.childNodes.length; i++ )
		{
			if( DIV.parentNode.childNodes[i].name == DIV.id )
			{
				if( !checked ) DIV.parentNode.childNodes[i].checked = checked;
			}
		}
	}	
</script>
<form method="post">
<xsl:for-each select="FILE_LIST/*">
	<input type="checkbox" onclick="check_subitems( this.name, this.checked );" name="{FILE}" value="{FILE}"/>
	<xsl:value-of select="name()"/> 
	<xsl:if test="DIFFTIME &lt; 120">
		<font color="red">(<xsl:value-of select="FILEMTIME"/>)</font>
	</xsl:if>
	<xsl:if test="DIFFTIME &gt;= 120">
		(<xsl:value-of select="FILEMTIME"/>)
	</xsl:if>
		
	<div id="{FILE}">
	<xsl:for-each select="SUBFILES/ITEM">
		&#xA0;&#xA0;&#xA0;&#xA0;&#xA0;&#xA0;&#xA0;&#xA0;<input type="checkbox" onclick="uncheck_parent( this, this.checked );" name="{CACHE}" value="{CACHE}"/>
		<xsl:value-of select="NAME"/>
			<xsl:if test="DIFFTIME &lt; 120">
		<font color="red">(<xsl:value-of select="FILEMTIME"/>)</font>
	</xsl:if>
	<xsl:if test="DIFFTIME &gt;= 120">
		(<xsl:value-of select="FILEMTIME"/>)
	</xsl:if>
	<br/>
	</xsl:for-each>
	</div>
</xsl:for-each>
<input type="Submit" value="Очистить"/>
</form>
</body>
</html>
</xsl:template>

</xsl:stylesheet>