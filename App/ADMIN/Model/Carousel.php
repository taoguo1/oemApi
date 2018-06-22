<?php
namespace App\ADMIN\Model;

use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Extend\Dwz;

class Carousel extends Model{
    public function getList($pageArr = null, $condition = null) {
        $data = $this->page($pageArr, 'carousel', '*', $condition);
        return $data;
    }

    public function add()
    {
        return $this->insert('carousel', [
                'title' => Lib::post ( 'title' ),
                'ad_link' => OSS_ENDDOMAIN.'/'.Lib::post ( 'ad_link' ),
                'link_type' => Lib::post ( 'link_type' ),
				'herf' => Lib::post ( 'herf' ),
                'content' => Lib::post ( 'content' ),
                'status' => 1,
                'ad_type' => Lib::post ( 'ad_type' ),
                //'sort' => Lib::post ( 'sort' ),
                'create_time' => lib::getMs()
        ]);
    }

    public function del($id = 0)
    {
        return DBQ::del('carousel', [
            'id' => $id
        ]);
    }

    public function delAll($ids)
    {
        return DBQ::del('carousel', [
            'id' => $ids
        ]);
    }

    public function edit($id = 0, $data=[])
    {   
		$ad_link=Lib::post ( 'ad_link' );
		$adarray=explode(":",$ad_link);
		if(in_array('https',$adarray)){
			$ad_link=$ad_link;
		}else{
			$ad_link=OSS_ENDDOMAIN.'/'.$ad_link;
		}
        $data=[
                'title' => Lib::post ( 'title' ),
                'ad_link' => $ad_link,
                'link_type' => Lib::post ( 'link_type' ),
				'herf' => Lib::post ( 'herf' ),
                'content' => Lib::post ( 'content' ),
                'status' => 1,
                'ad_type' => Lib::post ( 'ad_type' )
        ];
        return DBQ::upd('carousel', $data, [
            'id' => $id
        ]);
    }
}