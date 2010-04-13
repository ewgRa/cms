<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="phpdoc.xsl" />

<xsl:output method="text" indent="yes" encoding="utf-8"/>

<xsl:template match="/meta">
	<xsl:apply-templates select="*[name() = current()/className]" />
</xsl:template>


<xsl:template match="*">&lt;?php
	/* $Id */
	
	<xsl:call-template name="classPhpDoc">
		<xsl:with-param name="wholeDoc" select="1" />
	</xsl:call-template>
	final class <xsl:value-of select="name()" />DA extends Auto<xsl:value-of select="name()" />DA
	{
		/**
		 * @return <xsl:value-of select="name()" />DA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}
	}
?&gt;</xsl:template>
</xsl:stylesheet>