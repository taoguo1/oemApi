<?php
namespace App\API\V100\Model;
use Core\Base\Model;
use Core\Lib;
use Core\DB\DBQ;

class NotifyMessage extends Model
{
    public function getList($pageArr = null,$condition = null) {

        $data = $this->getMessagePage($pageArr,'system_message(A)',[
            '[>]user (B)' => [
                'A.uid' => 'id'
            ]
        ],
        [
            "A.id",
            "A.uid",
            "A.user_type",
            "A.status",
            "A.type",
            "A.read_unread",
            "A.title",
            "A.describe",
            "A.content",
            "A.create_time",
        ], $condition);
        return $data;

    }


    private function getMessagePage($pageArr, $table, $join, $columns = null, $where = null)
    {
        $pageNum = $pageArr['pageNum'];//当前页
        $numPerPage = $pageArr['numPerPage'];//,每一页显示多少条

        if($join){
            $totalCount = $this->count($table, $join,'*',$where);
        }else{
            $totalCount = $this->count($table, '*','*',$where);

        }
        $pageCount = ceil($totalCount / $numPerPage);
        $pageNum = ($pageNum <= 1) ? 1 : $pageNum;
        $pageNum = ($pageNum >= $pageCount) ? $pageCount : $pageNum;
        $isFirstPage = ($pageNum <= 1) ? 1 : 0;
        $isLastPage = ($pageNum >= $pageCount) ? 1 : 0;
        $start = ($pageNum - 1) * $numPerPage;
        $isOnePage = 0;
        if ($isFirstPage == 1 && $isLastPage == 1) {
            $isOnePage = 1;
        }
        if($start<=0){$start=0;}
        if ($where) {
            $where['LIMIT'] = [
                $start,
                $numPerPage
            ];
        } else {
            $columns['LIMIT'] = [
                $start,
                $numPerPage
            ];
        }

        $list = $this->select($table, $join, $columns, $where);
        if (empty($list)) {
            $list = [];
        }

        $array = [
            'pageNum' => $pageNum,
            'numPerPage' => $numPerPage,
            'totalCount' => $totalCount,
            'pageCount' => $pageCount,
            'isFirstPage' => $isFirstPage,
            'isLastPage' => $isLastPage,
            'isOnePage' => $isOnePage,
            'list' => $list
        ];

        return $array;
    }

    public function getone($id){
        $data= DBQ::getAll('system_message','*',[
            'id'=>$id
        ]
                );
        return $data;
    }


    public function show ($id="") {

        $content = DBQ::getRow('system_message','*',[
            'id'=>$id
        ]);

            DBQ::upd('system_message',['read_unread'=>1],[

                'id'=>$id
            ]);

            return $content;
        }

    public function read ($uid="") {
        if(DBQ::upd('system_message',['read_unread'=>1],['uid'=>$uid])) return true; 
          return false ;
    }
    //是否全部已读
    public function isAllRead ($uid="") {
        $rest=DBQ::getRow('system_message','*',['read_unread'=>2,'uid'=>$uid]);
        return $rest ;
    }
}