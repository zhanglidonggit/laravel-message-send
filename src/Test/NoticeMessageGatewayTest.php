<?php

namespace Tests\Feature;

use MessageNotification\Notice;
use Illuminate\Contracts\Console\Kernel;

beforeEach(function () {
    //加载框架配置
    $app = require dirname(dirname(__DIR__)).'/bootstrap/app.php';
    $app->make(Kernel::class)->bootstrap();
    $default_channel = config('notice.default');
    $channels = config('notice.channels');
    $this->config = $channels[$default_channel] ?? [];
});

//企业微信多媒体上传接口获取mediaId

it('test wechat get media id', function () {
    $notice = new Notice;
    $path = $notice->getMediaId('https://cdn.toodudu.com/2024/08/01/f12ec54157b4244723bb92f98830b52f.jpg');
    dd($path);
});

//企业微信发送文本消息

it('test wechat text message send ', function () {
    //文本消息 支持array和json字符串两种形式
    $text_message = [
        'touser' => '@all',
        'msgtype' => 'text',
        'agentid' => $this->config['agentid'],
        'text' => [
            'content' => '企业微信文本消息推送-测试',
        ],
    ];
    // $text_message = json_encode($text_message); //json参数
    $notice = new Notice;
    $data = $notice->send($text_message);
    expect($data['code'])->toBe(0);
});

//企业微信发送图片消息
it('test wechat image message send ', function () {
    $image_message = [
        'touser' => '@all',
        'msgtype' => 'image',
        'agentid' => $this->config['agentid'],
        'image' => [
            'media_id' => '33K96hRJHTGyM0vQNqR8Kktz1QgzPrA5xFAuzNmlmOyLsXalefk2NgajqKzZEQoAj1GCIYViaKOAjJqFx9xM7Lg',
        ],
    ];
    $notice = new Notice;
    $data = $notice->send($image_message);
    expect($data['code'])->toBe(0);
});

//企业微信发送语音消息
it('test wechat voice message send ', function () {
    $voice_message = [
        'touser' => '@all',
        'msgtype' => 'voice',
        'agentid' => $this->config['agentid'],
        'voice' => [
            'media_id' => '33K96hRJHTGyM0vQNqR8Kktz1QgzPrA5xFAuzNmlmOyLsXalefk2NgajqKzZEQoAj1GCIYViaKOAjJqFx9xM7Lg',
        ],
    ];
    $notice = new Notice;
    $data = $notice->send($voice_message);
    expect($data['code'])->toBe(0);
});

//企业微信发送视频消息
it('test wechat video message send ', function () {
    $video_message = [
        'touser' => '@all',
        'msgtype' => 'video',
        'agentid' => $this->config['agentid'],
        'video' => [
            'media_id' => '33K96hRJHTGyM0vQNqR8Kktz1QgzPrA5xFAuzNmlmOyLsXalefk2NgajqKzZEQoAj1GCIYViaKOAjJqFx9xM7Lg',
            'title' => '企业微信视频标题-测试',
            'description' => '企业微信视频内容-测试',
        ],
    ];
    $notice = new Notice;
    $data = $notice->send($video_message);
    expect($data['code'])->toBe(0);
});

//企业微信发送文件消息
it('test wechat file message send ', function () {
    $file_message = [
        'touser' => '@all',
        'msgtype' => 'file',
        'agentid' => $this->config['agentid'],
        'file' => [
            'media_id' => '33K96hRJHTGyM0vQNqR8Kktz1QgzPrA5xFAuzNmlmOyLsXalefk2NgajqKzZEQoAj1GCIYViaKOAjJqFx9xM7Lg',
        ],
    ];
    $notice = new Notice;
    $data = $notice->send($file_message);
    expect($data['code'])->toBe(0);
});

//企业微信发送图文消息
it('test wechat news message send ', function () {
    $news_message = [
        'touser' => '@all',
        'msgtype' => 'news',
        'agentid' => $this->config['agentid'],
        'news' => [
            'articles' => [
                'title' => '企业微信图文消息标题-测试',
                'description' => '企业微信图文消息描述-测试',
                'url' => 'https://www.toodudu.com',
                'picurl' => 'https://cdn.toodudu.com/2024/08/01/f12ec54157b4244723bb92f98830b52f.jpg',
            ],
        ],
    ];
    $notice = new Notice;
    $data = $notice->send($news_message);
    expect($data['code'])->toBe(0);
});

