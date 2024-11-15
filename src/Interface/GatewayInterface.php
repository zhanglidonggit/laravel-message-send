<?php

namespace MessageNotification\Interface;

interface GatewayInterface
{
    public function getName();
    public function send(MessageInterface $message);
    public function getSendUrl();
    public function getUploadUrl($type = '');
    public function getMediaId(string $path);
    public function getImageId(string $path);
    public function getUploaImagedUrl();

    //以下方法迁移进HttpRequestInterface
    // public function getHeader();
    // public function setHeader(?array $header);

    //以下方法迁移进HttpResponseInterface
    // public function assertSuccessfully(?array $response);
}
