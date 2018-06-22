<?php
namespace App\API\V100\Controller;

use Core\Base\Controller;

class GetAPISign extends Controller
{

    public function getAPISign()
    {
        $args = [];
        if ($_POST) {
            $args = $_POST;
        }
        $signature = \Core\Sign::getSign($args);
        $data = [
            'status' => 'success',
            'code' => 10000,
            'data' => [
                'timestamp' => $args['timestamp'],
                'signature' => $signature
            ]
        ];
        \Core\Lib::outputJson($data);
    }
}