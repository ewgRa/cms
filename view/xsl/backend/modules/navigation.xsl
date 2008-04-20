<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes" encoding="utf-8"/>

<xsl:template name="NAVIGATION_LANGUAGE">
	<xsl:if test="/DOCUMENT/LANGUAGE/ABBR = 'ru'"><strong>RU</strong></xsl:if><xsl:if test="/DOCUMENT/LANGUAGE/ABBR != 'ru'"><a href="/ru{/DOCUMENT/PAGE/REQUEST_URI}">RU</a></xsl:if> |
	<xsl:if test="/DOCUMENT/LANGUAGE/ABBR = 'en'"><strong>EN</strong></xsl:if><xsl:if test="/DOCUMENT/LANGUAGE/ABBR != 'en'"><a href="/en{/DOCUMENT/PAGE/REQUEST_URI}">EN</a></xsl:if>
</xsl:template>

<xsl:template match="NAVIGATION">
	<div>
		<xsl:attribute name="class">navigation<xsl:for-each select="PARAMS/ALIAS/ITEM">_<xsl:value-of select="."/></xsl:for-each></xsl:attribute>
		<ul>
			<xsl:for-each select="DATA/ITEM[PARENT_ID = '']">
				<li>
					<xsl:value-of select="NAME"/>
					<xsl:variable name="sub_nav" select="../ITEM[PARENT_ID = current()/ID]"/>
					<xsl:if test="count( $sub_nav )">
						<ul>
							<xsl:for-each select="$sub_nav">
								<li>
									<xsl:choose>
										<xsl:when test="/DOCUMENT/PAGE/URI = URL">
											<xsl:value-of select="NAME"/>
										</xsl:when>
										<xsl:otherwise>
											<a href="{/DOCUMENT/PAGE/LANGUAGE_URL}{URL}"><xsl:value-of select="NAME"/></a>
										</xsl:otherwise>
									</xsl:choose>
								</li>
							</xsl:for-each>
						</ul>
					</xsl:if>
				</li>
			</xsl:for-each>
		</ul>
		<div class="clear"/>
	</div>
</xsl:template>

</xsl:stylesheet>