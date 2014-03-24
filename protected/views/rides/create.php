<?php
/* @var $this RidesController */
/* @var $model Ride */

$this->breadcrumbs=array(
	'Rides'=>array('index'),
	'Create',
);
?>

<!-- Fonction permettant de cacher les champs destinés au retour --> 
<script type="text/javascript">
//document.getElementById("champ_cache").style.display = "none";
 
function afficher()
{
	var elements =document.getElementsByClassName("champ_cache");
	for(var i=0;i<elements.length;i++)    {    	elements[i].style.display = "block";	}
	
}
 
function cacher()
{
    var elements =document.getElementsByClassName("champ_cache");
	for(var i=0;i<elements.length;i++)
    {
    	elements[i].style.display = "none";
	}
}
</script>

<h1>Création d'un trajet</h1>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ride-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($ride); ?>
				<table>
					<tr>
						<td>Conducteur</td>
						<td>

							<?php echo $user->cpnvId; ?>
						</td>
					</tr>
					<tr>
						<td><?php echo $form->labelEx($ride,'departuretown_fk', array('label' => 'Lieu de départ')); ?></td>
						<td>
							
							<?php echo $form->dropDownList($ride,'departuretown_fk', CHtml::listData(Town::model()->findAll(),'id', 'name')); ?>
							<?php echo $form->error($ride,'departuretown_fk'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form->labelEx($ride,'arrivaltown_fk', array('label' => 'Lieu d\'arrivé')); ?></td>
						</td>
						<td>
							<?php echo $form->dropDownList($ride,'arrivaltown_fk', CHtml::listData(Town::model()->findAll(),'id', 'name')); ?>
							<?php echo $form->error($ride,'arrivaltown_fk'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form->labelEx($ride,'departure', array('label' => 'Heure de départ (hh:mm)')); ?>
						</td>
						<td>
							<?php echo $form->timeField($ride,'departure',array('class'=>'input input_r input_pryk','placeholder'=>'08:00')); ?>
							<?php echo $form->error($ride,'departure'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form->labelEx($ride,'arrival', array('label' => 'Heure d\'arrivée (hh:mm)')); ?>
							
						</td>
						<td>
							<?php echo $form->timeField($ride,'arrival',array('class'=>'input input_r input_pryk','placeholder'=>'08:00')); ?> 
							<?php echo $form->error($ride,'arrival'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form->labelEx($ride,'startDate', array('label' => 'Date du trajet')); ?>
							
						</td>
						<td>
							<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
										'model'=>$ride,
        								'attribute'=>'startDate',
									    'name'=>'startDate',
									    // additional javascript options for the date picker plugin
									    'options'=>array(
									        'showAnim'=>'fadeIn',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
									    	 'dateFormat' => 'dd.mm.yy', // date Format
									    ),
									    'htmlOptions'=>array(
									        
									    ),
									));
								?>
							<?php echo $form->error($ride,'startDate'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form->labelEx($ride,'endDate', array('label' => 'Date fin du trajet (récurrence)')); ?>
						</td>
						<td>
							<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
										'model'=>$ride,
        								'attribute'=>'endDate',

									    'name'=>'endDate',
									    // additional javascript options for the date picker plugin
									    'options'=>array(
									        'showAnim'=>'fadeIn', //'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
									        'dateFormat' => 'dd.mm.yy', // Date Format
        									
									    ),
									    'htmlOptions'=>array(
									        
									    ),
									));
								?>
							<?php echo $form->error($ride,'endDate'); ?>
						</td>
					</tr>


					<tr>
						<td>
							<?php echo $form->labelEx($ride,'seats', array('label' => 'Place(s) disponible(s)')); ?>
							
						</td>
						<td>
							<?php echo $form->dropDownList($ride,'seats',array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9')); ?>
							<?php echo $form->error($ride,'seats'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $form->labelEx($ride,'description', array('label' => 'Description')); ?>
						</td>
						<td>
							<?php echo $form->textArea($ride,'description',array('rows'=>6, 'cols'=>50)); ?>
							<?php echo $form->error($ride,'description'); ?>
						</td>
					</tr>

					<tr>
						<td>
							Voulez-vous créer le trajet retour ?<br/>
							Oui <input type="radio" name="teakts" value="oui" id="oui" onClick="afficher();" /> <br />
							Non <input type="radio" name="teakts" value="non" id="non" checked="checked" onClick="cacher();"/> <br />
							<p class="champ_cache" style="display: none;">
							    A cacher
							    
							</p>
						</td>
						<td></td>
					</tr>
					<tr class="champ_cache" style="display: none;">
						<td>
							<?php echo $form->labelEx($ride,'description', array('label' => 'Description')); ?>
						</td>
						<td>
							<?php echo $form->textArea($ride,'description',array('rows'=>6, 'cols'=>50)); ?>
							<?php echo $form->error($ride,'description'); ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo CHtml::submitButton($ride->isNewRecord ? 'Create' : 'Save'); ?>
						</td>
					</tr>
			</table>
		</form>
	</div>
<?php $this->endWidget(); ?>



