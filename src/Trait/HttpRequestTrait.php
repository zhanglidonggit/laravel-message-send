<?php

namespace MessageNotification\Trait;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

trait HttpRequestTrait
{
    public function getClient($options = [])
    {
        return new Client($options);
    }

    public function download($url, $query = [], $headers = [])
    {
        try {
            $response = $this->getClient(['verify' => false])->request('get', $url, [
                'headers' => $headers,
                'query' => $query,
            ]);

            if ((int) $response->getStatusCode() == 200) {
                return $response->getBody()->getContents();
            }

            return '';
        } catch (\Exception $ex) {
            return '';
        }
    }

    public function get($url, $query = [], $headers = [])
    {
        return $this->handleResponse($this->getClient()->request('get', $url, [
            'headers' => $headers,
            'query' => $query,
        ]));
    }

    protected function postMutipart($endpoint, $params = [], $headers = [])
    {
        return $this->handleResponse($this->getClient()->request('post', $endpoint, [
            'headers' => $headers,
            'multipart' => $params,
        ]));
    }

    protected function post($endpoint, $params = [], $headers = [])
    {
        return $this->handleResponse($this->getClient()->request('post', $endpoint, [
            'headers' => $headers,
            'form_params' => $params,
        ]));
    }

    protected function postJson($endpoint, $params = [], $headers = [])
    {
        return $this->handleResponse($this->getClient()->request('post', $endpoint, [
            'headers' => $headers,
            'json' => $params,
        ]));
    }

    private function handleResponse(ResponseInterface $response)
    {
        if ((int) $response->getStatusCode() == 200) {
            $res = $response->getBody();

            if ($res) {
                $data = json_decode(html_entity_decode($res), true);

                return $data ?? [];
            }
        }

        return [];
    }
}
