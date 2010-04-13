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
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!<xsl:call-template name="classPhpDoc" />
	 */
	abstract class Auto<xsl:value-of select="name()" />DA extends <xsl:value-of select="/meta/@DAExtends" />
	{
		protected $tableAlias = '<xsl:value-of select="name()" />';
		
		/**
		 * @return <xsl:value-of select="name()" />
		 */		
		public function insert(<xsl:value-of select="name()" /> $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			<xsl:for-each select="properties/*[not(@relation) and name() != 'id']">
			if (!is_null($object->get<xsl:value-of select="@upperName" />())) {<xsl:variable name="preValue">$object->get<xsl:value-of select="@upperName" />()</xsl:variable><xsl:variable name="value">
					<xsl:choose>
						<xsl:when test="@type='array'">serialize(<xsl:value-of select="$preValue" />)</xsl:when>
						<xsl:when test="@type='boolean'"><xsl:value-of select="$preValue" /></xsl:when>
						<xsl:when test="@type"><xsl:value-of select="$preValue" />->getId()</xsl:when>
						<xsl:otherwise><xsl:value-of select="$preValue" /></xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				$queryParts[] = '<xsl:value-of select="@downSeparatedName" /> = ?';
				$queryParams[] = <xsl:value-of select="$value" />;
			}
			</xsl:for-each>
			$dbQuery .= join(', ', $queryParts);
			$this->db()->query($dbQuery, $queryParams);
			<xsl:if test="count(properties/*[name() = 'id']) &gt; 0">
			$object->setId($this->db()->getInsertedId());
			</xsl:if>
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return Auto<xsl:value-of select="name()" />DA
		 */		
		public function save(<xsl:value-of select="name()" /> $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			<xsl:for-each select="properties/*[not(@relation) and name() != 'id']">
				<xsl:variable name="preValue">$object->get<xsl:value-of select="@upperName" />()</xsl:variable><xsl:variable name="value">
					<xsl:choose>
						<xsl:when test="@type='array'">serialize(<xsl:value-of select="$preValue" />)</xsl:when>
						<xsl:when test="@type"><xsl:value-of select="$preValue" />->getId()</xsl:when>
						<xsl:otherwise><xsl:value-of select="$preValue" /></xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
			$queryParts[] = '<xsl:value-of select="@downSeparatedName" /> = ?';
			$queryParams[] = <xsl:value-of select="$value" />;
			</xsl:for-each>
			$whereParts = array();
			<xsl:for-each select="properties/*[not(@relation) and name() = 'id']">
				<xsl:variable name="preValue">$object->get<xsl:value-of select="@upperName" />()</xsl:variable><xsl:variable name="value">
					<xsl:choose>
						<xsl:when test="@type='array'">serialize(<xsl:value-of select="$preValue" />)</xsl:when>
						<xsl:when test="@type"><xsl:value-of select="$preValue" />->getId()</xsl:when>
						<xsl:otherwise><xsl:value-of select="$preValue" /></xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
			$whereParts[] = '<xsl:value-of select="@downSeparatedName" /> = ?';
			$queryParams[] = <xsl:value-of select="$value" />;
			</xsl:for-each>
			$dbQuery .= join(', ', $queryParts). ' WHERE '.join(' AND ', $whereParts);
			$this->db()->query($dbQuery, $queryParams);
			 
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return <xsl:value-of select="name()" />
		 */
		protected function build(array $array)
		{
			return
				<xsl:value-of select="name()" />::create()-><xsl:for-each select="properties/*[not(@relation)]">
				<xsl:variable name="createFunction">
					<xsl:choose>
						<xsl:when test="@createFunction"><xsl:value-of select="@type" />::<xsl:value-of select="@createFunction" /></xsl:when>
						<xsl:otherwise><xsl:value-of select="@type" />::create</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				<xsl:variable name="value">
					<xsl:choose>
						<xsl:when test="@type='array'">$array['<xsl:value-of select="@downSeparatedName" />'] ? unserialize($array['<xsl:value-of select="@downSeparatedName" />']) : null</xsl:when>
						<xsl:when test="@type='boolean'">$array['<xsl:value-of select="@downSeparatedName" />'] == 1</xsl:when>
						<xsl:when test="@type"><xsl:value-of select="$createFunction" />($array['<xsl:value-of select="@downSeparatedName" />'])</xsl:when>
						<xsl:otherwise>$array['<xsl:value-of select="@downSeparatedName" />']</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>
				<xsl:variable name="endLine"><xsl:if test="position() != last()">-></xsl:if><xsl:if test="position() = last()">;</xsl:if></xsl:variable>
					set<xsl:value-of select="@upperName" />(<xsl:value-of select="$value"/>)<xsl:value-of select="$endLine" />
</xsl:for-each>
		<xsl:variable name="name" select="name()" />
		}<!--<xsl:variable name="relationClasses" select="/meta/*[properties/*[@type=$name and string-length(@relation)]]" /><xsl:if test="count($relationClasses)">
		
		public function dropCache()
		{
			<xsl:for-each select="$relationClasses">
			<xsl:value-of select="name()" />::da()->dropCache();
			</xsl:for-each>			
			return parent::dropCache();
		}
</xsl:if>
-->
	}
?&gt;</xsl:template>
</xsl:stylesheet>