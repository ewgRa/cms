<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="yes" encoding="utf-8" doctype-public = "-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system = "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

<xsl:template match="/DOCUMENT">
<html>
<head>
  <title>Debug</title>
	<link rel="stylesheet" type="text/css" href="/cms/css/all/table.css"/>
	<link rel="stylesheet" type="text/css" href="/lib/j/ext/resources/css/ext-all.css"/>
	<script type="text/javascript" src="/lib/j/ext/adapter/yui/yui-utilities.js"></script>
	<script type="text/javascript" src="/lib/j/ext/adapter/yui/ext-yui-adapter.js"></script>
    <script type="text/javascript" src="/lib/j/ext/ext-all.js"></script>
    <style type="text/css">
	html, body {
        font:normal 12px verdana;
        margin:0;
        padding:0;
        border:0 none;
        overflow:hidden;
        width: 100%;
        background-color: #BBD4F6;
    }
    .x-layout-panel{
        border:0px none;
    }
    
    
    #EngineEcho
    {
    	background-color: white;
    }    
    
    .x-grid-row td
    {
    	white-space:normal;
    	vertical-align: middle;
    }
    
    
	</style>
	<script type="text/javascript">
		var Tabs = {
			init : function(){
				var tabs = new Ext.TabPanel('tabs');
				tabs.addTab('EngineEcho', "Engine Echo");
				tabs.addTab('Database', "Database (<xsl:value-of select="count(DEBUG/DB/ITEM)"/>)");
				tabs.addTab('XML', "XML");
				tabs.addTab('XSLT', "XSLT");
				tabs.addTab('Events', "Events");
				tabs.activate('EngineEcho');
			}
		}
		Ext.EventManager.onDocumentReady(Tabs.init, Tabs, true);
	</script>
</head>
<body>
<div id ="container">
	<center>
	<div id="content" style="width: 1000px; text-align: left;">
		<div id="tabs">
			<div id="EngineEcho" class="tab-content">
				<div style="width: 100%; position: absolute; text-align:right; font-size: 90%;"><div style="margin-right: 20px">Generation time: <xsl:value-of select="format-number( DEBUG/GENERATIONTIME*100, '0' )"/> ms</div></div>
				<div style="overflow: auto; height: 480px;padding: 5px 10px 17px 10px;">
					<xsl:value-of select="DEBUG/ENGINEECHO" disable-output-escaping="yes"/>
				</div>
			</div>
			<div id="Database" class="tab-content" style="width: 1000px;">
				<div id="Database-grid-panel" style="height: 480px;width: 1000px;">
					<div style="background-color: white; height: 100%; overflow: auto;">
						<table width="1000" class="default" cellspacing="0" cellpadding="0" border="0" style="font-size: 16px;">
							<tr class="header">
								<td width="50">#</td>
								<td>Query</td>
								<td width="100">Time</td>
								<td width="200">Script</td>
							</tr>
							<xsl:for-each select="DEBUG/DB/ITEM">
							<tr>
								<xsl:if test="position() mod 2 = 1">
									<xsl:attribute name="class">btw</xsl:attribute>
								</xsl:if>
								<td align="center"><xsl:value-of select="position()"/></td>
								<td><xsl:value-of select="SQL"/></td>
								<td align="center"><xsl:value-of select="format-number( TIME*100, '0' )"/> ms</td>
								<td style="height: 100%"><div style="overflow: hidden; height: 100%;" title="{SCRIPT}"><xsl:value-of select="SCRIPT"/></div></td>
							</tr>
							</xsl:for-each>
						</table>							
					</div>
				</div>
			</div>
			<div id="XML" class="tab-content">
				<div style="height: 480px; width: 100%;overflow: hidden;background-color: white;">
					<xsl:value-of select="DEBUG/XML" disable-output-escaping="yes"/>
					<iframe id="xml_frame" name="xml_frame" src="about:blank" style="display: none; border: none; height: 100%; width:100%;"/>
				</div>
			</div>
			<div id="XSLT" class="tab-content">
				<div style="height: 480px; width: 100%;overflow: hidden;background-color: white;">
					<xsl:value-of select="DEBUG/XSLT" disable-output-escaping="yes"/>
					<iframe id="xsl_frame" name="xsl_frame" src="about:blank" style="display: none; border: none; height: 100%; width:100%;"/>
				</div>
			</div>
			<div id="Events" class="tab-content">
				<div style="height: 480px; width: 990px;overflow: auto;background-color: white; padding: 4px 4px 4px 4px;">
					<pre><xsl:value-of select="DEBUG/EVENTS" disable-output-escaping="yes"/></pre>
				</div>
			</div>
		</div>
	</div>
	</center>
</div>
 </body>
</html>
</xsl:template>
</xsl:stylesheet>