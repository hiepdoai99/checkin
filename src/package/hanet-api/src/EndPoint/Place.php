<?php
namespace Htqxd\HanetApi\EndPoint;

use Htqxd\HanetApi\EndPoint;

class Place extends EndPoint
{
    public function getEndpoint(): string
    {
        return '/place';
    }

    public function getTokenKey(): string
    {
        return 'token';
    }

    // Start Api Call
    public function getPlaces()
    {
        $options = [];
        return $this->post('/getPlaces', $this->formOptions($options));
    }

    public function updatePlace(array $data)
    {
        $options = [
            'form_params' => [
                'placeID' => $data['placeID'],
                'name' => $data['name'],
                'address' => $data['address']
        ]];
        return $this->post('/updatePlace', $this->formOptions($options));
    }

    public function getPlaceInfo(string $placeID)
    {
        $options = [
            'form_params' => [
                'placeID' => $placeID
        ]];
        return $this->post('/getPlaceInfo', $this->formOptions($options));
    }

}
