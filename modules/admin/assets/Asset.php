<?php
namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $baseUrl = '@web/adminassets';

    public $css = [
		'fonts/open-sans/style.min.css',
        'fonts/universe-admin/style.css',
		'fonts/iconfont/style.css',
		'vendor/flatpickr/flatpickr.min.css',
		'vendor/simplebar/simplebar.css',
		'vendor/tagify/tagify.css',
		'vendor/tippyjs/tippy.css',
		'vendor/select2/css/select2.min.css',
		'vendor/bootstrap/css/bootstrap.min.css',
		'css/style.css',
		'vendor/sweet-alert/sweetalert.css',
    ];

    public $js = [
		'vendor/popper/popper.min.js',
		'vendor/bootstrap/js/bootstrap.min.js',
		'vendor/simplebar/simplebar.js',
		'vendor/text-avatar/jquery.textavatar.js',
		'vendor/tippyjs/tippy.all.min.js',
		'vendor/flatpickr/flatpickr.min.js',
		'vendor/wnumb/wNumb.js',
		'js/main.js',
		'vendor/sweet-alert/sweetalert.js',
		
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\FontAwesomeAsset',
    ];
}
