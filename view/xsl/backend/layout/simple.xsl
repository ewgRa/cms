<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="yes" encoding="utf-8" doctype-public = "-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system = "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

<xsl:template match="/DOCUMENT">
<html>
	<head>
		<xsl:apply-templates select="." mode="render_html_head"/>
    </head>
    <body>
		<xsl:for-each select="/DOCUMENT/*[@section=1]">
			<xsl:sort select="@position"/>
			<xsl:apply-templates select="."/>
		</xsl:for-each>
    </body>
</html>
</xsl:template>
</xsl:stylesheet>