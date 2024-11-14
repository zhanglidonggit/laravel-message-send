<?php

namespace MessageNotification\ServiceProvider;

use MessageNotification\Notice;
use Illuminate\Support\ServiceProvider;

class NoticeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('notice', function ($app) {//test是别名
            $config = config('notice');

            return new Notice($config);
        });

        $this->app->singleton(Notice::class, function ($app) {
            $config = config('notice');

            return new Notice($config);
        });
    }
}
