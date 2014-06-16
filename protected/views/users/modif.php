<?php
/* @var $this UsersController */
/* @var $model User */

?>

<style>
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
	}
	.rating{
		background: url('../images/star_red.png') repeat-x 0 0;
		height:14px;
	}
</style>
<div class="user">
	<?php $array=$user->reputation(); echo "<h3>".$user->prenom." ".$user->nom."</h3>";
	if(Yii::app()->params['Votes']=="yes")
	{
		echo "<div class='ratings'><div class='rating' style='width:".$array[0]."%'></div></div> (".$array[1]." votes)";
	}
	?>

</div>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>
<p class="note">Les champs marqué d'un <span class="required">*</span> sont requis.</p>
	<?php echo $form->errorSummary($user); ?>
	<table class='usertable'>
		<tr>
			<td><?php echo $form->labelEx($user,'email', array('label' => 'Email personnel')); ?></td>
			<!--<td><?php //echo $form->textField($user,'email',array('size'=>35,'maxlength'=>60)); ?></td>-->
			<td><?php echo $form->textField($user,'email',array('size'=>35,'maxlength'=>60)); if(Yii::app()->params['Votes']=="yes")
	{
		echo "<br/>Réputation : " . $array[0];
	}?></td>
		</tr>
	
		<tr>
			<td><?php echo $form->labelEx($user,'hideEmail', array('label' => 'Masquer mon email')); ?></td>
			<td><?php echo $form->checkBox($user,'hideEmail'); ?></td>
		</tr>

		<tr>
			<td><?php echo $form->labelEx($user,'telephone', array('label' => 'Téléphone')); ?></td>
			<td><?php echo $form->textField($user,'telephone',array('size'=>35,'maxlength'=>45)); ?></td>
		</tr>

		<tr><td colspan="2"><span id='notif'><br/>Je suis averti par email lorsque quelqu'un ...</span></td></tr>
		<tr>
			<td><?php echo $form->labelEx($user,'notifInscription', array('label' => ' &nbsp; &nbsp; &nbsp; &nbsp;s\'inscrit à l\'un de mes trajets')); ?></td>
			<td><?php echo $form->checkBox($user,'notifInscription'); ?></td>
		</tr>

		<tr>

			<td><?php echo $form->labelEx($user,'notifUnsuscribe', array('label' => ' &nbsp; &nbsp; &nbsp; &nbsp;se désinscrit de l\'un de mes trajets')); ?></td>
			<td><?php echo $form->checkBox($user,'notifUnsuscribe'); ?></td>
		</tr>

		<tr>
			<td><?php echo $form->labelEx($user,'notifValidation', array('label' => ' &nbsp; &nbsp; &nbsp; &nbsp;valide mon inscription pour un trajet')); ?></td>
			<td><?php echo $form->checkBox($user,'notifValidation'); ?></td>
		</tr>

		<!--<tr>
			<td><?php echo $form->labelEx($user,'notifComment', array('label' => ' &nbsp; &nbsp; &nbsp; &nbsp;commente un de mes trajets')); ?></td>
			<td><?php echo $form->checkBox($user,'notifComment'); ?></td>
		</tr>-->

		<tr>
			<td><?php echo $form->labelEx($user,'notifDeleteRide', array('label' => ' &nbsp; &nbsp; &nbsp; &nbsp;supprime un des trajets auquel je suis inscrit')); ?></td>
			<td><?php echo $form->checkBox($user,'notifDeleteRide'); ?></td>
		</tr>

		<tr>
			<td><?php echo $form->labelEx($user,'notifModification', array('label' => ' &nbsp; &nbsp; &nbsp; &nbsp;modifie un des trajets auquel je suis inscrit')); ?></td>
			<td><?php echo $form->checkBox($user,'notifModification'); ?></td>
		</tr>
		<tr><td colspan="2"><center><?php echo CHtml::submitButton($user->isNewRecord ? 'Create' : 'Modifier'); ?></center></td></tr>
	</table>

<?php $this->endWidget(); ?>

</div>