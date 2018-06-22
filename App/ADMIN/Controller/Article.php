<?php
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
class Article extends Controller {
    /**
     *
     * @name 查询
     */
    public function index($cid = 0) {
        $title = Lib::request ( 'title' );
        $category_id = Lib::request ( 'category_id', 0, 'int' );
        if (! empty ( $cid )) {
            $category_id = $cid;
        }
        $articleCategoryModel = new \App\ADMIN\Model\ArticleCategory();
        $listOptionStr = $articleCategoryModel->getOptionList ( $cid, '', $category_id );
        $start_date = Lib::request ('start_date');
        $end_date = Lib::request ('end_date');
        $recommend_level = Lib::request ( 'recommend_level' );
        $category_idArr = null;
        if (! empty ( $category_id )) {
            $ids = $articleCategoryModel->getCatrgoryIds ( $category_id );
            $idsArr [] = $category_id;
            $category_idArr = $idsArr;
        }

        $condition = null;
        ($title) ? $condition ['AND'] ['title[~]'] = $title : null;
        ($category_idArr) ? $condition ['AND'] ['category_id'] = $category_idArr : null;
        ($start_date) ? $condition ['AND'] ['A.last_update_time[>=]'] =strtotime($start_date. " 00:00:00")*1000: null;
        ($end_date) ? $condition ['AND'] ['A.last_update_time[<=]'] =strtotime($end_date. " 23:59:59")*1000 : null;
        ($recommend_level) ? $condition ['AND'] ['recommend_level'] = $recommend_level : null;
        $condition ['ORDER'] = [
            'A.id' => 'DESC'
        ];

        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];

        }

        $data = $this->M()->getList ( $pageArr, $condition );
        $this->assign ( "data", $data );
        $this->assign ( "listOptionStr", $listOptionStr );
        $this->assign ( "cid", $cid );
        $this->view ();
    }

    /**
     *
     * @name 添加
     */
    public function add($cid = 0, $act = null) {
        if ($act == 'add') {
            $data = [
                'category_id' => Lib::post ( 'category_id' ),
                'title' => Lib::post ( 'title' ),
                'remarks' => Lib::post ( 'remarks' ),
                'content' => Lib::post ( 'content' ),
                'pic' => Lib::post ( 'pic' ),
                'pics' => Lib::post ( 'pics', '', 'array' ),
                'seo_title' => Lib::post ( 'seo_title' ),
                'seo_desc' => Lib::post ( 'seo_desc' ),
                'recommend_level' => Lib::post ( 'recommend_level' ),
                'article_source' => Lib::post ( 'article_source' ),
                'click_number' => Lib::post ( 'click_number' ),
                'author' => Lib::post ( 'author' ),
                'article_keywords' => Lib::post ( 'article_keywords' ),
                'sort' => Lib::post ( 'sort' ),
                'create_time' => Lib::getMs(),
                'last_update_time' => Lib::getMs(),
                'uid' => $_SESSION ['accountId']
            ];
            $insertId = $this->M ()->add ( $data );
            if ($insertId) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }
        $this->assign ( "cid", $cid );
        $articleCategoryModel = new \App\ADMIN\Model\ArticleCategory();
        $categoryOptionStr = $articleCategoryModel->getOptionList ( $cid, '', $cid );
        $this->assign ( 'categoryOptionStr', $categoryOptionStr );
        $this->view ();
    }

    /**
     *
     * @name 删除
     * @param number $id
     */
    public function del($id = 0) {
        if ($this->M ()->del ( $id )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName), $this->M ()->modelName );
        } else {
            Dwz::err ();
        }
    }

    /**
     *
     * @name 批量删除
     */
    public function delAll() {
        $ids = explode ( ',', Lib::post ( 'ids' ) );
        if ($this->M ()->delAll ( $ids )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
        } else {
            Dwz::err ();
        }
    }

    /**
     *
     * @name 编辑
     * @param number $id
     * @param $act
     */
    public function edit($id = 0, $act = null) {
        if ($act == 'edit' && ! empty ( $id )) {
            $data = [
                'category_id' => Lib::post ( 'category_id' ),
                'title' => Lib::post ( 'title' ),
                'remarks' => Lib::post ( 'remarks' ),
                'content' => Lib::post ( 'content' ),
                'pic' => Lib::post ( 'pic' ),
                'pics' => Lib::post ( 'pics', '', 'array' ),
                'recommend_level' => Lib::post ( 'recommend_level' ),
                'article_source' => Lib::post ( 'article_source' ),
                'click_number' => Lib::post ( 'click_number' ),
                'author' => Lib::post ( 'author' ),
                'seo_title' => Lib::post ( 'seo_title' ),
                'seo_desc' => Lib::post ( 'seo_desc' ),
                'article_keywords' => Lib::post ( 'article_keywords' ),
                'sort' => Lib::post ( 'sort' ),
                'last_update_time' => Lib::getMs(),
                'uid' => $_SESSION ['accountId']
            ];
            //p($data);die;
            if ($this->M ()->edit ( $id, $data )) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }

        $list = $this->M ()->db->get ( "article", "*", [
            'id' => $id
        ] );
        // $pid = $list['pid'];
        $articleCategoryModel = new \App\ADMIN\Model\ArticleCategory();
        $categoryOptionStr = $articleCategoryModel->getOptionList ( 0, '', $list ['category_id'] );
        $this->assign ( 'categoryOptionStr', $categoryOptionStr );
        $this->assign ( "list", $list );
        $this->view ();
    }
}