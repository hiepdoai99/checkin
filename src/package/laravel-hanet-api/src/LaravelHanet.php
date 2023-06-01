<?php

namespace Htqxd\LaravelHanetApi;

use Htqxd\LaravelHanetApi\Models\Hanet;

class LaravelHanet
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $accessToken;

    public \Htqxd\HanetApi\Hanet $hanet;

    public function __construct()
    {
        $hanet = Hanet::first();

        if (!$hanet)
            throw new \Exception('Bạn chưa cài đặt Camera Api');

        $this->clientId = $hanet->client_id;
        $this->clientSecret = $hanet->client_secret;
        $this->accessToken = $hanet->access_token;
        $this->redirectUri = route('hanet.callback');

        // check oAuth

        $this->hanet = new \Htqxd\HanetApi\Hanet([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'access_token' => $this->accessToken ?? '',
        ]);
    }

    public function getHanet()
    {
        return $this->hanet;
    }
}
