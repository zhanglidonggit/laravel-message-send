<?php

return [
    'default' => 'WeChat',
    'channels' => [
        'WeChat' => [
            'corpid' => 'wx0e69a1a43fea50ed',
            'corpsecret' => 'o31IMUgjJIHFpniJvyMcQc6vU6uLJA3Y_YkxdoRiZQw',
            'agentid' => '1000035',
            'token_url' => 'https://qyapi.weixin.qq.com/cgi-bin/gettoken',
            'send_url' => 'https://qyapi.weixin.qq.com/cgi-bin/message/send',
            'other_params' => [
                'upload_media_url' => 'https://qyapi.weixin.qq.com/cgi-bin/media/upload',
            ],
        ],
        'Dingding' => [
            'corpid' => 'dinggxzsyw98ywspyqew',
            'corpsecret' => '6EPJ3GTn3Y1FVERTWuns74CRKfASErZQRN0Pir4bhZK1EhWPmNmPnpm9XLDrJZlU',
            'agentid' => '2659579676',
            'token_url' => 'https://api.dingtalk.com/v1.0/oauth2/accessToken',
            'send_url' => 'https://api.dingtalk.com/v1.0/robot/oToMessages/batchSend',
            'other_params' => [
                'upload_media_url' => 'https://oapi.dingtalk.com/media/upload', //钉钉多媒体上传url
                'send_group_url' => 'https://api.dingtalk.com/v1.0/robot/groupMessages/send',
                'send_application_url' => 'https://oapi.dingtalk.com/topapi/message/corpconversation/asyncsend_v2?access_token=',
            ],
        ],
        'Feishu' => [
            'corpid' => 'cli_a7b9af88032a100c',
            'corpsecret' => 'eaVWHnjVHr4woPXcyShFKbJBx4Qa2SCS',
            'agentid' => '0',
            'token_url' => 'https://open.feishu.cn/open-apis/auth/v3/tenant_access_token/internal',
            'send_url' => 'https://open.feishu.cn/open-apis/message/v4/batch_send/',
            'other_params' => [
                'upload_media_url' => 'https://open.feishu.cn/open-apis/im/v1/files',
                'upload_image_url' => 'https://open.feishu.cn/open-apis/im/v1/images',
            ],
        ],
    ],
];
