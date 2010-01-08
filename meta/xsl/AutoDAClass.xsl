<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="phpdoc.xsl" />

<xsl:output method="text" indent="yes" encoding="utf-8"/>

<xsl:template match="/*">&lt;?php
	/* $Id */
	
	<xsl:call-template name="classPhpDoc">
		<xsl:with-param name="wholeDoc" select="1" />
	</xsl:call-template>
	abstract class Auto<xsl:value-of select="name()" />DA extends <xsl:value-of select="@DAExtends" />
	{
		protected $tableAlias = '<xsl:value-of select="name()" />';
		
		/**
		 * @return <xsl:value-of select="name()" />
		 */
		protected function build(array $array)
		{
			return
				<xsl:value-of select="name()" />::create()-><xsl:for-each select="*[not(@relation)]">
				<xsl:variable name="preValue">
					<xsl:choose>
						<xsl:when test="@type='array'">$array['<xsl:value-of select="@downSeparatedName" />'] ? unserialize($array['<xsl:value-of select="name()" />']) : null</xsl:when>
						<xsl:when test="@type='boolean'">$array['<xsl:value-of select="@downSeparatedName" />'] == 1</xsl:when>
						<xsl:when test="@type"><xsl:value-of select="@type" />::create($array['<xsl:value-of select="@downSeparatedName" />'])</xsl:when>
						<xsl:otherwise>$array['<xsl:value-of select="@downSeparatedName" />']</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				<xsl:variable name="value">
					<xsl:choose>
						<xsl:when test="@replaceVariables">Config::me()->replaceVariables(<xsl:value-of select="$preValue" />)</xsl:when>
						<xsl:otherwise><xsl:value-of select="$preValue" /></xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				<xsl:variable name="endLine"><xsl:if test="position() != last()">-></xsl:if><xsl:if test="position() = last()">;</xsl:if></xsl:variable>
					set<xsl:value-of select="@upperName" />(<xsl:value-of select="$value"/>)<xsl:value-of select="$endLine" />
</xsl:for-each>
		}
	}
?&gt;</xsl:template>
</xsl:stylesheet>