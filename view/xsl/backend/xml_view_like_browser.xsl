<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="no" encoding="utf-8"/>

<xsl:template match="/">
	<style>
		.inline
		{
			display: inline;
		}
		
		font.tag
		{
			font-weight: bold;
			color: #800080;
		}
	
		font.attribute_name
		{
			font-weight: bold;
			color: black;
		}	
		
		font.attribute_value
		{
			color: #0000FF;
		}
		
		a.node, a.node:visited, a.node:hover, a.node:active
		{
			text-decoration: none;
			color: black;
		}
		</style>
	<script>
		function ToggleNode( object )
		{
			if( document.getElementById( object.id + '_2' ).style.display == 'none' )
			{
				document.getElementById( object.id + '_2' ).style.display = '';
			}
			else document.getElementById( object.id + '_2' ).style.display = 'none';
		}
	</script>
	<div style="font-size: 15px; font-family: Times;">
	<xsl:apply-templates select="*"/>
	</div>
</xsl:template>

<xsl:template match="*">
	<div style="padding-left: 20px;">
		<xsl:choose>
			<xsl:when test="count( ./* ) = 0 and . = ''">
				<xsl:call-template name="single_empty_node">
					<xsl:with-param name="node" select="."/>
				</xsl:call-template>
			</xsl:when>
			<xsl:when test="count( ./* ) &gt; 0">
				<xsl:call-template name="many_node">
					<xsl:with-param name="node" select="."/>
				</xsl:call-template>
			</xsl:when>
			<xsl:otherwise>
				<xsl:call-template name="single_node">
					<xsl:with-param name="node" select="."/>
				</xsl:call-template>
			</xsl:otherwise>
		</xsl:choose>
	</div>
</xsl:template>

<xsl:template name="single_node">
	<xsl:param name="node"/>
	<xsl:variable name="id" select="generate-id($node)"/>
<!--	<a class="node" href="#" id="{$id}" onclick="ToggleNode( this );">
		<div id="{$id}_1" class="inline">&lt;<font class="tag"><xsl:value-of select="name( $node )"/></font><xsl:apply-templates select="@*"/>&gt;</div><div id="{$id}_2" class="inline"><xsl:value-of select="."/></div><div id="{$id}_3" class="inline">&lt;/<font class="tag"><xsl:value-of select="name( $node )"/></font>&gt;</div>
	</a>
	<script>
		if( document.getElementById( '<xsl:value-of select="$id"/>_1').offsetTop != document.getElementById( '<xsl:value-of select="$id"/>_3').offsetTop )
		{
			document.getElementById( '<xsl:value-of select="$id"/>_1').style.display = 'block';
			document.getElementById( '<xsl:value-of select="$id"/>_2').style.display = 'block';
			document.getElementById( '<xsl:value-of select="$id"/>_3').style.display = 'block';
			document.getElementById( '<xsl:value-of select="$id"/>_2').style.paddingLeft = '20px';
		}
	</script>
-->
		<div class="inline">&lt;<font class="tag"><xsl:value-of select="name( $node )"/></font><xsl:apply-templates select="@*"/>&gt;</div><div class="inline"><xsl:value-of select="."/></div><div class="inline">&lt;/<font class="tag"><xsl:value-of select="name( $node )"/></font>&gt;</div>
</xsl:template>

<xsl:template name="single_empty_node">
	<xsl:param name="node"/>
	&lt;<font class="tag"><xsl:value-of select="name( $node )"/></font><xsl:apply-templates select="@*"/>/&gt;
</xsl:template>

<xsl:template name="many_node">
	<xsl:param name="node"/>
	&lt;<font class="tag"><xsl:value-of select="name( $node )"/></font><xsl:apply-templates select="@*"/>&gt;
		<xsl:apply-templates select="$node/*"/>
	&lt;/<font class="tag"><xsl:value-of select="name( $node )"/></font>&gt;
</xsl:template>

<xsl:template match="@*">&#xA0;<font class="attribute_name"><xsl:value-of select="name()"/></font>=<font class="attribute_value">"<xsl:value-of select="."/>"</font></xsl:template>

</xsl:stylesheet>