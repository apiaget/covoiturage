<?php 
	$start=date("d-m-Y",strtotime($registration->rideFk->startDate));
	$end=date("d-m-Y",strtotime($registration->rideFk->endDate));
	$villeDepart=$registration->rideFk->departuretown->name;
	$villeArrivee=$registration->rideFk->arrivaltown->name;
	$startHour=date("H:i",strtotime($registration->rideFk->departure));
	$endHour=date("H:i",strtotime($registration->rideFk->arrival));
	$user=$registration->userFk->prenom;


	echo "Bonjour ".$user.",<br>";


	echo "Nous tenons à vous informer que l'un de vos trajets a été modifié.";
	

	echo "<br>Voici les nouvelles caractéristiques de votre trajet :<br><br>";
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