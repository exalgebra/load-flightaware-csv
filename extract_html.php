<?php
include_once('simplehtmldom_1_5/simple_html_dom.php');


$html = file_get_html('http://flightaware.com/live/flight/AFR7700/history/20160129/0615Z/LFPG/LFMN/tracklog/');
//file_put_contents("test.html",$html);
//$html = file_get_contents("test.html");
foreach($html->find('tr') as $e)
    {
        foreach ($e->find('tr') as $l)
            {
                echo $l->innertext . "<br>fdtd\n";
                foreach ($l->find('td') as $m)
            {
                echo $m->innertext . "<br>fdtd\n TD3 \n";
                if( strpos($e->innertext,"la porte d'embarquement")>0) echo"ZZZZZZZZZZZZZZZZZ";
            }
            
            echo"YY \n QQQ \n";
            }
        echo "TD1 \n\n TD1 \n\n"; //$e->innertext . '<br>\n';
    }
    
?>