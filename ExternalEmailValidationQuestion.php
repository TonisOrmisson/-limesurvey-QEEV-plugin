<?php

/**
 * @author Tõnis Ormisson <tonis@andmemasin.eu>
 * @since 3.0.
 */
class ExternalEmailValidationQuestion extends PluginBase {

    const AUTH_NONE = 0;
    const AUTH_BASIC = 1;
    const AUTH_API_KEY = 2;

    const DEFAULT_ANIMATION_DELAY = 200;

    const DEFAULT_SUCCESS_MESSAGE_CLASS = "alert alert-success";
    const DEFAULT_FAILED__MESSAGE_CLASS = "alert alert-danger";

    protected $storage = 'DbStorage';
    static protected $description = 'External Email Validation for a question';
    static protected $name = 'External Email Validation';


    /* Register plugin on events*/
    public function init() {
        $this->subscribe('beforeQuestionRender');
        $this->subscribe('beforeSurveySettings');
        $this->subscribe('newSurveySettings');
    }

    public function beforeQuestionRender() {
        $this->event;
        $event = $this->event;
        $questionQode = $event->get('code');
        $questionsString = $this->get("questions", 'Survey', $event->get('surveyId'));
        $usedCodes = explode(',', $questionsString);
        if (in_array($questionQode, $usedCodes)) {
            $this->renderPartial('button', [
                'buttonLabel' => $this->gT('Check'),
                'buttonHtmlOptions' =>[
                    'id' => $this->getButtonId(),
                    'class' => 'btn btn-default',
                ],
                'model' => $this,
            ]);

        }

    }



    /**
     * This event is fired by the administration panel to gather extra settings
     * available for a survey.
     * The plugin should return setting meta data.
     */
    public function beforeSurveySettings()
    {
        $event = $this->event;
        $event->set("surveysettings.{$this->id}", [
            'name' => get_class($this),
            'settings' => [
                'url' => [
                    'type' => 'string',
                    'label' => 'URL where for validation request is sent',
                    'current' => $this->get('url', 'Survey', $event->get('survey'))
                ],
                'authType' => [
                    'type' => 'select',
                    'label'=> 'Remote authentication type',
                    'options' => [
                        self::AUTH_NONE => "None",
                        self::AUTH_BASIC => "Basic HTTP",
                        self::AUTH_API_KEY => "API key",
                    ],
                    'default' => self::AUTH_BASIC,
                    'current' => $this->get('authType', 'Survey', $event->get('survey'))
                ],
                'username' => [
                    'type' => 'string',
                    'label' => 'Username / API key',
                    'current' => $this->get('username', 'Survey', $event->get('survey'))
                ],
                'password' => [
                    'type' => 'password',
                    'label' => 'Password',
                    'current' => $this->get('password', 'Survey', $event->get('survey'))
                ],
                'questions' => [
                    'type' => 'string',
                    'label' => 'Questions',
                    'help' => 'Comma-separated question names (codes) on which the validation occurs',
                    'current' => $this->get('questions', 'Survey', $event->get('survey'))
                ],

            ]
        ]);
    }



    /**
     * add new survey settings
     */
    public function newSurveySettings()
    {
        $event = $this->event;
        foreach ($event->get('settings') as $name => $value) {
            $this->set($name, $value, 'Survey', $event->get('survey'));
        }
    }

    /**
     * @return string
     */
    public function  getButtonId()
    {
        $event = $this->event;
        return "check-external-validation-button-" . $event->get('qid');
    }

    /**
     * @return string
     */
    public function  getContainerId()
    {
        $event = $this->event;
        return "check-external-validation-" . $event->get('qid');
    }

    /**
     * @return string
     */
    public function  getMessageId()
    {
        $event = $this->event;
        return "check-external-validation-message-" . $event->get('qid');
    }

    /**
     * @return string
     */
    public function getAnswerFieldId() {
        $event = $this->event;
        return "answer" . $event->get('surveyId') . "X" . $event->get('gid') . "X" . $event->get('qid');
    }

    /**
     * @return mixed
     */
    public function getUrl() {
        $event = $this->event;
        $url = $this->get("url", 'Survey', $event->get('surveyId'));
        return $url;
    }

    public function getAnimationDelay()
    {
        return self::DEFAULT_ANIMATION_DELAY;
    }

    /**
     * @return string
     */
    public function getMessageSuccessClass()
    {
        return self::DEFAULT_SUCCESS_MESSAGE_CLASS;
    }

    /**
     * @return string
     */
    public function getMessageFailedClass()
    {
        return self::DEFAULT_FAILED__MESSAGE_CLASS;
    }

}
