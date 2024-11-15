<?php

namespace MessageNotification\Interface;

interface HttpResponseInterface
{
    public function assertSuccessfully(?array $response);
}
