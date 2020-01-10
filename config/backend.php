<?php

return [
    'sms_login' => env('SMS_LOGIN', false),
    
    'copyright' => 'Powered by <a href="https://www.zhuitech.com" target="_blank">追数科技</a>',

    /**
     * 使用setting模块进行动态配置的config项
     */
    'settings' => [
        'admin.name',
        'admin.login_background_image',
        'admin.title',
        'admin.logo',
        'admin.logo-mini',
        'admin.skin',
        'backend.copyright',
    ],
];
