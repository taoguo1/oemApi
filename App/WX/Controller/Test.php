<?php
namespace App\WX\Controller;

use Core\Base\Controller;

class Test extends  Controller
{
    public function aaa()
    {
        $model = new \App\WX\Model\Test();
        $data = $model->getData();
        print_r($data);
    }
}

