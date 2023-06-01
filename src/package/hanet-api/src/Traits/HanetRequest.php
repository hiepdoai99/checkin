<?php
namespace Htqxd\HanetApi\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Htqxd\HanetApi\Http\Response;

trait HanetRequest
{
    private function getClient()
    {
        return new Client([
            'base_uri' => $this->apiHost
        ]);
    }
    
    private function getPath($uri): string
    {
        return $this->getEndpoint() . $uri;
    }

    protected function get($uri, array $headers = [])
    {
        $request = new Request('GET', $this->getPath($uri), $headers);
        return (new Response(
            $this->getClient()->sendAsync($request)->wait(),
            []
        ));
    }

    protected function post($uri, array $options)
    {
        $request = new Request('POST', $this->getPath($uri));
        return (new Response(
            $this->getClient()->sendAsync($request, $options)->wait(),
            []
        ));
    }
}