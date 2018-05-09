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
    validateValueExternally();
});

externalValidationButton.click(function() {
    validateValueExternally();
});

function validateValueExternally() {
    // always tri & lowercase
    valueToValidate = externalValidationAnswerField.val().trim().toLowerCase();
    externalValidationAnswerField.val(valueToValidate);
    
    externalValidationMessageContainer.hide({$model->getAnimationDelay()});
    var language = $('html').attr('lang');
    $.ajax({
        type: 'POST',
        url: "{$model->getUrl()}",
        data: {email_address: valueToValidate, language:language},
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                console.log(data.success);
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
    externalValidationMessageContainer.html(null);
    externalValidationMessageContainer.attr("class", "{$model->getMessageSuccessClass()}");
    externalValidationMessageContainer.html('a-ok');
    externalValidationMessageContainer.show({$model->getAnimationDelay()});
}

function externalValidationFailed(errors) {
    var html = "<ul>";
    for (var key in errors) {
        if (errors.hasOwnProperty(key)) {
            console.log("error:"+errors[key]);
            html += "<li>" + errors[key] + "</li>"
        }
    }
    html += "</ul>";
    externalValidationMessageContainer.html(html);
    externalValidationMessageContainer.attr("class", "{$model->getMessageFailedClass()}");
    externalValidationMessageContainer.show({$model->getAnimationDelay()});
}

function moveExternalValidation() {
  externalValidationContainer.insertAfter(externalValidationAnswerField);
  externalValidationContainer.show();
}

JS
);

?>
<div id="<?= $model->getContainerId() ?>" class="check-external-validation-container" hidden>
    <?= CHtml::tag('div', $buttonHtmlOptions, $buttonLabel); ?>
    <div id="<?= $model->getMessageId() ?>" hidden  class="alert alert-danger"></div>
</div>




