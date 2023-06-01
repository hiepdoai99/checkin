<?php
namespace Htqxd\HanetApi\EndPoint;

use Htqxd\HanetApi\EndPoint;

class Partner extends EndPoint
{
    public function getEndpoint(): string
    {
        return '/partner';
    }

    public function getTokenKey(): string
    {
        return 'access_token';
    }

    // Start Api Call
    public function updateToken(string $partnerToken)
    {
        $options = [
            'form_params' => [
                'partner_token' => $partnerToken
        ]];
        return $this->post('/updateToken', $this->formOptions($options));
    }

    public function addPlacePartner(string $placeID,string $partnerToken)
    {
        $options = [
            'form_params' => [
                'placeID' => $placeID,
                'partner_token' => $partnerToken
        ]];
        return $this->post('/addPlacePartner', $this->formOptions($options));
    }

}
