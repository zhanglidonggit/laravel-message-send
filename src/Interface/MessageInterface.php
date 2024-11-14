<?php

namespace MessageNotification\Interface;

interface MessageInterface
{
    const TEXT_MESSAGE = 'text';
    const CARD_MESSAGE = 'card';
    const WECHAT_CHANNEL = 'WeChat'; //企业微信
    const DINGDING_CHANNEL = 'Dingding'; //钉钉
    const FEISHU_CHANNEL = 'Feishu'; //飞书
    const QIYUE_CHANNEL = 'Qiyue'; //企悦

    public function toArray();
    public function setAttrbute($attribute, $value);
    public function getAttrbute($attribute);
    public function hasAttribute($attribute);
    public static function is_json($string);
}
