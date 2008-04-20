<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes" encoding="utf-8"/>

<xsl:template match="ADMINCONTENT[@mode='content_edit']">
	<xsl:for-each select="DATA/ITEM">
		<textarea style="display: none" id="content_data[{LANGUAGE_ID}]"><xsl:value-of select="TEXT"/></textarea>
	</xsl:for-each>
	<script>
		var LanguageAbbr = '<xsl:value-of select="/DOCUMENT/LOCALIZER/ABBR"/>';
		<xsl:for-each select="/DOCUMENT/ADMINLOCALIZER[@mode='get_language_list']/DATA/ITEM">
			Localizer.AddLanguage( '<xsl:value-of select="ID"/>', '<xsl:value-of select="ABBR"/>' );
		</xsl:for-each>
	</script>
	<form method="post" id="edit_form" enctype="multipart/form-data">
		<input id="content_id" type="hidden" name="id" value="{DATA/ITEM/ID}"/>
		<input id="language_id" type="hidden" name="language_id" value="{/DOCUMENT/LOCALIZER/ID}"/>
		<textarea name="text"><xsl:value-of select="DATA/ITEM[LANGUAGE_ID = /DOCUMENT/LOCALIZER/ID]/TEXT"/></textarea>
	</form>
</xsl:template>

</xsl:stylesheet>