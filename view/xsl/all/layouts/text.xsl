<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="text" indent="yes" encoding="utf-8"/>

<xsl:template match="DOCUMENT">
	<xsl:for-each select="/DOCUMENT/*[@section=1]">
		<xsl:sort select="@position"/>
		<xsl:apply-templates select="."/>
	</xsl:for-each>
</xsl:template>

</xsl:stylesheet>