//企业微信发送mpnews图文消息
it('test wechat mpnews message send ', function () {
    $mpnews_message = [
        'touser' => '@all',
        'msgtype' => 'mpnews',
        'agentid' => $this->config['agentid'],
        'mpnews' => [
            'articles' => [
                'title' => '企业微信图文消息标题-测试',
                'thumb_media_id' => '33K96hRJHTGyM0vQNqR8Kktz1QgzPrA5xFAuzNmlmOyLsXalefk2NgajqKzZEQoAj1GCIYViaKOAjJqFx9xM7Lg',
                'author' => '作者信息',
                'content_source_url' => 'https://www.toodudu.com',
                'content' => '企业微信图文消息描述-测试',
                'description' => '企业微信mpnews图文消息描述-测试',
                'digest' => '企业微信digest描述-测试',
            ],
        ],
    ];
    $notice = new Notice;
    $data = $notice->send($mpnews_message);
    expect($data['code'])->toBe(0);
});

//企业微信发送markdown消息
it('test wechat markdown message send ', function () {
    $markdown_message = [
        'touser' => '@all',
        'msgtype' => 'markdown',
        'agentid' => $this->config['agentid'],
        'markdown' => [
            'content' => '企业微信markwown消息-测试',
        ],
    ];
    $notice = new Notice;
    $data = $notice->send($markdown_message);
    expect($data['code'])->toBe(0);
});

//企业微信发送小程序消息
it('test wechat miniprogram message send ', function () {
    $markdown_message = [
        'touser' => '@all',
        'msgtype' => 'miniprogram_notice',
        'agentid' => $this->config['agentid'],
        'miniprogram_notice' => [
            'appid' => 'wx123123123123123',
            'page' => 'pages/index?userid=zhangsan&orderid=123123123',
            'title' => '会议室预订成功通知',
            'description' => '4月27日 16:16',
            'emphasis_first_item' => true,
            'content_item' => [
                [
                    'key' => '会议室',
                    'value' => '402',
                ],
                [
                    'key' => '会议地点',
                    'value' => '广州TIT-402会议室',
                ],
                [
                    'key' => '会议时间',
                    'value' => '2018年8月1日 09:00-09:30',
                ],
                [
                    'key' => '参与人员',
                    'value' => '周剑轩',
                ],
            ],
        ],
    ];
    $notice = new Notice;
    $data = $notice->send($markdown_message);
    expect($data['code'])->toBe(0);
});

//企业微信发送卡片消息
it('test wechat card message send ', function () {
    //卡片消息 支持array和json字符串两种形式
    $text_card_message = [
        'touser' => '@all',
        'msgtype' => 'textcard',
        'agentid' => $this->config['agentid'],
        'textcard' => [
            'title' => '企业微信卡片消息推送标题-测试',
            'description' => '企业微信文本消息推送卡片描述-测试',
            'url' => 'https://www.toodudu.com',
        ],
    ];
    // $text_card_message = json_encode($text_card_message); //json参数
    $notice = new Notice;
    $data = $notice->send($text_card_message);
    expect($data['code'])->toBe(0);
});
//企业微信更多消息类型请参考 https://developer.work.weixin.qq.com/document/path/96457

//钉钉多媒体上传文件获取mediaId

it('test dingding get media id', function () {
    $notice = new Notice;
    $path = $notice->getMediaId('https://cdn.toodudu.com/2024/08/01/f12ec54157b4244723bb92f98830b52f.jpg');
    dd($path);
});

//钉钉文本消息 通知
it('test dingding text message send', function () {
    //文本消息
    /*
        消息结构支持纯数组方式
        $text_message = [
            'userIds' => ['172968233624397665'],
            'msgKey' => 'sampleText',
            'msgParam' => json_encode([
                'content' => '钉钉文本消息推送-测试',
            ]),
        ];

        消息结构支持数组方式+json混排形式
        $text_message = [
            'userIds' => ['172968233624397665'],
            'msgKey' => 'sampleText',
            'msgParam' => [
                'content' => '钉钉文本消息推送-测试',
            ],
        ];

        消息结构支持json格式
        $text_message = json_encode([
            'userIds' => ['172968233624397665'],
            'msgKey' => 'sampleText',
            'msgParam' => [
                'content' => '钉钉文本消息推送-测试',
            ],
        ]);
     */
    $text_message = json_encode([
        'userIds' => ['172968233624397665'],
        'msgKey' => 'sampleText',
        'msgParam' => [
            'content' => '钉钉文本消息推送-测试',
        ],
    ]);
    $notice = new Notice;
    $data = $notice->send($text_message);
    expect($data['code'])->toBe(0);
});

