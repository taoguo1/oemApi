<?php
namespace App\API\V200\Controller;

use Core\Base\Controller;
use Core\Lib;

class Demo extends Controller
{

    public function index()
    {
        $data = [
            'status' => 'ok',
            'code' => 10010,
            'msg' => 'ok'
        ];
        \Core\Lib::outputJson($data);
    }

    public function add()
    {
        
        $data = [
            'status' => 'ok',
            'code' => 10010,
            'msg' => 'ok'
        ];
        \Core\Lib::outputJson($data);
    }
}