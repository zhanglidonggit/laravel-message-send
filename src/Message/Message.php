<?php

namespace MessageNotification\Message;

use MessageNotification\Interface\MessageInterface;

class Message implements MessageInterface
{
    public const MESSAGE_TYPES = [
        'text' => 'text', //纯文本消息
        'text_card' => 'text_card', //卡片消息
    ];

    public function __construct(protected array|string $messageObject) {}

    public function toArray()
    {
        if (self::is_json($this->messageObject)) {
            return json_decode($this->messageObject, true);
        }

        return $this->messageObject;
    }

    public function hasAttribute($attribute)
    {
        $messageObject = $this->messageObject;

        if (self::is_json($messageObject)) {
            $messageObject = json_decode($messageObject, true);
        }

        if (isset($messageObject[$attribute])) {
            return true;
        }

        return false;
    }

    public function getAttrbute($attribute)
    {
        $messageObject = $this->messageObject;

        if (self::is_json($messageObject)) {
            $messageObject = json_decode($messageObject, true);
        }

        return $messageObject[$attribute] ?? null;
    }

    public function setAttrbute($attribute, $value)
    {
        $messageObject = $this->messageObject;

        if (self::is_json($messageObject)) {
            $messageObject = json_decode($messageObject, true);
            $messageObject[$attribute] = $value;
            $messageObject = json_encode($messageObject);
        } else {
            $messageObject[$attribute] = $value;
        }
        $this->messageObject = $messageObject;
    }

    public static function is_json($string)
    {
        if (is_string($string)) {
            json_decode($string);

            return json_last_error() == JSON_ERROR_NONE;
        }

        return false;
    }
}
