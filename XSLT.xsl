<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="2.0">
	<xsl:output method="xml" doctype-system="operations.dtd" indent="yes" />
	<xsl:strip-space elements="*"/>
	
	<xsl:template match="/">
		<liste-cross>
			<xsl:apply-templates select="secours-en-mer/liste-cross/cross"/>
		</liste-cross>
	</xsl:template>
	<xsl:template match="cross">
		<xsl:variable name="op" select="/secours-en-mer/liste-opérations/opération[./coordination/@cross eq current()/@xml:id and ./jour/@vacances_scolaires='True' and ./vent/@force > '3']"/>
		<cross nom="{@nom}" nb="{count($op)}">
		    <lundi victimes = "{sum($op[./jour/@jour eq 'Lundi']/bilan-humain/@nombre)}"/>
		    <mardi victimes = "{sum($op[./jour/@jour eq 'Mardi']/bilan-humain/@nombre)}"/>
		    <mercredi victimes = "{sum($op[./jour/@jour eq 'Mercredi']/bilan-humain/@nombre)}"/>
		    <jeudi victimes = "{sum($op[./jour/@jour eq 'Jeudi']/bilan-humain/@nombre)}"/>
		    <vendredi victimes = "{sum($op[./jour/@jour eq 'Vendredi']/bilan-humain/@nombre)}"/>
		    <samedi victimes = "{sum($op[./jour/@jour eq 'Samedi']/bilan-humain/@nombre)}"/>
		    <dimanche victimes = "{sum($op[./jour/@jour eq 'Dimanche']/bilan-humain/@nombre)}"/>
		</cross>
	</xsl:template>
</xsl:stylesheet>