<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="text" indent="yes" encoding="utf-8"/>

<xsl:template name="classPhpDoc">
<xsl:param name="wholeDoc" select="0"/>
<xsl:if test="$wholeDoc">/*</xsl:if>
<xsl:if test="/meta/@license">
	 * @license <xsl:value-of select="/meta/@license" />
</xsl:if>
<xsl:if test="/meta/@author">
	 * @author <xsl:value-of select="/meta/@author" />
</xsl:if>
<xsl:if test="$wholeDoc">
	*/</xsl:if>
</xsl:template>
</xsl:stylesheet>