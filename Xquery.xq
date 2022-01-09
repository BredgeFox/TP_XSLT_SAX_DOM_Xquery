xquery version "1.0";
declare namespace output = "http://www.w3.org/2010/xslt-xquery-serialization";
declare option output:method "xml";
declare option output:media-type "text/xml";
declare option output:omit-xml-declaration "no";
declare option output:indent "yes";
declare option output:doctype-system "operations.dtd";

<liste-cross>
{
	let $racine := doc("cross.xml")/secours-en-mer
  	for $c in $racine/liste-cross/cross
	let $op := $racine/liste-opérations/opération[./coordination/@cross eq $c/@xml:id and ./jour/@vacances_scolaires='True' and ./vent/@force > '3']
  	let $nb := count($op)
	return
  		<cross nom="{$c/@nom}" nb="{$nb}">
			<Lundi victimes="{sum($op[./jour/@jour eq 'Lundi']/bilan-humain/@nombre)}"/>
			<Mardi victimes="{sum($op[./jour/@jour eq 'Mardi']/bilan-humain/@nombre)}"/>
			<Mercredi victimes="{sum($op[./jour/@jour eq 'Mercredi']/bilan-humain/@nombre)}"/>
			<Jeudi victimes="{sum($op[./jour/@jour eq 'Jeudi']/bilan-humain/@nombre)}"/>
			<Vendredi victimes="{sum($op[./jour/@jour eq 'Vendredi']/bilan-humain/@nombre)}"/>
			<Samedi victimes="{sum($op[./jour/@jour eq 'Samedi']/bilan-humain/@nombre)}"/>
			<Dimanche victimes="{sum($op[./jour/@jour eq 'Dimanche']/bilan-humain/@nombre)}"/>
		</cross>
}
</liste-cross>
