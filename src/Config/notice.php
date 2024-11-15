<?php

return [
    'default' => 'WeChat',
    'channels' => [
        'WeChat' => [
            'corpid' => '**********',
            'corpsecret' => '**********',
            'agentid' => '**********',
            'token_url' => 'https://qyapi.weixin.qq.com/cgi-bin/gettoken',
            'send_url' => 'https://qyapi.weixin.qq.com/cgi-bin/message/send',
            'other_params' => [
                'upload_media_url' => 'https://qyapi.weixin.qq.com/cgi-bin/media/upload',
            ],
        ],
        'Dingding' => [
            'corpid' => '**********',
            'corpsecret' => '**********',
            'agentid' => '**********',
            'token_url' => 'https://api.dingtalk.com/v1.0/oauth2/accessToken',
            'send_url' => 'https://api.dingtalk.com/v1.0/robot/oToMessages/batchSend',
            'other_params' => [
                'upload_media_url' => 'https://oapi.dingtalk.com/media/upload', //钉钉多媒体上传url
                'send_group_url' => 'https://api.dingtalk.com/v1.0/robot/groupMessages/send',
                'send_application_url' => 'https://oapi.dingtalk.com/topapi/message/corpconversation/asyncsend_v2?access_token=',
            ],
        ],
        'Feishu' => [
            'corpid' => '**********',
            'corpsecret' => '**********',
            'agentid' => '**********',
            'token_url' => 'https://open.feishu.cn/open-apis/auth/v3/tenant_access_token/internal',
            'send_url' => 'https://open.feishu.cn/open-apis/message/v4/batch_send/',
            'other_params' => [
                'upload_media_url' => 'https://open.feishu.cn/open-apis/im/v1/files',
                'upload_image_url' => 'https://open.feishu.cn/open-apis/im/v1/images',
            ],
        ],
    ],
];
