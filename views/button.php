<?php
/** @var array $buttonHtmlOptions */
/** @var string $buttonLabel */
/** @var ExternalEmailValidationQuestion $model */

Yii::app()->clientScript->registerScript("script" . $model->getAnswerFieldId(), <<<JS
moveExternalValidation();
$("#{$model->getAnswerFieldId()}").change(function() {
    if (validateValueExternally($( this ).val())) {
        externalValidationOK();
    } else {
        externalValidationFailed();
    }
});


function validateValueExternally(valueToValidate) {
    $.ajax({
        type: 'post',
        url: "{$model->getUrl()}",
        data: {username:"aaaa"},
        dataType: 'json',
        success: function(data) {
            alert( "called ajax" );
            if (data.result) {
                location.reload(); 
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
        }            
    });

    return true;  
}

function externalValidationOK() {
  
}

function externalValidationFailed() {
  
}

function moveExternalValidation() {
  $("#{$model->getContainerId()}").insertAfter("#{$model->getAnswerFieldId()}")
  $("#{$model->getContainerId()}").show();
}


JS
);

?>
<div id="<?= $model->getContainerId() ?>" hidden >
    <?= CHtml::tag('div', $buttonHtmlOptions, $buttonLabel); ?>
</div>




