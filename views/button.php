<?php
/** @var array $buttonHtmlOptions */
/** @var string $buttonLabel */
/** @var ExternalEmailValidationQuestion $model */

Yii::app()->clientScript->registerScript("script".$model->getAnswerFieldId(), <<<JS
moveExternalValidation();
$("#{$model->getAnswerFieldId()}").change(function() {
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

function moveExternalValidation() {
  $("#{$model->getId()}").insertAfter("#{$model->getAnswerFieldId()}")
  $("#{$model->getId()}").show();
}

JS
);

?>
<div id="<?=$model->getId()?>" hidden >
    <?= CHtml::tag('div', $buttonHtmlOptions, $buttonLabel);?>
</div>




