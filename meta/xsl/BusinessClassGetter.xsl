<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="text" indent="yes" encoding="utf-8"/>

<xsl:template name="classGetter">
		<xsl:if test="@type">
		/**
		 * @return <xsl:value-of select="@type" />
		 */</xsl:if>
		public function get<xsl:value-of select="@upperName" />()
		{<xsl:choose>
		<xsl:when test="not(@relation)">
		<xsl:if test="not(@null)">
			Assert::isNotNull($this-><xsl:value-of select="name()" />);</xsl:if>
			return $this-><xsl:value-of select="name()" />;
		</xsl:when>
		<xsl:otherwise>
			return <xsl:value-of select="@type" />::da()->getById($this->get<xsl:value-of select="@upperName" />Id());
		</xsl:otherwise>
		</xsl:choose>
		<xsl:if test="@type = 'boolean'">}
		
		public function is<xsl:value-of select="@upperName" />()
		{
			return ($this->get<xsl:value-of select="@upperName" />() === true);
		</xsl:if>
		<xsl:choose>
			<xsl:when test="position() != last()">}
		</xsl:when>
			<xsl:otherwise>}</xsl:otherwise>
		</xsl:choose>
</xsl:template>
</xsl:stylesheet>