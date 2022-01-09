<?php header('Content-type: text/xml; Encoding: utf-8');
$doc = new DOMDocument();
$doc->preserveWhiteSpace = false;
$doc->validateOnParse  = true;
$doc->load("cross.xml");

$racine = $doc->documentElement;
$xpath = new DOMXpath($doc);
$listeCross = $xpath->query("/secours-en-mer/liste-cross/cross");

//Generation du resultat
$xml = "<?xml version='1.0' encoding='UTF-8' ?>\n"; //<?php
$xml .= "<!DOCTYPE liste-cross SYSTEM “operations.dtd”>\n";
$xml .= "<liste-cross>\n";//Début liste-cross

foreach ($listeCross as $cross)
{
	$id = $cross->getAttribute("xml:id");
    $query = "count(liste-opérations/opération[./coordination/@cross = '".$id."' and ./jour/@vacances_scolaires='True' and ./vent/@force > '3'])";
    $nb = $xpath->evaluate(/*requête*/$query, /*noeud DOM racine du parcours*/ $racine);
    $xml .= "\t<cross nom='".$cross->getAttribute("nom")."' nb='".$nb."'>\n";//Début cross
    //LUNDI
        $query = "sum(liste-opérations/opération[./coordination/@cross = '".$id."' and ./jour/@vacances_scolaires='True' and ./vent/@force > '3'][./jour/@jour = 'Lundi']/bilan-humain/@nombre)";
        $xml .= "\t\t<Lundi victimes = '".$xpath->evaluate(/*requête*/$query, /*noeud DOM racine du parcours*/ $racine)."'/>\n";
    //MARDI
        $query = "sum(liste-opérations/opération[./coordination/@cross = '".$id."' and ./jour/@vacances_scolaires='True' and ./vent/@force > '3'][./jour/@jour = 'Mardi']/bilan-humain/@nombre)";
        $xml .= "\t\t<Mardi victimes = '".$xpath->evaluate(/*requête*/$query, /*noeud DOM racine du parcours*/ $racine)."'/>\n";
    //MERCREDI
        $query = "sum(liste-opérations/opération[./coordination/@cross = '".$id."' and ./jour/@vacances_scolaires='True' and ./vent/@force > '3'][./jour/@jour = 'Mercredi']/bilan-humain/@nombre)";
        $xml .= "\t\t<Mercredi victimes = '".$xpath->evaluate(/*requête*/$query, /*noeud DOM racine du parcours*/ $racine)."'/>\n";
    //JEUDI
        $query = "sum(liste-opérations/opération[./coordination/@cross = '".$id."' and ./jour/@vacances_scolaires='True' and ./vent/@force > '3'][./jour/@jour = 'Jeudi']/bilan-humain/@nombre)";
        $xml .= "\t\t<Jeudi victimes = '".$xpath->evaluate(/*requête*/$query, /*noeud DOM racine du parcours*/ $racine)."'/>\n";
    //VENDREDI
        $query = "sum(liste-opérations/opération[./coordination/@cross = '".$id."' and ./jour/@vacances_scolaires='True' and ./vent/@force > '3'][./jour/@jour = 'Vendredi']/bilan-humain/@nombre)";
        $xml .= "\t\t<Vendredi victimes = '".$xpath->evaluate(/*requête*/$query, /*noeud DOM racine du parcours*/ $racine)."'/>\n";
    //SAMEDI
        $query = "sum(liste-opérations/opération[./coordination/@cross = '".$id."' and ./jour/@vacances_scolaires='True' and ./vent/@force > '3'][./jour/@jour = 'Samedi']/bilan-humain/@nombre)";
        $xml .= "\t\t<Samedi victimes = '".$xpath->evaluate(/*requête*/$query, /*noeud DOM racine du parcours*/ $racine)."'/>\n";
    //DIMANCHE
        $query = "sum(liste-opérations/opération[./coordination/@cross = '".$id."' and ./jour/@vacances_scolaires='True' and ./vent/@force > '3'][./jour/@jour = 'Dimanche']/bilan-humain/@nombre)";
        $xml .= "\t\t<Dimanche victimes = '".$xpath->evaluate(/*requête*/$query, /*noeud DOM racine du parcours*/ $racine)."'/>\n";
    $xml .= "\t</cross>\n";//Fin cross
}

$xml .= "</liste-cross>";//Fin liste-cross
echo $xml;

//Ecriture du résultat dans le fichier liste-cross.xml
$file = fopen("liste-cross.xml", "w");
fwrite($file,$xml);
fclose($file);
?>