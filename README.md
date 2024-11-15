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