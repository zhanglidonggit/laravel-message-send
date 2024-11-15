<?php

namespace MessageNotification\Interface;

interface HttpRequestInterface
{
    public function setHeader(?array $header);
    public function getHeader();
}
