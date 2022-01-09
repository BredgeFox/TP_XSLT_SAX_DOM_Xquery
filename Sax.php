<?php header('Content-type:text/html; Encoding:utf-8');
include('./Sax4PHP/Sax4PHP.php');

class SaxCross extends DefaultHandler
{
	public $vent; //bool
	public $boolbh; //bool
	public $nb_victimes; //int
	public $countC; //int
	public $cross; //Liste des cross String
	public $bh; //Bilan humain des opération par cross et par jour. INT
	public $pointeurC, $pointeurJ; //Pointeur de cross et pointeur de jour. INT

	function __construct()
	{
		parent::__construct();
	}

	function startDocument()
	{
		//Un problème surviens lors de l'exécution. Aucun passage par la fonction startDocument n'est effectué
		$this->countC = 0;
		$xml = "<?xml version='1.0' encoding='UTF-8' ?>\n"; //<?php
		$xml .= "<!DOCTYPE liste-cross SYSTEM 'operations.dtd'>\n";
		$xml .= "<liste-cross>\n";
	}

	function endDocument()
	{
		//Valeur par défaut en cas d'absence de données
		for ($i = 0; $i < $this->countC; $i++) {
			if (!isset($this->bh[$i][0])) {
				$this->bh[$i][0] = 0;
			}
			if (!isset($this->bh[$i][1])) {
				$this->bh[$i][1] = 0;
			}
			if (!isset($this->bh[$i][2])) {
				$this->bh[$i][2] = 0;
			}
			if (!isset($this->bh[$i][3])) {
				$this->bh[$i][3] = 0;
			}
			if (!isset($this->bh[$i][4])) {
				$this->bh[$i][4] = 0;
			}
			if (!isset($this->bh[$i][5])) {
				$this->bh[$i][5] = 0;
			}
			if (!isset($this->bh[$i][6])) {
				$this->bh[$i][6] = 0;
			}
			if (!isset($this->bh[$i][7])) {
				$this->bh[$i][7] = 0;
			}
			//Affichage du contenu des balises <liste-cross> ... </liste-cross>
			$xml .= "\t<cross nom=" . $this->cross[$i][0] . " nb=" . $this->bh[$i][0] . ">\n" .
				"\t\t<lundi victimes=" . $this->bh[$i][1] . "/>\n" .
				"\t\t<mardi victimes=" . $this->bh[$i][2] . "/>\n" .
				"\t\t<mercredi victimes=" . $this->bh[$i][3] . "/>\n" .
				"\t\t<jeudi victimes=" . $this->bh[$i][4] . "/>\n" .
				"\t\t<vendredi victimes=" . $this->bh[$i][5] . "/>\n" .
				"\t\t<samedi victimes=" . $this->bh[$i][6] . "/>\n" .
				"\t\t<dimanche victimes=" . $this->bh[$i][7] . "/>\n" .
				"\t</cross>\n";
		}
		$xml .= "</liste-cross>";//Fin liste-cross

		//Suite au non passage par la fonction startDocument() lors de l'execution report de l'entête du document
		$xml_start = "<?xml version='1.0' encoding='UTF-8' ?>\n"; //<?php
		$xml_start .= "<!DOCTYPE liste-cross SYSTEM 'operations.dtd'>\n";
		$xml_start .= "<liste-cross>\n";
		$xml = $xml_start.$xml;

		echo $xml;//Print du résultat dans le terminal

		//Ecriture du résultat dans le fichier liste-cross.xml
		$file = fopen("liste-cross.xml", "w");
		fwrite($file,$xml);
		fclose($file);
	}

	function startElement($nom, $att)
	{
		switch ($nom) {
			case "cross":
				$this->cross[$this->countC][0] = $att["nom"];
				$this->cross[$this->countC][1] = $att["xml:id"];

				$this->countC++;
				break;

			case "jour":
				//En fonction du jour de l'opération incrémentation
				if (isset($att["vacances_scolaires"]) && $att["vacances_scolaires"]  == "True") //Opération effectué en période de vacance
				{
					switch ($att["jour"]) {
						case "Lundi": //l'operation se déroule un lundi
							$this->pointeurJ = 1;
							break;

						case "Mardi": //l'operation se déroule un mardi
							$this->pointeurJ = 2;
							break;

						case "Mercredi": //l'operation se déroule un mercredi
							$this->pointeurJ = 3;
							break;

						case "Jeudi": //l'operation se déroule un jeudi
							$this->pointeurJ = 4;
							break;

						case "Vendredi": //l'operation se déroule un vendredi
							$this->pointeurJ = 5;
							break;

						case "Samedi": //l'operation se déroule un samedi
							$this->pointeurJ = 6;
							break;

						case "Dimanche": //l'operation se déroule un dimanche
							$this->pointeurJ = 7;
							break;
					}
				}
				break;

			case "coordination":
				if ($this->vent && (isset($this->pointeurJ) && $this->pointeurJ != 0)) {
					//Total de cross
					for ($i = 0; $i < $this->countC; $i++) {
						if ($att["cross"] == $this->cross[$i][1]) {
							$this->pointeurC = $i;
						}
					}

					if (!isset($this->bh[$this->pointeurC][0])) {
						//Valeur par défaut d'opération qui concerne le cross du pointeurC
						$this->bh[$this->pointeurC][0] = 0;
					}
					
					//Nombre total d'opération qui concerne le cross du pointeurC.
					$this->bh[$this->pointeurC][0] = $this->bh[$this->pointeurC][0] + 1;
					
					//Si il y a un bilan humain incrément de ce dernier au jour concerné dans la cross concerné.
					if ($this->boolbh) {
						if (!isset($this->bh[$this->pointeurC][$this->pointeurJ])) {
							$this->bh[$this->pointeurC][$this->pointeurJ] = 0;
						}
						$this->bh[$this->pointeurC][$this->pointeurJ] = $this->bh[$this->pointeurC][$this->pointeurJ] + $this->nb_victimes; // enregistrement du bilan humain de l'opération
					}
				}
				break;

			case "vent":
				if (isset($att["force"]) && $att["force"] > 3)
				{
					$this->vent = true;
				}
				break;

			case "bilan-humain":
				$this->boolbh = true;
				if(isset($att["nombre"]))
				{
					$this->nb_victimes = $att["nombre"];
				}
				else
				{
					$this->nb_victimes = 0;
				}
				break;
		}
	}

	function endElement($nom)
	{
		switch ($nom) {
			case "opération":
				//Reset des var pour la prochaine opération
				$this->pointeurJ = 0;
				$this->pointeurC = 0;
				$this->vent = false;
				$this->boolbh = false;
				break;
		}
	}
}

try
{
	$sax = new SaxParser(new SaxCross());
	$sax->parse('cross.xml');
}
catch (SAXException $e)
{
	echo "\n", $e;
}
catch (Exception $e)
{
	echo "Capture de l'exception par défaut\n", $e;
}
