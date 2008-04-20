<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="yes" encoding="utf-8" doctype-public = "-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system = "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

<xsl:template match="/DOCUMENT">
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="/lib/j/ext2/resources/css/ext-all.css"/>
		<script type="text/javascript" src="/lib/j/ext2/adapter/ext/ext-base.js"></script>
		<script type="text/javascript" src="/lib/j/ext2/ext-all.js"></script>
		<script type="text/javascript" src="/cms/j/backend/ext_admin.js"></script>
		<xsl:apply-templates select="." mode="render_html_head"/>
	</head>
    <body>
		<div id="west">
			<xsl:for-each select="/DOCUMENT/*[@section=1]">
				<xsl:sort select="@position"/>
				<xsl:apply-templates select="."/>
			</xsl:for-each>
		</div>
		<div id="center" style="height: 100%">
			<xsl:for-each select="/DOCUMENT/*[@section=2]">
				<xsl:sort select="@position"/>
				<xsl:apply-templates select="."/>
			</xsl:for-each>
		</div>
    </body>
</html>
</xsl:template>
</xsl:stylesheet>