<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="xml" indent="yes" encoding="utf-8" omit-xml-declaration="yes"/>

<xsl:template match="/document">
	<html>
		<head>
			<style>
				h2
				{
					margin: 0;
				}
				
				.table
				{
				}
				
				.table .name
				{
					padding-right: 10px;
				}
				
				.table td h3
				{
					padding: 0;
					margin: 0;
					padding-top: 4px;
				}
			</style>
			
			<script>
				function toggleBlock(id)
				{
					var elem = document.getElementById(id);
					if(elem.style.display == 'none')
						elem.style.display = '';
					else
						elem.style.display = 'none';
				}
			</script>
		</head>
		<body>
			<div class="navigation">
				<xsl:choose>
					<xsl:when test="(pointer - 1) &gt;= 0">
						<a href="?pointer={pointer - 1}">Next</a>
					</xsl:when>
					<xsl:otherwise>Next</xsl:otherwise>
				</xsl:choose>
				 | 
				<xsl:choose>
					<xsl:when test="(pointer + 1) &lt; countItems">
						<a href="?pointer={pointer + 1}">Prev</a>
					</xsl:when>
					<xsl:otherwise>Prev</xsl:otherwise>
				</xsl:choose>
			</div>
			
			<div class="request">
				<h2><a href="#" onclick="toggleBlock('request'); return false;">Request</a></h2>
				<table id="request" class="table" border="0" cellspacing="0" cellpadding="0" style="display:none">
				<xsl:for-each select="item[type=4]/data/*">
					<xsl:if test="count(*)">
						<tr>
							<td colspan="2"><h3><xsl:value-of select="name()"/></h3></td>
						</tr>
						<xsl:for-each select="*">
							<tr>
								<td class="name"><xsl:value-of select="name()" /></td>
								<td><xsl:value-of select="." /></td>
							</tr>
						</xsl:for-each>
					</xsl:if>
				</xsl:for-each>
				</table> 
			</div>
			<hr/>
			<div class="page">
				<h2>Page</h2>
				<xsl:if test="count(item[type=3]/data) &gt; 0">
					<div>Id: <xsl:value-of select="item[type=3]/data/id"/></div>
					<div>Path: <xsl:value-of select="item[type=3]/data/path"/></div>
					<div>Layout id: <xsl:value-of select="item[type=3]/data/layoutId"/></div>
					<div>Created for <xsl:value-of select="format-number(item[type=3]/endTime - item[type=3]/startTime, '0.00')"/></div>
					<br/>
				</xsl:if>
			</div>
			<hr/>
			<div class="engineEcho">
				<h2>Engine echo</h2>
				<xsl:if test="string-length(item[type=1]/data) &gt; 0">
					<div><xsl:value-of select="item[type=1]/data" disable-output-escaping="yes"/></div>
					<br/>
				</xsl:if>
				All executed for <xsl:value-of select="format-number(item[type=1]/endTime - item[type=1]/startTime, '0.00')"/>
			</div>
			<hr/>
			<div class="databaseQueries">
				<h2>Database queries</h2>
				<xsl:choose>
					<xsl:when test="count(item[type=2]) = 0">
						No queries
					</xsl:when>
					<xsl:otherwise>
						<table>
							<xsl:for-each select="item[type=2]">
								<tr>
									<td valign="top"><xsl:value-of select="position()"/>.</td>
									<td valign="top"><xsl:value-of select="data"/></td>
									<td valign="top"><xsl:value-of select="format-number(endTime - startTime, '0.00')"/></td>
								</tr>
							</xsl:for-each>
						</table>
					</xsl:otherwise>
				</xsl:choose>
			</div>
		</body>
	</html>
</xsl:template>

</xsl:stylesheet>