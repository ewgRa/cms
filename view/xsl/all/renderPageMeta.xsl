<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="xml" indent="yes" encoding="utf-8" omit-xml-declaration="yes"/>

<xsl:template match="/document">
    <xsl:if test="string-length(title) &gt; 0">
	<title><xsl:value-of select="title"/></title>
    </xsl:if>

    <xsl:if test="string-length(description) &gt; 0">
	<meta name="description" content="{description}"></meta>
    </xsl:if>

    <xsl:if test="string-length(keywords) &gt; 0">
	<meta name="keywords" content="{keywords}"/>
    </xsl:if>
</xsl:template>

</xsl:stylesheet>