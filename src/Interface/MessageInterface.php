<?php

namespace MessageNotification\Interface;

interface MessageInterface
{
    public function toArray();
    public function setAttrbute($attribute, $value);
    public function getAttrbute($attribute);
    public function hasAttribute($attribute);
    public static function is_json($string);
}
