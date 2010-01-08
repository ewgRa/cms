<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="text" indent="yes" encoding="utf-8"/>

<xsl:template name="classSetter"><xsl:if test="not(@relation)">
	<xsl:variable name="type">
		<xsl:if test="@type and @type != 'boolean'"><xsl:value-of select="@type" /> $</xsl:if>
		<xsl:if test="not(@type) or @type = 'boolean'">$</xsl:if>
	</xsl:variable>
	<xsl:variable name="defaultValue">
		<xsl:if test="@null and @type != 'boolean'"> = null</xsl:if>
		<xsl:if test="@type='boolean'"> = true</xsl:if>
	</xsl:variable>
	<xsl:variable name="value">
		<xsl:choose>
			<xsl:when test="@type='boolean'">($<xsl:value-of select="name()" /> === true)</xsl:when>
			<xsl:otherwise>$<xsl:value-of select="name()" /></xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
		/**
		 * @return Auto<xsl:value-of select="name(..)" />
		 */
		public function set<xsl:value-of select="@upperName" />(<xsl:value-of select="$type" /><xsl:value-of select="name()" /><xsl:value-of select="$defaultValue"></xsl:value-of>)
		{
			$this-><xsl:value-of select="name()" /> = <xsl:value-of select="$value" />;
			return $this;
		}
		</xsl:if></xsl:template>
</xsl:stylesheet>