//钉钉卡片消息 通知
it('test dingding card message send', function () {
    //卡片消息
    $text_message = json_encode([
        'userIds' => ['172968233624397665'],
        'msgKey' => 'sampleActionCard',
        'msgParam' => [
            'title' => '钉钉卡片消息标题-测试',
            'text' => '钉钉卡片消息内容-测试',
            'singleTitle' => '查看详情',
            'singleURL' => 'https://www.toodudu.com',
        ],
    ]);

    $text_message = [
        'userIds' => ['172968233624397665'],
        'msgKey' => 'sampleActionCard',
        'msgParam' => json_encode([
            'title' => '钉钉卡片消息标题-测试',
            'text' => '钉钉卡片消息内容-测试',
            'singleTitle' => '查看详情',
            'singleURL' => 'https://www.toodudu.com',
        ]),
    ];
    $notice = new Notice;
    $data = $notice->send($text_message);
    expect($data['code'])->toBe(0);
});

//钉钉markdown消息 通知
it('test dingding markdown message send', function () {
    $markdown_message = [
        'userIds' => ['172968233624397665'],
        'msgKey' => 'sampleMarkdown',
        'msgParam' => json_encode([
            'title' => '钉钉markdown消息标题-测试',
            'text' => '钉钉markdown消息标题-测试',
        ]),
    ];
    $notice = new Notice;
    $data = $notice->send($markdown_message);
    expect($data['code'])->toBe(0);
});

//钉钉图片消息 通知
it('test dingding image message send', function () {
    $image_message = [
        'userIds' => ['172968233624397665'],
        'msgKey' => 'sampleImageMsg',
        'msgParam' => json_encode([
            'photoURL' => 'https://cdn.toodudu.com/2024/08/01/f12ec54157b4244723bb92f98830b52f.jpg',
        ]),
    ];
    $notice = new Notice;
    $data = $notice->send($image_message);
    expect($data['code'])->toBe(0);
});

//钉钉链接消息 通知
it('test dingding link message send', function () {
    $link_message = [
        'userIds' => ['172968233624397665'],
        'msgKey' => 'sampleLink',
        'msgParam' => json_encode([
            'text' => '测试钉钉链接类型消息-测试',
            'title' => '链接标题-测试',
            'picUrl' => '@lADPDf0i-cSGXPLNA8DNBLA', //通过多媒体上传接口获取的mediaId
            'messageUrl' => 'https://www.toodudu.com',
        ]),
    ];
    $notice = new Notice;
    $data = $notice->send($link_message);
    expect($data['code'])->toBe(0);
});

//钉钉语音消息 通知
it('test dingding audio message send', function () {
    $audio_message = [
        'userIds' => ['172968233624397665'],
        'msgKey' => 'sampleAudio',
        'msgParam' => json_encode([
            'mediaId' => '@lADPDf0i-cSGXPLNA8DNBLA',
            'duration' => 10000,
        ]),
    ];
    $notice = new Notice;
    $data = $notice->send($audio_message);
    expect($data['code'])->toBe(0);
});

//钉钉文件消息 通知
it('test dingding file message send', function () {
    $file_message = [
        'userIds' => ['172968233624397665'],
        'msgKey' => 'sampleFile',
        'msgParam' => json_encode([
            'mediaId' => '@lADPDf0i-cSGXPLNA8DNBLA',
            'fileName' => '文件类型消息-测试',
            'fileType' => 'jpg',
        ]),
    ];
    $notice = new Notice;
    $data = $notice->send($file_message);
    expect($data['code'])->toBe(0);
});
//钉钉通知更多消息类型请参考 https://open.dingtalk.com/document/isvapp/send-single-chat-messages-in-bulk?spm=a2c6h.13066369.question.12.1abe4b75saNAbN

//飞书上传图片 - 用于发送消息
it('test feishu image upload', function () {
    $notice = new Notice;
    $data = $notice->getImageId('https://testcdn.ibisaas.com/2024/09/05/519f57225b4c1a33270b9a5c0c76ee59.jpeg');
    dd($data);
    expect($data['code'])->toBe(0);
});

