<?php
/* @var $this RidesController */
/* @var $ride Ride */

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
#map-canvas {
	height: 300px;
	width: 910px;
	margin: 0px;
	padding: 0px
}

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/highcharts-more.js"></script>



<!--googlemap api-->

    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
		var directionsDisplay;
		var directionsService = new google.maps.DirectionsService();
		var map;

		function initialize() {
		  directionsDisplay = new google.maps.DirectionsRenderer();
		  var SantaCruz = new google.maps.LatLng(46.822650, 6.502052);
		  var mapOptions = {

		  }
		  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		  directionsDisplay.setMap(map);
		  calcRoute();
		}

		function calcRoute() {
		  var start = <?php echo json_encode("Suisse ".$ride->departuretown->name); ?>;
		  var end = <?php echo json_encode("Suisse ".$ride->arrivaltown->name); ?>;
		  var request = {
			  origin:start,
			  destination:end,
			  travelMode: google.maps.TravelMode.DRIVING
		  };
		  directionsService.route(request, function(response, status) {
			if (status == google.maps.DirectionsStatus.OK) {
			  directionsDisplay.setDirections(response);
			}
		  });
		}

		google.maps.event.addDomListener(window, 'load', initialize);

    </script>
	<!--fin api goolemap -->
	<!--affichage de la map -->
<div id="map-canvas"></div>
<br>

<table>
	<tr>
		<td>Conducteur</td><td><?php echo $ride->driver->prenom." ".$ride->driver->nom; ?>
			<?php if(Yii::app()->params['Votes']=="yes")
			{?>
				<span class="user">
				<?php $array=$user->reputation(); echo "<span class='ratings'><span class='rating' style='width:".$array[0]."%'></span></span> (".$array[1]." votes)";?>
				</span>
			<?php }?>
			
		</td>
	</tr>
	<?php
		if(isset($ride->bindedride))
		{
	?>
			<tr>
				<td><strong>
				<?php if(strtotime($ride->departure)<=strtotime($ride->trajetretour->departure)){echo 'Aller';}else{echo 'Retour';}?>
				</strong></td><td></td>
			</tr>
	<?php
		}
	?>
	<tr>
		<td>Départ</td><td><?php echo $ride->departuretown->name." à ".$ride->departure; ?></td>
	</tr>
	<tr>
		<td>Arrivée prévue</td><td><?php echo $ride->arrivaltown->name." vers ".$ride->arrival; ?></td>
	</tr>
	<tr>
		<td>Description</td><td><?php echo $ride->description; ?></td>
	</tr>
	<?php
		if(isset($ride->bindedride))
		{
	?>
			<tr>
				<td>
					<strong>
					<?php if(strtotime($ride->departure)<=strtotime($ride->trajetretour->departure)){echo 'Retour';}else{echo 'Aller';}?>
					</strong>
				</td><td></td>
			</tr>
			<tr>
				<td>Départ</td><td><?php echo $ride->trajetretour->departuretown->name." à ".$ride->trajetretour->departure; ?></td>
			</tr>
			<tr>
				<td>Arrivée prévue</td><td><?php echo $ride->trajetretour->arrivaltown->name." vers ".$ride->trajetretour->arrival; ?></td>
			</tr>
			<tr>
				<td>Description</td><td><?php echo $ride->trajetretour->description; ?></td>
			</tr>
	<?php
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
			if(isset($_GET['date'])){
				echo '<input type="text" name="dateDebut" id="dateDebut" hidden value="'.date('d.m.Y', strtotime($_GET['date'])).'"/>';
				echo '<input type="text" name="dateFin" id="dateFin" hidden value="'.date('d.m.Y', strtotime($_GET['date'])).'"/>';
			}else{
				echo '<input type="text" name="dateDebut" id="dateDebut" hidden />';
				echo '<input type="text" name="dateFin" id="dateFin" hidden />';
			}
?>
			<div class="form">
<?php
				foreach(Yii::app()->user->getFlashes() as $key => $message) { //affiche les messages d'erreur
					echo '<div class="errorMessage '.$key.'">' . $message . "</div>\n";
				}
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
			//si il y a un ride retour
			if(isset($ride->bindedride))
			{
				if(strtotime($ride->departure)<=strtotime($ride->trajetretour->departure))
				{
					echo '<tr><td rowspan="2"><input type="submit" value="S\'inscrire au trajet Aller" name="inscrire"></td></tr>';
				}
				else
				{
					echo '<tr><td rowspan="2"><input type="submit" value="S\'inscrire au trajet Retour" name="inscrire"></td></tr>';
				}
				echo '<tr><td rowspan="2"><input type="submit" value="S\'inscrire à l\'Aller-Retour" name="inscrireAllerRetour"></td></tr>';
			}
			else
			{
				echo '<tr><td rowspan="2"><input type="submit" value="S\'inscrire" name="inscrire"></td></tr>'; //bouton s'inscrire
			}

			//si l'utilisateur a au moins une inscription au ride
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
		if(!empty($registrations)){
			echo '<br/>';
			echo '<table id="registredUsers"><tr><th>Utilisateur</th><th>Date(s)</th><th>Validation</th></tr>';
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
				
				echo'<tr><td>'.$prenom.' '.$nom.'</td><td>'.$date.'</td><td>'.$validation.'</td><td></td></tr>';
			}
			echo '</table>';
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
		if(!empty($registrations)){
			echo '<br/>';
			echo '<table id="registredUsers"><tr><th>Utilisateur</th><th>Date(s)</th><th>Email</th><th>Numéro de téléphone</th><th>Validation</th><th></th></tr>';
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
					echo'<tr><td>'.$prenom.' '.$nom.'</td><td>'.$date.'</td><td>'.$email.'</td><td>'.$numero.'</td><td>'.$validation.'</td><td></td></tr>';
				}
				else
				{
					$validation ="En attente";
					echo'<tr><td>'.$prenom.' '.$nom.'</td><td>'.$date.'</td><td>'.$email.'</td><td>'.$numero.'</td><td>'.$validation.'</td>
					<td>
					<form method="post">
					<input type="hidden" name="idReg" value="'.$registration->id.'" />
					<input type="submit" name="valider" value="valider"/>
					</form>
					</td></tr>';
				}
			}
			echo '</table>';
		}
	}
