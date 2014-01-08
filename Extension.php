<?php

namespace yz\finance;


/**
 * Class Extension
 */
class Extension extends \yii\base\Extension
{
	public static function init()
	{
		parent::init();

		\Yii::$app->i18n->translations['yz/finance'] = [
			'class' => 'yii\i18n\PhpMessageSource',
			'basePath' => '@yz/finance',
			'sourceLanguage' => 'en-US',
		];
	}

} 