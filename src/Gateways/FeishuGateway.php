<?php

namespace MessageNotification\Gateways;

use MessageNotification\Interface\MessageInterface;
use MessageNotification\Message\Message;

class FeishuGateway extends Gateway
{
    public function __construct($corpid, $corpsecret, $agentid, $token_url, $send_url, $other_params = [])
    {
        parent::__construct($corpid, $corpsecret, $agentid, $token_url, $send_url, $other_params);
        $this->key = 'feishu_access_token_'.$corpid;
    }

    public function send(MessageInterface $message)
    {
        try {
            $headers = [
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => 'Bearer '.$this->getToken(),
            ];
            $this->setHeader($headers);

            //兼容飞书通知
            if ($message->hasAttribute('content') && Message::is_json($message->getAttrbute('content'))) {
                $message->setAttrbute('content', json_decode($message->getAttrbute('content'), true));
            }

            return $this->sendRequestToClient($message);
        } catch (\Exception $ex) {
            return [
                'code' => $ex->getCode(),
                'message' => $ex->getMessage() ?? '消息发送失败',
            ];
        }
    }

    public function assertSuccessfully(?array $response, string $check_code = 'code', string $msg_code = 'msg')
    {
        if (! isset($response[$check_code]) || isset($response[$check_code]) && (int) $response[$check_code] != 0) {
            throw new \Exception($response[$msg_code] ?? '消息发送失败', (int) $response[$check_code] ?? 1);
        }

        return [
            'code' => 0,
            'message' => 'success',
            'response' => $response,
        ];
    }

    public function getUploaImagedUrl()
    {
        return $this->upload_image_url;
    }

    public function getMediaId(string $path)
    {
        $headers = [
            // 'Content-Type' => 'multipart/form-data; boundary=---7MA4YWxkTrZu0gW', //client发起multipart请求不能加这个 类包里已经加了
            'Authorization' => 'Bearer '.$this->getToken(),
        ];
        [$path,$filename,$extension] = $this->fileSaveCheck($path);
        $params = [
            [
                'name' => 'file',
                'contents' => fopen($path, 'r'),
            ],
            [
                'name' => 'file_type',
                'contents' => $this->getFileType($extension),
            ],
            [
                'name' => 'file_name',
                'contents' => $filename.'.'.$extension,
            ],
        ];
        $response = $this->postMutipart($this->getUploadUrl().'&type='.$this->getFileType($extension), $params, $headers);
        @unlink($path);
        return $this->assertSuccessfully($response);
    }

    public function getFileType(string $extension_name)
    {
        $type = 'stream';

        switch ($extension_name) {
            case in_array($extension_name, ['opus']):
                $type = 'opus';

                break;

            case in_array($extension_name, ['pdf']):
                $type = 'pdf';

                break;

            case in_array($extension_name, ['mp4']):
                $type = 'mp4';

                break;

            case in_array($extension_name, ['doc', 'docx']):
                $type = 'doc';

                break;

            case in_array($extension_name, ['xls', 'xlsx']):
                $type = 'xls';

                break;

            case in_array($extension_name, ['ppt', 'pptx']):
                $type = 'ppt';

                break;
        }

        return $type;
    }

    /**
     * 重载图片上传.
     */
    public function getImageId(string $path)
    {
        try {
            [$path,$filename,$extension] = $this->fileSaveCheck($path);
            $headers = [
                // 'Content-Type' => 'multipart/form-data; boundary=---7MA4YWxkTrZu0gW', //client发起multipart请求不能加这个 类包里已经加了
                'Authorization' => 'Bearer '.$this->getToken(),
            ];
            $params = [
                [
                    'name' => 'image_type',
                    'contents' => 'message',
                    'headers' => [
                        'Content-Disposition: form-data; name="image_type"',
                    ],
                ],
                [
                    'name' => 'image',
                    'contents' => fopen($path, 'r'),
                    'filename' => $filename.'.'.$extension,
                    'headers' => [
                        'Content-Disposition: form-data; name="image"',
                        'Content-Type: application/octet-stream',
                    ],
                ],
            ];
            $response = $this->postMutipart($this->getUploaImagedUrl(), $params, $headers);
            @unlink($path);
            return $this->assertSuccessfully($response);
        } catch (\Exception $ex) {
            return [
                'code' => 1,
                'message' => $ex->getMessage(),
                'response' => [],
            ];
        }
    }

    public function refresh()
    {
        $query = [
            'app_id' => $this->corpid,
            'app_secret' => $this->corpsecret,
        ];
        $data = $this->postJson($this->token_url, $query, ['Content-Type' => 'application/json; charset=utf-8']);

        if ($data['tenant_access_token'] ?? '') {
            $this->cache->set($this->key, $data['tenant_access_token'], $data['expire']);

            return $data['tenant_access_token'];
        }

        throw new \Exception('获取Token失败'.json_encode($data));
    }
}
