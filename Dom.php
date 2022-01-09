<?php header('Content-type: text/xml; Encoding: utf-8');
$doc = new DOMDocument();
$doc->preserveWhiteSpace = false;
$doc->validateOnParse  = true;
$doc->load("cross.xml");
$tab;

//Génération du résultat
$xml = "<?xml version='1.0' encoding='UTF-8' ?>\n"; //<?php
$xml .= "<!DOCTYPE liste-cross SYSTEM 'operations.dtd'>\n";
$xml .= "<liste-cross>";

$e = $doc->documentElement->firstChild->firstChild;
while(($e instanceOf DOMELEMENT) && ($e->tagName == 'cross'))//Parcours de la liste-cross
{
  $index = $e->getAttribute("xml:id");
  $tab[$index][0] = $e->getAttribute("nom");//Nom
  $tab[$index][1] = 0;//Total opérations
  $tab[$index][2] = 0;//Total victimes Lundi
  $tab[$index][3] = 0;//Total victimes Mardi
  $tab[$index][4] = 0;//Total victimes Mercredi
  $tab[$index][5] = 0;//Total victimes Jeudi
  $tab[$index][6] = 0;//Total victimes Vendredi
  $tab[$index][7] = 0;//Total victimes Samedi
  $tab[$index][8] = 0;//Total victimes Dimanche
  $e = $e->nextSibling;//Cross suivante
}
$e = $doc->documentElement->firstChild->nextSibling->firstChild;
while(($e instanceOf DOMELEMENT) && ($e->tagName == 'opération'))//Parcours de la liste-opérations
{
  $lc = $e->lastChild->tagName;
  //Début d'operation
  $ochild = $e->firstChild;
  $vac = false;
  $vent = false;
  $bh = false;
  $temp = NULL;//Reset tableau
  while(($ochild instanceOf DOMELEMENT) && ($ochild->tagName != $lc))//Parcours des fils de l'opération
  {
    switch($ochild->tagName)
    {
      case 'jour' :
        if($ochild->getAttribute('vacances_scolaires') == 'True')
        {
          $temp[0] = $ochild->getAttribute('jour');
          $vac = true;
        }
        break;
      case 'vent' :
        if($vac && $ochild->getAttribute('force') > '3')
        {
          $vent = true;
        }
        break;
      case 'bilan-humain' :
        if($vac && $vent)
        {
          $temp[1] = $ochild->getAttribute('nombre');
          $bh = true;
        }
        break;
      case 'coordination' :
        if($vac && $vent)
        {
          //Enregistrement
          $tab[$ochild->getAttribute('cross')][1] = $tab[$ochild->getAttribute('cross')][1] + 1;//Total d'opération

          if($bh)
          {
            switch ($temp[0])
            {
              case 'Lundi':
                $tab[$ochild->getAttribute('cross')][2] = $tab[$ochild->getAttribute('cross')][2] + $temp[1];//Somme victimes Lundi
                break;
              case 'Mardi':
                $tab[$ochild->getAttribute('cross')][3] = $tab[$ochild->getAttribute('cross')][3] + $temp[1];//Somme victimes Mardi
                break;
              case 'Mercredi':
                $tab[$ochild->getAttribute('cross')][4] = $tab[$ochild->getAttribute('cross')][4] + $temp[1];//Somme victimes Mercredi
                break;
              case 'Jeudi':
                $tab[$ochild->getAttribute('cross')][5] = $tab[$ochild->getAttribute('cross')][5] + $temp[1];//Somme victimes Jeudi
                break;
              case 'Vendredi':
                $tab[$ochild->getAttribute('cross')][6] = $tab[$ochild->getAttribute('cross')][6] + $temp[1];//Somme victimes Vendredi
                break;
              case 'Samedi':
                $tab[$ochild->getAttribute('cross')][7] = $tab[$ochild->getAttribute('cross')][7] + $temp[1];//Somme victimes Samedi
                break;
              case 'Dimanche':
                $tab[$ochild->getAttribute('cross')][8] = $tab[$ochild->getAttribute('cross')][8] + $temp[1];//Somme victimes Dimanche
                break;
            }
          }
          //Fin Enregistrement
        }
        break;
    }
    $ochild = $ochild->nextSibling;//Fils d'opération suivant
  }
  //Fin d'operation
  $e = $e->nextSibling;//Opération suivante
}

foreach ($tab as $key => $value)
{
  //Début cross
  $xml .= "
  <cross nom='".$tab[$key][0]."' nb='".$tab[$key][1]."'>
    <Lundi victimes='".$tab[$key][2]."'/>
    <Mardi victimes='".$tab[$key][3]."'/>
    <Mercredi victimes='".$tab[$key][4]."'/>
    <Jeudi victimes='".$tab[$key][5]."'/>
    <Vendredi victimes='".$tab[$key][6]."'/>
    <Samedi victimes='".$tab[$key][7]."'/>
    <Dimanche victimes='".$tab[$key][8]."'/>
  </cross>";
  //Fin cross
}

$xml .= "\n<liste-cross>";//Fin liste-cross
echo $xml;

//Ecriture du résultat dans le fichier liste-cross.xml
$file = fopen("liste-cross.xml", "w");
fwrite($file,$xml);
fclose($file);
?>