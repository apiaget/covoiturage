<?php 
	$start=date("d-m-Y",strtotime($registration->rideFk->startDate));
	$end=date("d-m-Y",strtotime($registration->rideFk->endDate));
	$villeDepart=$registration->rideFk->departuretown->name;
	$villeArrivee=$registration->rideFk->arrivaltown->name;
	$startHour=date("H:i",strtotime($registration->rideFk->departure));
	$endHour=date("H:i",strtotime($registration->rideFk->arrival));
	$user=$registration->rideFk->driver->prenom;


	echo "Bonjour ".$user.",<br>";

	echo "Nous avons le regret de vous annoncer qu'un utilisateur s'est désinscrit au trajet du <b>".$start." au ".$end."</b>";

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

?>
<p>L'équipe Covoiturage CPNV</p>