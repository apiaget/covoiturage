<?php
/* @var $this RidesController */
/* @var $ride Ride */

/*$this->breadcrumbs=array(
	'Rides'=>array('index'),
	$ride->id,
);*/

?>
<style type="text/css">
#seats{
	width: 900px;
	overflow: auto;
}
#days td{
	padding: 0 4px 0 4px;
}
#days{
	margin-bottom: 0;
}
.chosen{
	background-color: #E5F1F4;
}

#notif{
		font-weight: bold;
		font-size: 0.9em;
	}
	.usertable{
		margin-left:170px;
		width:600px;
	}
	.ratings{
		background: url('../images/star_grey.png') repeat-x 0 0;
		width:70px;
		height:14px;
		display: inline-block;
	}
	.rating{
		background: url('../images/star_red.png') repeat-x 0 0;
		height:14px;
	}
</style>




<table>
	<tr>
		<td>Conducteur</td><td><?php echo $ride->driver->cpnvId; ?>
			<span class="user">
				<?php $array=$user->reputation(); echo "</h3><span class='ratings'><span class='rating' style='width:".$array[0]."%'></span></span> (".$array[1]." votes)";?>
			</span>
		</td>
	</tr>
	<tr>
		<td>Départ</td><td><?php echo $ride->departuretown->name." à ".substr($ride->departure, 0, 5); ?></td>
	</tr>
	<tr>
		<td>Arrivée prévue</td><td><?php echo $ride->arrivaltown->name." vers ".substr($ride->arrival, 0, 5); ?></td>
	</tr>
	<tr>
		<td>Description</td><td><?php echo $ride->description; ?></td>
	</tr>
	<?php
		if($ride->bindedride==null){
			echo "woaw";
		}
	?>
</table>
<div name="seats" id="seats">	<!--Affichage de la liste des jours-->
	<?php
		$diff=($ride->day-date('N'));
		if($diff<0){
			$diff=7+$diff;
		}
		$date=date('Y-m-d 00:00:00', strtotime(date('Y-m-d 00:00:00', time()).' +'.$diff.' day'));
		$dateA=$dateB=$date;
		echo "<table id='days'><tr id='cool'><th width='70px'>Date</th>"; //Ligne du haut
		while ($dateA<=$ride->endDate) {
			if($ride->showDuringHolidays($dateA) && $dateA>=$ride->startDate){
				if(isset($_GET['date']) && $_GET['date']==date('d-m-Y', strtotime($dateA))){
					echo "<td class='chosen' width='68px'>".date('d.m.Y', strtotime($dateA))."</td>";
				}else{
					echo "<td width='68px'>".date('d.m.Y', strtotime($dateA))."</td>";
				}
			}
			$dateA=date('Y-m-d 00:00:00', strtotime($dateA.'+7 day'));
		}
		echo "<td class='supp'></td></tr><tr><th>Occupation</th>"; //ligne du bas
		while ($dateB<=$ride->endDate) {
			if($ride->showDuringHolidays($dateB) && $dateB>=$ride->startDate){
				if(isset($_GET['date']) && $_GET['date']==date('d-m-Y', strtotime($dateB))){
					echo "<td class='chosen'>";
					$i=0;
					foreach ($registrations as $registration) {
						if($dateB>=$registration->startDate&&$dateB<=$registration->endDate&&$registration->accepted){$i++;	}
					}
					echo $i."/".$ride->seats;
				}else{
					echo "<td>";
					$i=0;
					foreach ($registrations as $registration) {
						if($dateB>=$registration->startDate&&$dateB<=$registration->endDate&&$registration->accepted){$i++; }
					}
					echo $i."/".$ride->seats;
				}
				echo "</td>";
			}
			$dateB=date('Y-m-d 00:00:00', strtotime($dateB.'+7 day'));
		}
		echo "<td class='supp'></td></tr></table>";
	?>
</div>

