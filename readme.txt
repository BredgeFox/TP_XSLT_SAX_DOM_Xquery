Le scripts et le programme présent dans ce répertoire effectue une redirection du résultat dans un fichier liste-cross.xml 
Vous pouvez connaitre le temps d'exécution de ces dernier en utilisant la commande time.
Pour utiliser la commande time il suffi de la mettre en préfixe aux commandes listé ci-dessous.

En prérequis à l'exécution de ce script et de ce programme il vous faut ajouter au dossier le fichier xml source "cross.xml"

Pour exécuter Xquery vous pouvez utiliser la commande :
java -cp saxon9he.jar net.sf.saxon.Query -q:Xquery.xq > liste-cross.xml
ou bien
./Xquery.sh
afin d'exécuter le script shell

Auteur :
Matthias ROBERT
Quentin AUBERT

Documents élaborés dans le cadre du parcour de formation de première année de MASTER MIAGE délivré par L'UFR des sciences et technique de Nantes métropole 