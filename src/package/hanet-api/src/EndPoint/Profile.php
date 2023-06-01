<?php
namespace Htqxd\HanetApi\EndPoint;

use Htqxd\HanetApi\EndPoint;

class Profile extends EndPoint
{
    public function getEndpoint(): string
    {
        return '/profile';
    }

    public function getTokenKey(): string
    {
        return 'token';
    }

    // Start Api Call
    public function getProfile()
    {
        $options = [];
        return $this->post('/getProfile', $this->formOptions($options));
    }

}
