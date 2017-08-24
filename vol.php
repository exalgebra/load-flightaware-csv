<?php
$debug=0;
// transforme le journal d'un vol en fichier csv ˆ la norme "de julien" : lat log alt
include_once('simplehtmldom_1_5/simple_html_dom.php');

if(isset($_GET['id']))
    $urlhtml=$_GET['id'];
    else
    die('parametre id vide');
//$urlhtml = 'http://fr.flightaware.com/live/flight/AFR454/history/20160206/2220Z/LFPG/SBGR/tracklog/';
echo "vol suivi $urlhtml <br>"; 
if(strpos($urlhtml,"flightaware")<1)
    die("ne fonctionne que pour les url flightaware");
$html = file_get_html($urlhtml);

$vol=array();
$ligne = -1;

// charge les 8 colonnes du tableau de la page html dans un array
foreach($html->find('tr') as $e)
    {
        foreach ($e->find('td') as $l)
            {
                //echo $l->outertext . "<br>\n";
               if( $ligne>0)
                  {
                    $ordre++;
                    if($ordre>9){ $ligne ++; $ordre=0;}
                    $vol[$ligne][$ordre]=$l->innertext;
                  }
                
                if( strpos($l->innertext,"la porte d'embarquement")>0)
                    {
                    $ligne =1;
                    $ordre=-4; //echo "<br> \n Debut journal de vol";
                    }
                if( strpos($l->innertext,"Taxi Time")>0)
                    {
                    $ligne =1;
                    $ordre=-6;
                    if($debug)echo "<br> \n Taxi Time";
                    }
            }
    }
$exportvol = array();
$ligne_export=0;
$fin=0;

//recupere uniquement les trois colonnes dont on a besoin
$contenucsv="";
  foreach($vol as $ligne)
    {

        if($fin<1)
        {
            $exportvol[0]= $ligne[2];
            $exportvol[1]= $ligne[3];
            $exportvol[2]= $ligne[8];
             
            if($exportvol[2]=="")$exportvol[2] = $lastalt; //en cas d'abscence d'indication d'altitude on cosnerve la veleur precedente
            $laligne=implode($ligne);

            if(strpos($laligne,"FlightAware Estimated" )>0)$fin=1;
            if($fin<1)$contenucsv .= ''. $exportvol[1].','.$exportvol[0].','.str_replace('.','', $exportvol[2]).''."\n";
            
            $lastalt = $exportvol[2];
        }
        
    }

//stocke dans u nfichier telechargeable
$nom_fichier = "vol".time().".csv";
file_put_contents("vols/".$nom_fichier,$contenucsv);
echo "Fichier a charger : <a href=http://flight.caribara.com/vols/$nom_fichier> $nom_fichier</a> ";
  
?>
