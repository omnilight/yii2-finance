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
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->i18n->translations['yz/finance'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@yz/finance/messages',
            'sourceLanguage' => 'en-US',
        ];
    }


} 