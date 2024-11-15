<?php

namespace MessageNotification\Gateways;

use MessageNotification\Interface\AccessTokenInterface;
use MessageNotification\Interface\DownloadOrUploadInterface;
use MessageNotification\Interface\GatewayInterface;
use MessageNotification\Interface\MessageInterface;
use MessageNotification\Trait\HandleFileTrait;
use MessageNotification\Trait\HttpRequestTrait;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

abstract class Gateway implements AccessTokenInterface, DownloadOrUploadInterface, GatewayInterface
{
    use HandleFileTrait;
    use HttpRequestTrait;
    protected string $corpid;
    protected string $corpsecret;
    protected int $agentid;
    protected string $key;
    protected string $cacheNameSpace = 'notice_message_namespace';
    protected ?CacheInterface $cache = null;
    protected int $cacheLifetime = 1500;
    protected string $token_url;
    protected string $send_url;
    protected string $upload_url;
    protected string $upload_image_url;
    protected array $headers = [];
    protected mixed $robotCode;
    protected string $tmp_file_dir = '';
    public function __construct($corpid, $corpsecret, $agentid, $token_url, $send_url, $other_params = [])
    {
        $this->corpid = $corpid;
        $this->corpsecret = $corpsecret;
        $this->agentid = $agentid;
        $this->token_url = $token_url;
        $this->send_url = $send_url;

        if ($other_params['upload_media_url'] ?? false) {
            $this->upload_url = $other_params['upload_media_url'];
        }

        if ($other_params['upload_image_url'] ?? false) {
            $this->upload_image_url = $other_params['upload_image_url'];
        }

        if ($other_params['tmp_file_dir'] ?? false) {
            $this->tmp_file_dir = $other_params['tmp_file_dir'];
        } else {
            $this->tmp_file_dir = 'notice_file_tmp';
        }

        if (! $this->cache) { //初始化缓存存储器
            $this->cache = new Psr16Cache(new FilesystemAdapter($this->cacheNameSpace, $this->cacheLifetime));
        }
    }
    public function setHeader(?array $header = [])
    {
        if ($header) {
            $this->headers = array_merge($this->headers, $header);
        }
    }

    public function getSendUrl()
    {
        return $this->send_url;
    }

    public function getHeader()
    {
        return $this->headers;
    }
    public function getName()
    {
        return \strtolower(str_replace([__NAMESPACE__.'\\', 'Gateway'], '', \get_class($this)));
    }

    public function sendRequestToClient(MessageInterface $message)
    {
        $header = $this->getHeader();
        $request_url = $this->getSendUrl();

        try {
            $messageBody = $message->toArray();
            $response = $this->postJson($request_url, $messageBody, $header);

            return $this->assertSuccessfully($response);
        } catch (\Exception $ex) {
            return [
                'code' => $ex->getCode(),
                'message' => $ex->getMessage() ?? '消息发送失败',
            ];
        }
    }

    public function assertSuccessfully(?array $response, string $check_code = 'errcode', string $msg_code = 'errmsg')
    {
        if (! isset($response[$check_code]) || isset($response[$check_code]) && (int) $response[$check_code] != 0) {
            throw new \Exception($response[$msg_code] ?? '消息发送失败', $response[$check_code] ?? 1);
        }

        return [
            'code' => 0,
            'message' => 'success',
            'response_data' => $response,
        ];
    }

    public function getToken()
    {
        if ($this->cache->get($this->key)) {
            return $this->cache->get($this->key);
        }

        try {
            return $this->refresh();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    public function getFileNameExtension($url)
    {
        return array_slice(explode('.', $url), -1)[0];
    }

    public function getUploadUrl($type = '')
    {
        return $this->upload_url.'?access_token='.$this->getToken();
    }

    public function getMediaId(string $path)
    {
        [$path,$filename,$extension] = $this->fileSaveCheck($path);
        $params = [
            [
                'name' => 'media',
                'contents' => fopen($path, 'r'),
                'filename' => $filename.'.'.$extension,
            ],
            [
                'name' => 'type',
                'contents' => $this->getFileType($extension),
            ],
        ];
        $response = $this->postMutipart($this->getUploadUrl().'&type='.$this->getFileType($extension), $params, []);

        return $this->assertSuccessfully($response);
    }

    public function getUrlContent(string $url)
    {
        $extension_name = $this->getFileNameExtension($url);

        return [$this->download($url), $extension_name];
    }

    public function saveFile(string $url)
    {
        [$content,$extension] = $this->getUrlContent($url);

        if (! $content) {
            return '';
        }
        $file_path = storage_path().'/app';

        $single_file_name = $file_name = md5(time().$url.random_int(0, 99999));
        $file_name = $file_path.'/'.$file_name.'.'.$extension;
        file_put_contents($file_name, $content);

        return [$file_name, $single_file_name]; //返回本地路径
    }

    public function getFileType(string $extension_name)
    {
        $type = 'file';

        switch ($extension_name) {
            case in_array($extension_name, ['jpg', 'gif', 'png', 'bmp']):
                $type = 'image';

                break;

            case in_array($extension_name, ['amr', 'mp3', 'wav']):
                $type = 'voice';

                break;

            case in_array($extension_name, ['mp4']):
                $type = 'video';

                break;
        }

        return $type;
    }

    public function getImageId(string $path)
    {
        return [
            'code' => 0,
            'message' => 'success',
            'response' => [],
        ];
    }

    public function getUploaImagedUrl()
    {
        return '';
    }

    protected function is_url(string $path)
    {
        return filter_var($path, FILTER_VALIDATE_URL) !== false;
    }
}
