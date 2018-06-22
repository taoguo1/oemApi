<?php
namespace App\ADMIN\Controller;

use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use Core\DB\DBQ;

class Goods extends Controller
{

    /**
     *
     * @name 产品查询
     */
    public function index()
    {
        $pageArr = Lib::setPagePars();
        $title = Lib::request('title');
        
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }
        $condition = null;
        ($title) ? $condition['AND']['goods_name[~]'] = $title : null;
        $condition['ORDER'] = [
            'A.id' => 'DESC'
        ];
        $data = $this->M()->getList($pageArr, $condition);
        $this->assign("data", $data);
        // 数据查询
        $this->view();
    }

    /**
     *
     * @name 添加
     */
    public function add($act = null)
    {
        if ($act == 'add') {
            $data = [
                'category_id' => Lib::post('category_id'),
                'goods_name' => Lib::post('goods_name')
            ];
            $insert = $this->M()->add($data);
            if ($insert) {
                Dwz::successDialog($this->M()->modelName, '', 'closeCurrent');
            }
        }
        $goodsCategory = $this->M()->getGoodsCategory();
        $this->assign('goodsCategory', $goodsCategory);
        // 数据查询
        $this->view();
    }

    /**
     *
     * @name 修改
     */
    public function edit($id = 0, $act = null)
    {
        if ($act == 'edit') {
            $data = [
                'category_id' => Lib::post('category_id'),
                'goods_name' => Lib::post('goods_name')
            ];
            $upd = DBQ::upd('goods',$data,['id'=>$id]);
            if ($upd) {
                Dwz::successDialog($this->M()->modelName, '', 'closeCurrent');
            }
        }
        
        $list = DBQ::getRow('goods', '*', [
            'id' => $id
        ]);
        $goodsCategory = $this->M()->getGoodsCategory();
        $this->assign('goodsCategory', $goodsCategory);
        $this->assign('list', $list);
        $this->view();
    }

    /**
     *
     * @name 删除
     */
    public function del($id = 0)
    {
        $del = DBQ::del('goods', [
            'id' => $id
        ]);
        if ($del) {
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }
}

