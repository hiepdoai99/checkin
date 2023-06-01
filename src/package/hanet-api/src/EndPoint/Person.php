<?php
namespace Htqxd\HanetApi\EndPoint;

use GuzzleHttp\Psr7\Utils;
use Htqxd\HanetApi\EndPoint;

class Person extends EndPoint
{
    public function getEndpoint(): string
    {
        return '/person';
    }

    public function getTokenKey(): string
    {
        return 'token';
    }

    // Start Api Call
    public function register()
    {
        $options = [
            'multipart' => [
                [
                  'name' => 'token',
                  'contents' => '<access_token>'
                ],
                [
                  'name' => 'name',
                  'contents' => 'Nguyễn ngọc Mai'
                ],
                [
                  'name' => 'file',
                  'contents' => Utils::tryFopen('/C:/Users/hoang/Desktop/15675993_1564764260207903_5868868848299140302_o.jpg', 'r'),
                  'filename' => '/C:/Users/hoang/Desktop/15675993_1564764260207903_5868868848299140302_o.jpg',
                  'headers'  => [
                    'Content-Type' => '<Content-type header>'
                  ]
                ],
                [
                  'name' => 'aliasID',
                  'contents' => '6789'
                ],
                [
                  'name' => 'placeID',
                  'contents' => '166'
                ],
                [
                  'name' => 'title',
                  'contents' => 'Dev'
                ]
            ],
            'timeout' => 20,
        ];
        return $this->post('/register', $this->formOptions($options));
    }

    public function registerByUrl()
    {
        $options = [
            'multipart' => [
                [
                    'name' => 'token',
                    'contents' => '<ACCESS_TOKEN>'
                ],
                [
                    'name' => 'name',
                    'contents' => 'Như  xinh đẹp'
                ],
                [
                    'name' => 'url',
                    'contents' => '<url>'
                ],
                [
                    'name' => 'aliasID',
                    'contents' => '<aliasID>'
                ],
                [
                    'name' => 'placeID',
                    'contents' => '<placeID>'
                ],
                [
                    'name' => 'title',
                    'contents' => 'test test '
                ],
                [
                    'name' => 'type',
                    'contents' => '0'
                ]
            ],
            'timeout' => 20,
        ];
        return $this->post('/registerByUrl', $this->formOptions($options));
    }

    public function updateByFaceImage()
    {
        $options = [
            'multipart' => [
                [
                    'name' => 'token',
                    'contents' => '<ACCESS_TOKEN>'
                ],
                [
                    'name' => 'file[]',
                    'contents' => Utils::tryFopen('/C:/Users/hoang/Desktop/Nha/2020-12-18-05-26-03.jpg', 'r'),
                    'filename' => '/C:/Users/hoang/Desktop/Nha/2020-12-18-05-26-03.jpg',
                    'headers'  => [
                        'Content-Type' => '<Content-type header>'
                    ]
                ],
                [
                    'name' => 'aliasID',
                    'contents' => '<aliasID>'
                ],
                [
                    'name' => 'placeID',
                    'contents' => '<placeID>'
                ],
                'timeout' => 20,
        ]];
        return $this->post('/updateByFaceImage', $this->formOptions($options));
    }

    public function updateByFaceImageByAliasID()
    {
        $options = [
            'multipart' => [
                [
                    'name' => 'token',
                    'contents' => '<ACCESS_TOKEN>'
                ],
                [
                    'name' => 'file[]',
                    'contents' => Utils::tryFopen('/C:/Users/hoang/Desktop/Nha/2020-12-18-05-26-03.jpg', 'r'),
                    'filename' => '/C:/Users/hoang/Desktop/Nha/2020-12-18-05-26-03.jpg',
                    'headers'  => [
                        'Content-Type' => '<Content-type header>'
                    ]
                ],
                [
                    'name' => 'aliasID',
                    'contents' => '<aliasID>'
                ],
                [
                    'name' => 'placeID',
                    'contents' => '<placeID>'
                ],
                'timeout' => 20,
        ]];
        return $this->post('/updateByFaceImageByAliasID', $this->formOptions($options));
    }

