<?php
namespace Htqxd\HanetApi;

use Htqxd\HanetApi\Traits\HasAuthorization;

class Hanet
{
    use HasAuthorization;
    
    public string $clientId;

    public string $clientSecret;

    public string $redirectUri;

    public string $accessToken;
    
    public function __construct(array $config)
    {
        $this->clientId = $config['client_id'] ?? '';
        $this->clientSecret = $config['client_secret'] ?? '';
        $this->redirectUri = $config['redirect_uri'] ?? '';
        $this->accessToken = $config['access_token'] ?? '';
    }

    public function __call($name, $arguments)
    {
        $className = 'Htqxd\\HanetApi\\EndPoint\\' . ucfirst($name);
        if (!class_exists($className)) {
            throw new \Exception("Class {$className} not found");
        }
        return new $className($this);
    }
}
