<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="yes" encoding="utf-8" doctype-public = "-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system = "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

<xsl:template match="DOCUMENT" mode="render_html_head">
	<xsl:if test="/DOCUMENT/PAGE/TITLE != ''">
		<title><xsl:value-of select="/DOCUMENT/PAGE/TITLE"/></title>
	</xsl:if>
	<xsl:if test="/DOCUMENT/PAGE/DESCRIPTION != ''">
		<meta name="description" content="{/DOCUMENT/PAGE/DESCRIPTION}"/>
	</xsl:if>
	<xsl:if test="/DOCUMENT/PAGE/KEYWORDS != ''">
		<meta name="keywords" content="{/DOCUMENT/PAGE/KEYWORDS}"/>
	</xsl:if>
	<xsl:for-each select="PAGE/JAVASCRIPT_FILES/ITEM">
		<script language="javascript" src="{.}"></script>
	</xsl:for-each>
	<xsl:for-each select="PAGE/CSS_FILES/ITEM">
		<link rel="stylesheet" type="text/css" href="{.}"/>
	</xsl:for-each>
	<xsl:if test="count( /DOCUMENT/DICTIONARY/DATA/JS/* ) &gt; 0">
		<script language="javascript" src="/lib/j/dictionary.js"></script>
		<script>
			Dictionary.Set( { <xsl:for-each select="/DOCUMENT/DICTIONARY/DATA/JS/*">'<xsl:value-of select="name()"/>' : '<xsl:value-of select="."/>'</xsl:for-each> } );
		</script>
	</xsl:if>
</xsl:template>

</xsl:stylesheet>