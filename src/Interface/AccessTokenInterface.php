<?php

namespace MessageNotification\Interface;

interface AccessTokenInterface
{
    public function getToken();
    public function refresh();
}
