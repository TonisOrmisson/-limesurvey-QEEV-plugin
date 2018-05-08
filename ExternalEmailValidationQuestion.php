<?php
/**
 * @author Tõnis Ormisson <tonis@andmemasin.eu>
 * @since 3.0.
 */
class ExternalEmailValidationQuestion extends PluginBase {

    const AUTH_NONE = 0;
    const AUTH_BASIC = 1;
    const AUTH_API_KEY = 2;

    protected $storage = 'DbStorage';
    static protected $description = 'External Email Validation for a question';
    static protected $name = 'External Email Validation';

    protected $templates;

    /* Register plugin on events*/
    public function init() {
        $this->subscribe('afterFindSurvey');
        $this->subscribe('beforeQuestionRender');
        $this->subscribe('newSurveySettings');
    }

    public function beforeQuestionRender(){
        $this->event;

    }



    /**
     * This event is fired by the administration panel to gather extra settings
     * available for a survey.
     * The plugin should return setting meta data.
     */
    public function beforeSurveySettings()
    {
        $event = $this->event;
        $event->set("surveysettings.{$this->id}", array(
            'name' => get_class($this),
            'settings' => array(
                'url' => array(
                    'type' => 'string',
                    'label' => 'URL where for validation request is sent',
                ),
                'authType' => array(
                    'type' => 'select',
                    'label'=> 'Remote authentication type',
                    'options' => [
                        self::AUTH_NONE => "None",
                        self::AUTH_BASIC => "Basic HTTP",
                        self::AUTH_API_KEY => "API key",
                    ],
                    'default' => self::AUTH_BASIC,
                ),
                'username' => array(
                    'type' => 'string',
                    'label' => 'Username / API key',
                ),
                'password' => array(
                    'type' => 'password',
                    'label' => 'Password',
                ),

            )
        ));
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

}
