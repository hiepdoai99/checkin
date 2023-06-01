<?php
namespace Htqxd\HanetApi\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Htqxd\HanetApi\Http\Response;

trait HasAuthorization
{
    private string $authHost = 'https://oauth.hanet.com';

    private function getAuthClient()
    {
        return new Client([
            'base_uri' => $this->authHost
        ]);
    }

    public function getAuthorizationUrl() : string
    {
        // https://oauth.hanet.com/oauth2/authorize?response_type=code&client_id=<CLIENT_ID>&redirect_uri=<URL>&scope=full
        $params = [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => 'full',
        ];
        return $this->authHost . '/oauth2/authorize?' . http_build_query($params);
    }
    
    public function getAccessToken(string $code)
    {
        $options = [
            'form_params' => [
                'code' => $code,
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirectUri,
                'client_secret' => $this->clientSecret,
            ]
        ];
        $request = new Request('POST', '/token');
        return (new Response(
            $this->getAuthClient()->sendAsync($request, $options)->wait(),
            []
        ))->json();
    }

    public function refreshToken()
    {
        # code...
        return 'refreshToken';
    }

    public function isAuthorize() : bool
    {
        // check exprire
        // try refresh token
        return true;
    }
}
