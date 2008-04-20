<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="yes" encoding="utf-8" doctype-public = "-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system = "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

<xsl:template match="GOOGLEANALYTICS[@mode='default']">
	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
		var pageTracker = _gat._getTracker("<xsl:value-of select="SETTINGS/TRACKER_ID"/>");
		pageTracker._initData();
		pageTracker._trackPageview();
	</script>
</xsl:template>

</xsl:stylesheet>