<?php
	if($user->id!=$ride->driver_fk){ //passager ou utilisateur
?>
		<form method="post">
			<table>
			<tr><td><label for="date">Date</label></td><td><input type="text" name="date" id="date" disabled/><input type="text" name="dateB" id="dateB" hidden/></td></tr>
			<?php 
			if($ride->startDate!=$ride->endDate) //récurrence 
			{
				echo "<tr><td><label for='recurrence'>Récurrence*</label></td><td><input type='checkbox' name='recurrence' id='recurrence' /><input type='checkbox' name='recurrenceON' id='recurrenceON' checked hidden/></td></tr>";
			}else{
				echo "<tr><td><input type='checkbox' name='recurrenceOFF' id='recurrenceOFF' checked hidden/></td></tr>";
			}
			?>
			<?php 
			if($ride->bindedride!="") //aller retour 
			{
				$bindedriveid = $ride->bindedride;
				$bindedrive = Ride::model()->find("id=:bindedriveid", array(":bindedriveid"=> $bindedriveid));
			
				echo "<tr><td><label for='allerretour'>Inscription au retour de ".substr($bindedrive->departure,0,5)."</label></td><td><input type='checkbox' name='allerretour' id='allerretour' /><input type='checkbox' name='allerretourON' id='allerretourON' checked hidden/></td></tr>";
			}else{
				echo "<tr><td><input type='checkbox' name='allerretourOFF' id='allerretourOFF' checked hidden/></td></tr>";
			}
			?>
			<tr><td rowspan="2"><input type="submit" value="S'inscrire"></td></tr>
		</table>
<?php
	    foreach(Yii::app()->user->getFlashes() as $key => $message) { //affiche les messages d'erreur
	        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
	    }
?>
		</form>
<?php 
		if($ride->startDate!=$ride->endDate) //aller retour 
		{
			echo "<small>*La récurrence s'effectue à partir de la date sélectionnée jusqu'à la fin des trajets proposés</small>";
		}
	}
?>

<?php
	if($user->id==$ride->driver_fk){ //si c'est le conducteur
?>
		<form method="post">
			<input type="submit" name="supprimer" id="supprimer" value="Supprimer le trajet"/>
			<input type="submit" name="editer" id="editer" value="Editer le trajet"/>
		</form>
<?php
	}
	var_dump($ride);
?>
<?
//	voit paramètre du ride ----OK
//	Si driver
//		voit personnes inscrites, validée ou non avec leur réputation et leur téléphone
//		possibilité de valider les utilisateurs
//		possibilité d'expulser des utilisateurs
//		voit bouton "editer", "supprimer"
//		voit commentaires, possibilités d'en supprimer et de répondre
//	Ou Si passager
//		voit natel du conducteur
//		voit les autres utilisateurs inscrits
//		possibilité de se désinscrire
//		voit commentaires avec possibilité de répondre
//	Sinon
//		case contenant la date remplie si get la contient ----OK
//		case à cocher récurrence / aller-retour ----OK
//		bouton "s'inscrire" ----OK
//		voit commentaires avec possibilité de répondre
//	fin Si
?>


<script type="text/javascript">
	var tds=document.getElementsByTagName('td');
	var l=0
	for(var i=0, iMax=tds.length ; i < iMax; i++){
		if(tds[i].parentNode.parentNode.parentNode.id == 'days' && tds[i].className!="supp" && document.getElementById('date') != null){
			if(l==0) {//s'assure que la date n'est pas vide
				document.getElementById('date').value=tds[i].parentNode.parentNode.childNodes[0].childNodes[1].textContent;
				document.getElementById('dateB').value=tds[i].parentNode.parentNode.childNodes[0].childNodes[1].textContent;
			} 
			tds[i].onclick = function(){
				resetColorDayTable(); //reseter toutes les couleurs
				var k = 0;
				that=this;
				while( (that = that.previousSibling) != null ) {k++;} //compte 
				this.parentNode.parentNode.childNodes[0].childNodes[k].className="chosen";//mettre la couleur au jour choisi
				this.parentNode.parentNode.childNodes[1].childNodes[k].className="chosen";
				date=this.parentNode.parentNode.childNodes[0].childNodes[k].textContent;//récupérer valeur ligne du haut
				document.getElementById('date').value=date;
				document.getElementById('dateB').value=date;
				return false;
			};
		}
	}
	function resetColorDayTable(){
		var table = document.getElementById('days');
		for(var i = 0; i<2; i++)
		{
			for(var j = 0, jMax = table.childNodes[0].childNodes[0].childNodes.length; j<jMax;j++)
			{
				table.childNodes[0].childNodes[i].childNodes[j].className="";
			}
		}
	}

</script>