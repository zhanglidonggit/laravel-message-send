<?php

namespace MessageNotification\Interface;

interface GatewayInterface
{
    public function getName();
    public function send(MessageInterface $message);
    public function setHeader(?array $header);
    public function getSendUrl();
    public function getUploadUrl($type = '');
    public function getHeader();
    public function assertSuccessfully(?array $response);
    public function getToken();
    public function getMediaId(string $path);
    public function getImageId(string $path);
    public function getUploaImagedUrl();
}
