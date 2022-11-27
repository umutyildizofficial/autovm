<?php
namespace app\modules\site\assets;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $baseUrl = '@web/siteassets';

    public $css = [
		'css/simple-alert.css',
		'css/bootstrap.css',
        'js/plugins/morris/morris.css',
        'css/style.css',
    ];

    public $js = [
        'js/simple-alert.js',
        'js/raphael.js',
        'js/plugins/morris/morris.js',
        'js/main.js',
        'js/bootstrap.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\FontAwesomeAsset',
    ];
}
