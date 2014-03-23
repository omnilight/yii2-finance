<?php

namespace yz\finance;
use yii\base\Application;
use yii\base\BootstrapInterface;


/**
 * Class Bootstrap
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap(Application $app)
    {
        $app->i18n->translations['yz/finance'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@yz/finance',
            'sourceLanguage' => 'en-US',
        ];
    }


} 