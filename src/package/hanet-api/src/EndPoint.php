<?php

namespace Htqxd\HanetApi;

use Htqxd\HanetApi\Traits\HanetRequest;

abstract class EndPoint
{
    use HanetRequest;

    public string $apiHost = 'https://partner.hanet.ai';

    public Hanet $hanet;

    abstract public function getEndpoint(): string;

    public function __construct(Hanet $hanet)
    {
        $this->hanet = $hanet;
    }

    public function getTokenKey() : string
    {
        return 'token';
    }

    public function formOptions(array $options) : array
    {
        $options['form_params'][$this->getTokenKey()] = $this->hanet->accessToken;
        return $options;
    }
}
