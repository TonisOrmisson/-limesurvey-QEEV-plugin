<?php
/** @var array $options */
/** @var string $label */
/** @var string $answerFieldId */

Yii::app()->clientScript->registerScript("script".$answerFieldId, <<<JS
$("#$answerFieldId").change(function() {
    if (validateValueExternally($( this ).val())) {
        externalValidationOK();
    } else {
        externalValidationFailed();
    }
    alert( "Handler for .change() called." );
});


function validateValueExternally(valueToValidate) {
    return true;  
}

function externalValidationOK() {
  
}

function externalValidationFailed() {
  
}

JS
);

?>

<?= CHtml::tag('div',$options,$label);?>