if(Yii::app()->params['ExecutionTime']=="yes")
{
	echo Yii::getLogger()->getExecutionTime();
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
<!--<div id="container" style="min-width: 310px; height: 250px; margin: 0 auto"></div>-->
<div id="container" style="min-width: 310px; margin: 0 auto"></div>

<script type="text/javascript">


    $(document).ready(
	function() {
		var tableData = $('#registredUsers');
		if(tableData.children().find('tr').length>1) //au moins une registration
		{

			$('#container').highcharts({
				chart: {
					type: 'columnrange',
					inverted: true
				},
				title: {
					text: 'Remplissage du véhicule'
				},
				yAxis: {
					type:"datetime",
						dateTimeLabelFormats:{
						month: '%b %e, %Y'
					},
					title: {
						text: 'Dates'
					},
					tickInterval: 86400000 * 7 // 7 days
				},
				tooltip: {
					headerFormat: '<b>{series.name}</b><br>',
					pointFormat: '{point.low:%b. %e} - {point.high:%b. %e}'
				},

				plotOptions: {
					columnrange: {
						dataLabels: {
							enabled: true,
							formatter: function () {
								//return Highcharts.dateFormat('%b. %e', this.y);
							}
						}
					},
					series: {
						marker: {
							enabled: true	
						}
					}
				},
				legend: {
					enabled: false
				},
				series: [{
					name: 'Dates',
					data: []
				}]
			});


			var chart = $('#container').highcharts();
			

			var users = $("#registredUsers tr td:nth-child(1)");

			$('#container').height(50+50*(users.length+1));
			chart.setSize(910, 50+50*(users.length+1),false);

			var categories = "[";
			for(var i = 0 ; i < users.length ; i++)
			{
				categories += "'"+users.eq(i).html()+"',";
			}
			categories = categories.substring(0, categories.length - 2)+"']";
			chart.xAxis[0].setCategories(eval(categories));
			
			var registrationsRow = tableData.children().find('tr').nextAll();

			for(var i = 0 ; i < registrationsRow.length ; i++)
			{
				var dates = registrationsRow.eq(i).find('td').eq(1).html();
				var valid = registrationsRow.eq(i).find('td').last().prev().html();
				if(dates.length==10)//un seul jour
				{
					var date = dates.split('-');
					chart.series[0].addPoint([Date.UTC(date[2],  date[1]-1, date[0]),Date.UTC(date[2],  date[1]-1, date[0], 23, 59, 59)]);
				}
				else
				{
					var date = dates.split(' - ');
					var dates1 = date[0].split('-');
					var dates2 = date[1].split('-');
					chart.series[0].addPoint([Date.UTC(dates1[2],  dates1[1]-1, dates1[0]),Date.UTC(dates2[2],  dates2[1]-1, dates2[0])]);
				}
				
				if(valid=="En attente")
				{
					chart.series[0].data[i].update({
						marker:{
							color: '#FF2A40',
							states:{
								hover:{
									color: '#ff6171'
								}
							}
						}
					});
				}else if (valid == "Validé"){
					chart.series[0].data[i].update({
						marker:{
							color: '#00CD73',
							states:{
								hover:{
									color: '#4DFFB1'
								}
							}
						}
					});
				}
			}
			var date = tableData.children().find('tr').nextAll().eq(0).find('td').eq(1).html().substring(0, 10).split('-');
			var dayNumber = new Date(date[2],  date[1]-1, date[0]).getDay();
			chart.yAxis[0].update({startOfWeek: dayNumber});
		}



		var isMouseDown = false,
		isHighlighted = true;
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
				//isHighlighted = $(this).hasClass("highlighted");
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