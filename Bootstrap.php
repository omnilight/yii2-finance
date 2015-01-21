<?php

namespace omnilight\finance;

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
        $app->i18n->translations['omnilight/finance'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@omnilight/finance/messages',
            'sourceLanguage' => 'en-US',
        ];

        \Yii::$app->params['yii.migrations'][] = '@omnilight/finance/migrations';
    }
}