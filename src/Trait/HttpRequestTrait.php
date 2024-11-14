<?php

namespace MessageNotification\Trait;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

trait HttpRequestTrait
{
    public function getClient()
    {
        return new Client;
    }

    public function get($url, $query = [], $headers = [])
    {
        return $this->handleResponse($this->getClient()->request('get', $url, [
            'headers' => $headers,
            'query' => $query,
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
