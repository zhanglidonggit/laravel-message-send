<?php

namespace MessageNotification\Interface;

interface DownloadOrUploadInterface
{
    public function getFileNameExtension(string $url);
    public function getUrlContent(string $url);
}
