<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

echo CHtml::link("Créer un nouveau trajet", array('rides/create'));
?>

<hr/>

<?php
$r[0]=0;
foreach($rides as $ride)
{
    $r[$ride->id]=0;
}
echo "<table>";
echo "<tr><th>Conducteur</th><th>Places</th><th>Départ</th><th>Arrivée</th><th>Jour</th></tr>";
$i=0;
$date=$datetime = date('Y-m-d 00:00:00', time());
while($i<20 && array_sum($r)<count($rides))
{
    foreach ($rides as $ride) {

            if($ride->visibility==1 && $ride->startDate<=$date && $ride->endDate>=$date && $ride->day==date('N',strtotime($date)) && $r[$ride->id]==0 && $ride->showDuringHolidays($date)){
                $daydate = date("d-m-Y",strtotime($date));
                echo "<tr onclick=";
                echo "\"document.location='/covoiturage/covoiturage/rides/".$ride->id."?date=".$daydate."';";
                echo "\" onmouseover='tablein(this);' onmouseout='tableout(this);'>";

                    echo "<td>".$ride->driver->cpnvId."</td>";
                    echo "<td>"."0/".$ride->seats."</td>";
                    echo "<td>".$ride->departuretown->name." à ".substr($ride->departure, 0, 5)."</td>";
                    echo "<td>".$ride->arrivaltown->name." vers ".substr($ride->arrival, 0, 5)."</td>";
                
                    switch ($ride->day) {
                            case '1':
                                $day = "Lundi";
                                break;
                            case '2':
                                $day = "Mardi";
                                break;
                            case '3':
                                $day = "Mercredi";
                                break;
                            case '4':
                                $day = "Jeudi";
                                break;
                            case '5':
                                $day = "Vendredi";
                                break;
                            case '6':
                                $day = "Samedi";
                                break;
                            case '7':
                                $day = "Dimanche";
                                break;
                            default:
                                $day = "?";
                                break;
                        }
                    echo "<td>".$day." ".$daydate."</td>";
                echo "</tr>";
                $i++;
            }
            if($ride->endDate<$date)
            {
                $r[$ride->id]=1;
            }

    }
    $date=date('Y-m-d 00:00:00', strtotime($date.' +1 day'));
}
echo "</table>";

/*
//afficher 20 trajets en comptant les récurrences

i=0 //dès qu'une ligne est affichée, augmente de 1
j=date //commence à aujourd'hui, à chaque while, augmente d'un jour
while i<=20
    pour tout les rides en tant que ride    //on parcours tous les rides transmis par le controlleur
        si ride->startdate<=j && ride.joursemaine=j.joursemaine //afin de n'afficher que les rides voulus
            affiche le ride
        end si
    end pour tout
    i++
    j=j+1 jour
endwhile
*/

?>
<script type="text/javascript">
function tablein(that){
    that.style.backgroundColor="#E5F1F4";
    that.style.cursor="pointer";
}
function tableout(that){
    that.style.backgroundColor="white";
    that.style.cursor="auto";
}
</script>