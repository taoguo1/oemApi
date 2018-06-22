<?php

namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\DB\DB;
use Core\DB\DBQ;
use Core\Lib;

class Test1 extends Controller
{
    private $children = "";

    function posterity($fid=1)
    {

       $arr =  DBQ::getAll("agent","id",['pid'=>$fid]);

        foreach ($arr as $k=>$v)
        {
            $this->children .= $v.",";
            $this->posterity($v);
        }

        return   $this->children;
    }

    function show()
    {
        echo $this->posterity(1);
    }

}