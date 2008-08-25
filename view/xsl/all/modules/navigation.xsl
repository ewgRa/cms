<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" encoding="utf-8"  omit-xml-declaration="yes"/>
<xsl:template match="document">
	<ul>
	<xsl:for-each select="item">
		<li><a href="{uri}"><xsl:value-of select="text" disable-output-escaping="yes"/></a></li>
	</xsl:for-each>
	</ul>
</xsl:template>
</xsl:stylesheet>