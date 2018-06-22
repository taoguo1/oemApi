<?php
namespace App\API\V100\Model;
use Core\Base\Model;
use Core\DB\DBQ;

class News extends Model
{
    //获取最新动态分类
    public function getAritleList($id=0){
        if($id) {
            $data = DBQ::getAll('article', [
                "id",
                "title",
                "content",
                "remarks",
                "pic"
            ], [
                  "category_id" => $id
                ]
            );
            foreach($data as $k=>$v){
                $data[$k]['pic']=OSS_ENDDOMAIN.'/'.$v['pic'];
            }
        }else{
            $data['articleCategory'] = DBQ::getAll('article_category', ["id","name","icon"]);
            $data['articleList'] = DBQ::getAll('article', ["id",
                "title",
                "content",
                "remarks",
                "pic",
                "pics"]);
            foreach($data['articleList'] as $k1=>$v1){
                $data['articleList'][$k1]['pic']=OSS_ENDDOMAIN.'/'.$v1['pic'];
            }
            foreach($data['articleCategory'] as $k2=>$v2){
                $data['articleCategory'][$k2]['icon']=OSS_ENDDOMAIN.'/'.$v2['icon'];
            }
        }
        
        return $data;
    }
  




    public function getDataList($id = 0){
        if($id) {
            $data = DBQ::getAll('article', [
                "id",
                "title",
                "content",
                "remarks",
                "pic"
            ], [
                    "category_id" => $id
                ]
            );
        }else{
            $data = DBQ::getAll('article', ["id","title","content","remarks","pic"]);
        }
        return $data;
    }

    public function getList(){
        $data = DBQ::getAll('article', [
            "id",
            "title",
        	"content",
        	"remarks",
        	"pic"
        ],[
                "category_id" => 4
            ]
        );
        return $data;
    }
    //文章详情
    public function getone($id){
    	$data= DBQ::getRow('article',
    			[	
    					"id",
    					"title",
    					"content",
                        "pic",
                        "pics",
                        "create_time",
    					'last_update_time'
    			],[
    					"id"=>$id
    			]
    			);
    	return $data;
    }


    //获取头条详情
    public function getMyNew($id){
        $data= DBQ::getRow('my_first_news',
                [   
                        "id",
                        "title",
                        "content",
                        "img_url",
                        "create_time"
                ],[
                        "id"=>$id
                ]
                );
        return $data;
    }
    public function getInstuctions(){
        $data = DBQ::getOne('article', [
            "id",
            "title",
            "content"
        ],[
                "category_id" => 3
            ]
        );
        return $data;
    }
    
    public function advertisements(){
    	$data = DBQ::select('article', [
    			"id",
    			"pic",
    			"pics"
    	],[
    			"category_id" => 5
    	]
    	);
    	return $data;
    }

    //获取保险列表
    public function insures(){
    	$data = DBQ::getAll('insure', [
    			"id",
    			"title",
    			"pic",
    			"remarks",
    			"article_source"
    	]);
    	return $data;
    }

    
    public function using(){
    	$data = DBQ::getAll('article', [
    			"id",
    			"title",
    			"remarks",
    			"pic"
    	],[
    			"category_id" => 7
    	]
    	);
    	return $data;
    }
    public function loan(){
    	$data = DBQ::getAll('article', [
    			"id",
    			"title",
    			"remarks",
    			"pic"
    	],[
    			"category_id" => 8
    	]
    	);
    	return $data;
    }
    
    public function agent(){
    	$data = DBQ::getAll('article', [
    			"id",
    			"title",
    			"remarks",
    			"pic"
    	],[
    			"category_id" => 9
    	]
    	);
    	return $data;
    }
    public function headline(){
    	$data = DBQ::getAll('article', [
    			"id",
    			   "title",
    	],[
    			"category_id" => 10
    	]
    	);
    	return $data;
    }
    
    public function getQrcode(){
    	$data = DBQ::getRow('article', [
    			"id",
    			'pic'
    	],[
    			"id" => 76
    	]
    	);
    	return $data;
    }

    public function agreementss(){
    	$data = DBQ::getOne('article', [
    			'id',
    			'content'
    	],[
    			"category_id" => 13
    	]
    	);
    	return $data;
    }
    public function attention(){
        $data=DBQ::getOne('appconfig',['contact_us']);
        return $data;
    }
}