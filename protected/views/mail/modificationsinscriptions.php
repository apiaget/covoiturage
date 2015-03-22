<?php 
	$start=date("d-m-Y",strtotime($ride->startDate));
	$end=date("d-m-Y",strtotime($ride->endDate));
	$villeDepart=$ride->departuretown->name;
	$villeArrivee=$ride->arrivaltown->name;
	$startHour=date("H:i",strtotime($ride->departure));
	$endHour=date("H:i",strtotime($ride->arrival));
	$user=$ride->driver->firstname;
    $jour=array('dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi');

	echo "Salut ".$user.",<br>";
//var_dump($registrations);
    if(empty($registrations)){
        echo "Un utilisateur s'est désinscrit de ton trajet";
    }else {
        echo "Un utilisateur a modifié ses inscritions à ton trajet";
    }

    echo "<br>Le trajet concerné est le suivant :<br><br>";
	echo "<table width='70%' style='color:#666;font: 13px Arial;'>";
        echo "<tr>";
            echo "<td style='border-bottom: 2px dashed #0d924b;'>&nbsp;</td>";
            echo "<td style='border-bottom: 2px dashed #0d924b;'><b>Lieu</b></td>";
            echo "<td style='border-bottom: 2px dashed #0d924b;'><b>Heure</b></td>";
        echo "<tr>";
        echo "<tr>";
            echo "<td><b>Départ</b></td>";
            echo "<td>".$villeDepart."</td>";
            echo "<td>".$startHour."</td>";
        echo "<tr>";
        echo "<tr>";
            echo "<td><b>Arrivée</b></td>";
            echo "<td>".$villeArrivee."</td>";
            echo "<td>".$endHour."</td>";
        echo "<tr>";
	echo "</table>";
if(!empty($registrations)){

    echo "<br>Les dates d'inscriptions à ton trajet sont les suivantes :<br><br>";
    echo "<table width='70%' style='color:#666;font: 13px Arial;'>";
        echo "<tr>";
            echo "<td style='border-bottom: 2px dashed #0d924b;'><b>Jour</b></td>";
            echo "<td style='border-bottom: 2px dashed #0d924b;'><b>Date</b></td>";
        echo "<tr>";

        foreach($registrations as $registration)
        {
            echo "<tr>";
            echo "<td><b>".$jour[date("w",strtotime($registration->date))]."</b></td>";
            echo "<td>".date("d-m-Y",strtotime($registration->date))."</td>";
            echo "<tr>";
        }
    echo "</table>";
}

?>
<p>L'équipe Covoiturage CPNV</p>