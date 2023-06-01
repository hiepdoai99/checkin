<?php
namespace Htqxd\HanetApi\EndPoint;

use Htqxd\HanetApi\EndPoint;

class Device extends EndPoint
{
    public function getEndpoint(): string
    {
        return '/device';
    }

    public function getTokenKey(): string
    {
        return 'token';
    }

    // Start Api Call
    public function getListDevice()
    {
        $options = [];
        return $this->post('/getListDevice', $this->formOptions($options));
    }

    public function getDeviceInfo(string $deviceID)
    {
        $options = [
            'form_params' => [
                'deviceID' => $deviceID
        ]];
        return $this->post('/getDeviceInfo', $this->formOptions($options));
    }

    public function getListDeviceByPlace(string $placeID)
    {
        $options = [
            'form_params' => [
                'placeID' => $placeID
        ]];
        return $this->post('/getListDeviceByPlace', $this->formOptions($options));
    }

    public function updateDevice(array $data)
    {
        $options = [
            'form_params' => [
                'deviceID' => $data['deviceID'],
                'deviceName' => $data['deviceName']
        ]];
        return $this->post('/updateDevice', $this->formOptions($options));
    }

    public function getConnectionStatus(array $deviceIDs)
    {
        $options = [
            'form_params' => [
                'deviceIDs' => implode(',', $deviceIDs),
        ]];
        return $this->post('/getConnectionStatus', $this->formOptions($options));
    }

}
