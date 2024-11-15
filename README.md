# laravel-message-send
## 安装方法
```
    composer require zhanglidong/laravel-message-send
```

## 配置
```
    在config/app.php  providers 项中添加服务提供者
    MessageNotification\ServiceProvider\NoticeServiceProvider::class

    运行 php artisan vendor:publish 选择上面的提供者或者名称为notice的tag 同步配置文件到config/notice.php
```

## Usage
```
    使用方式请参考 Test\NoticeMessageGatewayTest.php
    
    注意 本工具包所用数据结构与三方文档一致 开发使用过程中请参考相应渠道文档

    企业微信 消息类型请参考 https://developer.work.weixin.qq.com/document/path/96457

    钉钉 消息类型请参考 https://open.dingtalk.com/document/isvapp/send-single-chat-messages-in-bulk?spm=a2c6h.13066369.question.12.1abe4b75saNAbN

    飞书 消息推送请参考 https://open.feishu.cn/document/server-docs/im-v1/batch_message/send-messages-in-batches 
    飞书 消息类型请参考 https://open.feishu.cn/document/server-docs/im-v1/message-content-description/create_json
```