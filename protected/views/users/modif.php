<?php
/* @var $this UsersController */
/* @var $model User */

/*echo CHtml::link("Accueil", array('/'));
echo " | ";
echo CHtml::link("<strong>Données personnelles</strong>", array('users/view', 'id'=>$user->id));
*/
?>

<style>
	#notif{
		font-weight: bold;
		font-size: 0.9em;
	}
	.usertable{
		/*margin:auto;*/
		margin-left:170px;
		width:600px;
		/*border:solid black;*/
	}
</style>


<?php
/*echo "<form method='post' action='".CHtml::normalizeUrl(array(Yii::app()->controller->getId().'/'.Yii::app()->controller->getAction()->getId()))."'>";
	if(isset($modif)&&$modif==1)
	{
		echo "modif est setté à 1";



		echo "<div class='form'>";
		$form=$this->beginWidget('CActiveForm');
		 
		    echo $form->errorSummary($user);
		 
		    echo "<div class='row'>";
		        echo $form->label($user,'email');
		        echo $form->textField($user,'email');
		    echo "</div>";
		 
		    echo "<div class='row'>";
   		        echo $form->label($user,'hideEmail');
		        echo $form->checkBox($user,'hideEmail');
		    echo "</div>";

		    echo "<div class='row'>";
		        echo $form->label($user,'telehone');
		        echo $form->numberField($user,'telephone');
		    echo "</div>";
		 
		    echo "<div class='row'>";
		    	echo $form->label($user,'hideTelephone');
		        echo $form->checkBox($user,'hideTelephone');
		    echo "</div>";
		 
		    echo "<div class='row submit'>";
		        echo CHtml::submitButton('Login');
		    echo "</div>";
		 
		$this->endWidget();
		echo "</div>";

*/?>
<?php //if(isset($modif)&&$modif==1){ ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($user); ?>
	<table class='usertable'>
		<tr>
			<td><?php echo $form->labelEx($user,'email', array('label' => 'Email personnel')); ?></td>
			<td><?php echo $form->textField($user,'email',array('size'=>35,'maxlength'=>60)); ?></td>
			<?php //echo $form->error($user,'email'); ?>
		</tr>
	
		<tr>
			<td><?php echo $form->labelEx($user,'hideEmail', array('label' => 'Masquer mon email')); ?></td>
			<td><?php echo $form->checkBox($user,'hideEmail'); ?></td>
			<?php //echo $form->error($user,'hideEmail'); ?>
		</tr>

		<tr>
			<td><?php echo $form->labelEx($user,'telephone', array('label' => 'Téléphone')); ?></td>
			<td><?php echo $form->textField($user,'telephone',array('size'=>35,'maxlength'=>45)); ?></td>
			<?php //echo $form->error($user,'telephone'); ?>
		</tr>

		<tr>
			<td><?php echo $form->labelEx($user,'hideTelephone', array('label' => 'Masquer mon téléphone')); ?></td>
			<td><?php echo $form->checkBox($user,'hideTelephone'); ?></td>
			<?php //echo $form->error($user,'hideTelephone'); ?>
		</tr>
		<tr><td colspan="2"><span id='notif'><br/>Je suis averti par email lorsque quelqu'un ...</span></td></tr>
		<tr>
			<td><?php echo $form->labelEx($user,'notifInscription', array('label' => ' &nbsp; &nbsp; &nbsp; &nbsp;s\'inscrit à l\'un de mes trajets')); ?></td>
			<td><?php echo $form->checkBox($user,'notifInscription'); ?></td>
			<?php //echo $form->error($user,'notifInscription'); ?>
		</tr>

		<tr>

			<td><?php echo $form->labelEx($user,'notifUnsuscribe', array('label' => ' &nbsp; &nbsp; &nbsp; &nbsp;se désinscrit de l\'un de mes trajets')); ?></td>
			<td><?php echo $form->checkBox($user,'notifUnsuscribe'); ?></td>
			<?php //echo $form->error($user,'notifUnsuscribe'); ?>
		</tr>

		<tr>
			<td><?php echo $form->labelEx($user,'notifComment', array('label' => ' &nbsp; &nbsp; &nbsp; &nbsp;commente un de mes trajets')); ?></td>
			<td><?php echo $form->checkBox($user,'notifComment'); ?></td>
			<?php //echo $form->error($user,'notifComment'); ?>
		</tr>

		<tr>
			<td><?php echo $form->labelEx($user,'notifDeleteRide', array('label' => ' &nbsp; &nbsp; &nbsp; &nbsp;supprime un des trajets auquel je suis inscrit')); ?></td>
			<td><?php echo $form->checkBox($user,'notifDeleteRide'); ?></td>
			<?php //echo $form->error($user,'notifDeleteRide'); ?>
		</tr>

		<tr>
			<td><?php echo $form->labelEx($user,'notifModification', array('label' => ' &nbsp; &nbsp; &nbsp; &nbsp;modifie un des trajets auquel je suis inscrit')); ?></td>
			<td><?php echo $form->checkBox($user,'notifModification'); ?></td>
			<?php //echo $form->error($user,'notifModification'); ?>
		</tr>
		<tr><td colspan="2"><center><?php echo CHtml::submitButton($user->isNewRecord ? 'Create' : 'Modifier'); ?></center></td></tr>
	</table>

<?php $this->endWidget(); ?>

</div>



<?php /*
	}else{
	echo "<form method='post' action='".CHtml::normalizeUrl(array(Yii::app()->controller->getId().'/'.Yii::app()->controller->getAction()->getId()))."'>";
		echo "<table class='usertable'>";
			echo "<tr><td>Email personnel</td><td>".$user->email."</td></tr>";
			echo "<tr><td>Masquer mon email</td><td>".valueCheck($user->hideEmail)."disabled ></td></tr>";
			echo "<tr><td>Téléphone</td><td>".$user->telephone."</td></tr>";
			echo "<tr><td>Masquer mon téléphone</td><td>".valueCheck($user->hideTelephone)."disabled ></td></tr>";
			
		echo "</table>";

		echo "<span id='notif'>Je suis averti par email lorsque quelqu'un ...</span>";

		echo "<table class='usertable'>";
			echo "<tr><td>s'inscrit à l'un de mes trajets</td><td>".valueCheck($user->notifInscription). "disabled></td></tr>";
			echo "<tr><td>se désinscrit de l'un de mes trajets</td><td>".valueCheck($user->notifUnsuscribe)."disabled></td></tr>";
			echo "<tr><td>commente un de mes trajets</td><td>".valueCheck($user->notifComment)."disabled></td></tr>";
			echo "<tr><td>supprime un des trajets auquel je suis inscrit</td><td>".valueCheck($user->notifDeleteRide)."disabled></td></tr>";
			echo "<tr><td>modifie un des trajets auquel je suis inscrit</td><td>".valueCheck($user->notifModification)."disabled></td></tr>";
		echo "</table>";
		if($user->admin)
		{
			echo "<span if='admin'>Je suis un administrateur du site</span><br/>";
		}
		echo "<input type='submit' value='modifier' name='userModif' id='userModif'>";
		echo "</form>";
	}
*/
?>
<?php
	// 	si (isset($_POST['userModif']))
	//		formulaire affiché avec champs modifiables
	//		bouton enregistrer
	//	sinon
	//		formulaire affiché sans champs modifiables
	//		bouton modifier
	//	fin si


 	function valueCheck($value)
	{
		if($value)
		{
			return "<input type='checkbox' checked ";
		}else{
			return "<input type='checkbox' ";
		}
	}
	

?>