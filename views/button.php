<?php
/** @var array $buttonHtmlOptions */
/** @var string $buttonLabel */
/** @var ExternalEmailValidationQuestion $model */

Yii::app()->clientScript->registerScript("script" . $model->getAnswerFieldId(), <<<JS

var externalValidationContainer = $("#{$model->getContainerId()}");
var externalValidationMessageContainer = $("#{$model->getMessageId()}");
var externalValidationAnswerField = $("#{$model->getAnswerFieldId()}");
var externalValidationButton = $("#{$model->getButtonId()}");

moveExternalValidation();

externalValidationAnswerField.change(function() {
    if (validateValueExternally($( this ).val())) {
        externalValidationOK();
    } else {
        externalValidationFailed();
    }
});

externalValidationButton.click(function() {
    if (validateValueExternally($("#{$model->getAnswerFieldId()}").val())) {
        externalValidationOK();
    } else {
        externalValidationFailed();
    }
});

function validateValueExternally(valueToValidate) {
    $.ajax({
        type: 'POST',
        url: "{$model->getUrl()}",
        data: {email_address: valueToValidate},
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                externalValidationOK();
            } else {
                externalValidationFailed(data.errors)
            }
        },
        error: function(result) {
            alert("Error making validation query, please contact administrator!");
        }
    });

    return true;  
}


function externalValidationOK() {
    externalValidationMessageContainer.html('a-ok');
    externalValidationMessageContainer.show();
}

function externalValidationFailed(errors) {
    var html = "<ul>";
    for (var key in errors) {
        if (errors.hasOwnProperty(key)) {
            console.log("error:"+errors[key]);
            html += "<li>" + errors[key] + "</li>"
        }
    }
    html += "</ul>"
    externalValidationMessageContainer.html(html);
    externalValidationMessageContainer.show();
}

function moveExternalValidation() {
  externalValidationContainer.insertAfter("#{$model->getAnswerFieldId()}")
  externalValidationContainer.show();
}

JS
);

?>
<div id="<?= $model->getContainerId() ?>" hidden >
    <?= CHtml::tag('div', $buttonHtmlOptions, $buttonLabel); ?>
    <div id="<?= $model->getMessageId() ?>" hidden >
    </div>
</div>




