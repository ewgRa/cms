<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:import href="phpdoc.xsl" />
<xsl:import href="BusinessClassGetter.xsl" />
<xsl:import href="BusinessClassSetter.xsl" />

<xsl:output method="text" indent="yes" encoding="utf-8"/>

<xsl:template match="/*">&lt;?php
	/* $Id$ */
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!<xsl:call-template name="classPhpDoc" />
	 */
	class Auto<xsl:value-of select="name()" />
	{<xsl:for-each select="*[not(@relation)]">
		<xsl:if test="@type">
		/**
		 * @var <xsl:value-of select="@type" />
		 */</xsl:if>
		private $<xsl:value-of select="name()" /> = null;
		</xsl:for-each>
		/**
		 * @return <xsl:value-of select="name()" />DA
		 */
		public static function da()
		{
			return <xsl:value-of select="name()" />DA::me();
		}
		<xsl:if test="count(*[name()='id']) = 0 and count(*[@id]) &gt; 0">
		public function getId()
		{
			return <xsl:for-each select="*[@id]">$this->get<xsl:value-of select="@upperName" /><xsl:if test="@relation">Id</xsl:if>()<xsl:if test="position() != last()">.'_'.</xsl:if></xsl:for-each>;
		}
		</xsl:if><xsl:for-each select="*">
		<xsl:call-template name="classSetter" />
		<xsl:call-template name="classGetter" /></xsl:for-each>
	}
?&gt;</xsl:template>
</xsl:stylesheet>