    public function updateByFaceImageByPersonID()
    {
        $options = [
            'multipart' => [
                [
                    'name' => 'token',
                    'contents' => '<ACCESS_TOKEN>'
                ],
                [
                    'name' => 'file[]',
                    'contents' => Utils::tryFopen('/C:/Users/hoang/Desktop/Nha/2020-12-18-05-26-03.jpg', 'r'),
                    'filename' => '/C:/Users/hoang/Desktop/Nha/2020-12-18-05-26-03.jpg',
                    'headers'  => [
                        'Content-Type' => '<Content-type header>'
                    ]
                ],
                [
                    'name' => 'personID',
                    'contents' => '<personID>'
                ],
                [
                    'name' => 'placeID',
                    'contents' => '<placeID>'
                ],
                'timeout' => 20,
        ]];
        return $this->post('/updateByFaceImageByPersonID', $this->formOptions($options));
    }

    public function getListByPlace(string $placeID, string $type='')
    {
        $options = [
            'form_params' => [
                'placeID' => $placeID,
                'type' => $type ?? 0,
        ]];
        return $this->post('/getListByPlace', $this->formOptions($options));
    }

    public function getListByAliasIDAllPlace(string $aliasID)
    {
        $options = [
            'form_params' => [
                'aliasID' => $aliasID,
        ]];
        return $this->post('/getListByAliasIDAllPlace', $this->formOptions($options));
    }

    public function getListByAliasID(string $aliasID, array $placeIDs)
    {
        $options = [
            'form_params' => [
                'aliasID' => $aliasID,
                'placeIDs' => implode(',', $placeIDs),
        ]];
        return $this->post('/getListByAliasID', $this->formOptions($options));
    }

    public function getUserInfoByAliasID(string $aliasID)
    {
        $options = [
            'form_params' => [
                'aliasID' => $aliasID,
        ]];
        return $this->post('/getUserInfoByAliasID', $this->formOptions($options));
    }

    public function getUserInfoByPersonID(string $personID)
    {
        $options = [
            'form_params' => [
                'personID' => $personID,
        ]];
        return $this->post('/getUserInfoByPersonID', $this->formOptions($options));
    }

    public function update(array $data)
    {
        // $data['updates'] = '{"name": "Kim Như","title":"Nhân Viên"}'
        $options = [
            'form_params' => [
                'aliasID' => $data['aliasID'],
                'placeID' => $data['placeID'],
                'updates' => $data['updates'],
        ]];
        return $this->post('/update', $this->formOptions($options));
    }

    public function updateAliasID(string $personID, string $aliasID)
    {
        // $data['updates'] = '{"name": "Kim Như","title":"Nhân Viên"}'
        $options = [
            'form_params' => [
                'aliasID' => $aliasID,
                'personID' => $personID,
        ]];
        return $this->post('/updateAliasID', $this->formOptions($options));
    }

    /**
     * Undocumented function
     *
     * @param string $placeID
     * @param string $date <ngày định dạng yyyy-mm-dd>
     * @return void
     */
    public function getCheckinByPlaceIdInDay(string $placeID, string $date, array $filters = [])
    {
        $options = [
            'form_params' => array_merge([
                    'placeID' => $placeID,
                    'date' => $date,
                    'exType' => '2,1',
                    'type' => '0',
                ], $filters
            )
        ];
        return $this->post('/getCheckinByPlaceIdInDay', $this->formOptions($options));
    }

    /**
     * Undocumented function
     *
     * @param string $placeID
     * @param string $date <ngày định dạng yyyy-mm-dd>
     * @return void
     */
    public function getTotalCheckinByPlaceIdInDay(string $placeID, string $date, array $filters)
    {
        $options = [
            'form_params' => [
                'placeID' => $placeID,
                'date' => $date,
                ...$filters,
        ]];
        return $this->post('/getTotalCheckinByPlaceIdInDay', $this->formOptions($options));
    }

}