//飞书上传文件
it('test feishu file upload', function () {
    $notice = new Notice;
    $data = $notice->getMediaId('https://testcdn.ibisaas.com/2024/09/05/519f57225b4c1a33270b9a5c0c76ee59.jpeg');
    dd($data);
    expect($data['code'])->toBe(0);
});

//飞书图片消息发送
it('test feishu image message send', function () {
    $image_message = json_encode([
        'msg_type' => 'image',
        'content' => [
            'image_key' => 'img_v3_02gl_3b4b7955-5bea-40f8-9aeb-cfd8c032496g',
        ],
        'open_ids' => ['ou_2be920a7f363c82d32b860e8ebe7b919'],
    ]);
    $notice = new Notice;
    $data = $notice->send($image_message);
    expect($data['code'])->toBe(0);
});

//飞书文本消息发送
it('test feishu text message send', function () {
    $text_message = json_encode([
        'msg_type' => 'text',
        'content' => [
            'text' => '飞书应用文本消息推送-测试',
        ],
        'open_ids' => ['ou_2be920a7f363c82d32b860e8ebe7b919'],
    ]);
    $notice = new Notice;
    $data = $notice->send($text_message);
    expect($data['code'])->toBe(0);
});

//飞书卡片消息发送 - 需要在后台创建卡片
it('test feishu card message send', function () {
    //卡片消息
    $card_content = json_decode('
        {
            "config": {
                "update_multi": true
            },
            "i18n_elements": {
                "zh_cn": [
                    {
                        "tag": "column_set",
                        "flex_mode": "none",
                        "horizontal_spacing": "default",
                        "background_style": "default",
                        "columns": [
                            {
                                "tag": "column",
                                "elements": [
                                    {
                                        "tag": "div",
                                        "text": {
                                            "tag": "plain_text",
                                            "content": "小程序新版本已发布",
                                            "text_size": "normal",
                                            "text_align": "left",
                                            "text_color": "default"
                                        }
                                    }
                                ],
                                "width": "weighted",
                                "weight": 1
                            }
                        ]
                    },
                    {
                        "tag": "action",
                        "actions": [
                            {
                                "tag": "button",
                                "text": {
                                    "tag": "plain_text",
                                    "content": "按钮"
                                },
                                "type": "danger_filled",
                                "width": "default",
                                "size": "medium",
                                "behaviors": [
                                    {
                                        "type": "open_url",
                                        "default_url": "https://www.toodudu.com",
                                        "pc_url": "https://www.toodudu.com",
                                        "ios_url": "https://www.toodudu.com",
                                        "android_url": "https://www.toodudu.com"
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            "i18n_header": {
                "zh_cn": {
                    "title": {
                        "tag": "plain_text",
                        "content": "您有消息需要查收"
                    },
                    "subtitle": {
                        "tag": "plain_text",
                        "content": "审核通过啦"
                    },
                    "template": "blue"
                }
            }
        }', true);

    $text_message = json_encode([
        'msg_type' => 'interactive',
        'card' => $card_content,
        'open_ids' => ['ou_2be920a7f363c82d32b860e8ebe7b919'],
    ]);
    $notice = new Notice;
    $data = $notice->send($text_message);
    expect($data['code'])->toBe(0);
});

/**
 * 飞书群发消息支持的消息类型只有以下几种
 * 消息类型。支持的消息类型有：.
 *
 * text：文本
 * image：图片
 * post：富文本
 * share_chat：分享群名片
 * interactive：卡片
 * 注意：
 *
 * 如果 msg_type 取值为 text、image、post 或者 share_chat，则消息内容需要传入 content 参数内。
 * 如果 msg_type 取值为 interactive，则消息内容需要传入 card 参数内。
 * 富文本类型（post）的消息，不支持使用 md 标签。
 * 各类型的内容如何配置，参见发送消息内容，但需要确保符合当前接口的要求。例如，仅支持以上 5 种消息类型、批量发送富文本消息时不支持 md 标签等。
 */
//飞书消息推送请参考 https://open.feishu.cn/document/server-docs/im-v1/batch_message/send-messages-in-batches
//飞书消息格式请参考 https://open.feishu.cn/document/server-docs/im-v1/message-content-description/create_json
