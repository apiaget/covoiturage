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
	-moz-user-select: none;
	-khtml-user-select: none;
	-webkit-user-select: none;
	user-select: none;
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
table td.highlighted {
  background-color:#E5F1F4;
}
</style>


 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<table>
	<tr>
		<td>Conducteur</td><td><?php echo $ride->driver->cpnvId; ?>
			<span class="user">
				<?php $array=$user->reputation(); echo "<span class='ratings'><span class='rating' style='width:".$array[0]."%'></span></span> (".$array[1]." votes)";?>
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
</table>
<div name="seats" id="seats">	<!--Affichage de la liste des jours-->
	<?php
		$diff=($ride->day-date('N'));
		if($diff<0){
			$diff=7+$diff;
		}
		$date=date('Y-m-d 00:00:00', strtotime(date('Y-m-d 00:00:00', time()).' +'.$diff.' day'));
		$dateA=$dateB=$date;
		echo "<table id='days'><tr id='trajets'><th width='70px'>Date</th>"; //Ligne du haut
		while ($dateA<=$ride->endDate) {
			if($ride->showDuringHolidays($dateA) && $dateA>=$ride->startDate){
				if(isset($_GET['date']) && $_GET['date']==date('d-m-Y', strtotime($dateA))){
					echo "<td class='highlighted' width='68px'>".date('d.m.Y', strtotime($dateA))."</td>";
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
					echo "<td class='highlighted'>";
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
			<?php
			if(isset($ride->bindedride))
			{
				echo '<input type="checkbox" name="allerretour" id="allerretour" />';
			}

			if(isset($_GET['date'])){
				echo '<input type="text" name="dateDebut" id="dateDebut" hidden value="'.date('d.m.Y', strtotime($_GET['date'])).'"/>';
				echo '<input type="text" name="dateFin" id="dateFin" hidden value="'.date('d.m.Y', strtotime($_GET['date'])).'"/>';
			}else{
				echo '<input type="text" name="dateDebut" id="dateDebut" hidden />';
				echo '<input type="text" name="dateFin" id="dateFin" hidden />';
			}

			?>

			<!--<input type="submit" value="S'inscrire">-->

		<div class="form">
<?php

	    foreach(Yii::app()->user->getFlashes() as $key => $message) { //affiche les messages d'erreur
	        echo '<div class="errorMessage '.$key.'">' . $message . "</div>\n";
	    }
	    //var_dump($ride);
?>
		</div>

			
			<!-- Si l'utilisateur inscrit -->
			<?php
			
				//Si id du user est id du ride conçerné, 
				$inscrit = false;
				foreach($registrations as $registration){
					if($registration->userFk->id == User::model()->currentUser()->id)
					{
						$inscrit = true;
						break;
					}
				}
				//sinon
			echo '<tr><td rowspan="2"><input type="submit" value="S\'inscrire" name="inscrire"></td></tr>'; //bouton s'inscrire
			if($inscrit == true)
			{
				echo '<tr><td rowspan="2"><input type="submit" value="Se desinscrire" name="desinscrire"></td></tr>'; //bouton se desinscrire
			}
			?>
			</table>
			<?php
				foreach(Yii::app()->user->getFlashes() as $key => $message) { //affiche les messages d'erreur
					echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
				}
			?>
		</form>
<?php 

		echo '<br/>';
	echo '<table><tr><th>Utilisateur</th><th>Date(s)</th><th>Validation</th></tr>';
	foreach($registrations as $registration)
	{
		$nom=$registration->userFk->nom();
		$prenom = $registration->userFk->prenom();

		$dateDebut = date("d-m-Y",strtotime($registration->startDate));
		$dateFin = date("d-m-Y",strtotime($registration->endDate));
		$date = "";
		
		if($registration->accepted==1)
		{
			$validation ="Validé";
		}
		else
		{
			$validation ="En attente";
		}

		if($dateDebut==$dateFin){
			$date=$dateDebut;
		}
		else
		{
			$date = $dateDebut." - ".$dateFin;
		}
		
		echo'<tr><td>'.$prenom.' '.$nom.'</td><td>'.$date.'</td><td>'.$validation.'</td></tr>';
	}
	echo '</table>';
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
	
	echo '<br/>';
	echo '<table><tr><th>Utilisateur</th><th>Numéro de téléphone</th><th>Email</th><th>Date(s)</th><th>Validation</th><th></th></tr>';
	foreach($registrations as $registration)
	{
		$driverid=$user->id;
		$nom=$registration->userFk->nom();
		$id=$registration->userFk->id;
		$prenom = $registration->userFk->prenom();
		$dateDebut = date("d-m-Y",strtotime($registration->startDate));
		$dateFin = date("d-m-Y",strtotime($registration->endDate));
		$date = "";
		if($registration->userFk->hideTelephone==0)
		{
			$numero =$registration->userFk->telephone;
		}
		else
		{
			$numero ="Non visible";
		}

		if($registration->userFk->hideEmail==0)
		{
			$email =$registration->userFk->email;
		}
		else
		{
			$email ="Non visible";
		}

		if($dateDebut==$dateFin){
			$date=$dateDebut;
		}
		else
		{
			$date = $dateDebut." - ".$dateFin;
		}

		if($registration->accepted==1)
		{
			$validation ="Validé";
			echo'<tr><td>'.$prenom.' '.$nom.'</td><td>'.$numero.'</td><td>'.$email.'</td><td>'.$date.'</td><td>'.$validation.'</td><td></td></tr>';
		}
		else
		{
			echo '<form method="post">';
				$validation ="En attente";
				echo'<input type="hidden" name="idReg" value="'.$registration->id.'" />'; //A REVOIR
				echo'<tr><td>'.$prenom.' '.$nom.'</td><td>'.$numero.'</td><td>'.$email.'</td><td>'.$date.'</td><td>'.$validation.'</td><td><input type="submit" name="valider" value="valider"/></td></tr>';
			echo'</form>';
		}
	}
	echo '</table>';
	}
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
	$(function () {
		var isMouseDown = false,
		isHighlighted;
		var mX=0;
		$("#days td")
			.mousedown(function (e) {
				$("#days td").removeAttr('class');
				isMouseDown = true;
				var index = $(this).parent().children().index($(this));

				$('#dateDebut').val("");
				$('#dateFin').val("");
				if($(this).parent().attr('id')=="trajets" && $(this).index()<($(this).parent().children().size()-1))
				{
					$('#dateDebut').val($('#trajets').children().eq(index).html());
					$('#dateFin').val($('#trajets').children().eq(index).html());
					$(this).toggleClass("highlighted", isHighlighted);

					var col = $(this).parent().next().children().eq($(this).index());
					$(col).toggleClass("highlighted", isHighlighted);
				}
				else if ($(this).index()<($(this).parent().children().size()-1))
				{
					$('#dateDebut').val($('#trajets').children().eq(index).html());
					$('#dateFin').val($('#trajets').children().eq(index).html());
					$(this).toggleClass("highlighted", isHighlighted);

					var col = $(this).parent().prev().children().eq($(this).index());
					$(col).toggleClass("highlighted", isHighlighted);
				}
				isHighlighted = $(this).hasClass("highlighted");
		})
		.mouseover(function (e) {
			if (isMouseDown) {
				if(this.className!="highlighted" && $(this).index() > $(this).parent().children(".highlighted").last().index() && $(this).index()<($(this).parent().children().size()-1)){
					var index = $(this).parent().children().index($(this));
					$('#dateFin').val($('#trajets').children().eq(index).html());
				}
				if(this.className!="highlighted" &&  $(this).index() < $(this).parent().children(".highlighted").first().index() && $(this).index()<($(this).parent().children().size()-1)){
					var index = $(this).parent().children().index($(this));
					$('#dateDebut').val($('#trajets').children().eq(index).html());
				}

				if($(this).parent().attr('id')=="trajets" && $(this).index()<($(this).parent().children().size()-1))
				{
					$(this).toggleClass("highlighted", isHighlighted);
					var col = $(this).parent().next().children().eq($(this).index());
					$(col).toggleClass("highlighted", isHighlighted);
					
				}
				else if($(this).index()<($(this).parent().children().size()-1))
				{
					$(this).toggleClass("highlighted", isHighlighted);
					var col = $(this).parent().prev().children().eq($(this).index());
					$(col).toggleClass("highlighted", isHighlighted);
				}
				var firsthighlighted = $('#trajets').children(".highlighted").first().index();
				var lasthighlighted = $('#trajets').children(".highlighted").last().index();
				for (var i = 0; i<(lasthighlighted-firsthighlighted); i++)
				{
					$('#days').children().children("tr").eq(0).children().eq(firsthighlighted+i).addClass('highlighted');
					$('#days').children().children("tr").eq(1).children().eq(firsthighlighted+i).addClass('highlighted');
				}
			}
		})
		.bind("selectstart", function () {
		  	return false;
		})

		$(document)
			.mouseup(function () {
			isMouseDown = false;
		});
	});
</script>