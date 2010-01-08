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
	final class <xsl:value-of select="name()" /> extends Auto<xsl:value-of select="name()" />
	{
		/**
		 * @return <xsl:value-of select="name()" />
		 */
		public static function create()
		{
			return new self;
		}
	}
?&gt;
</xsl:template>
</xsl:stylesheet>