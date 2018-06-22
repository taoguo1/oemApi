<?php
namespace App\ADMIN\Model;

use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Extend\Dwz;

class ArticleCategory extends Model
{

    public $listStr = '';

    public $listOptionStr = '';

    public $catrgoryIds = '';

    public function getList($id, $cut)
    {
        $list = DBQ::getAll('article_category', '*', [
            'pid' => $id,
            'ORDER' => [
                'sort' => 'ASC'
            ]
        ]);
        
        foreach ($list as $k => $v) {
            $icon = ! empty($v['icon']) ? OSS_ENDDOMAIN .'/'. $v['icon'] : "";
            $this->listStr .= '<tr target="id" rel="' . $v['id'] . '">
			<td align="center" height="25">' . $v['id'] . '</td>
			<td>' . $cut . $v['name'] . '</td>
			<td align="center">' . $v['alias'] . '</td>
			<td align="center"><img src="' . $icon . '" onerror="javascript:this.src=' . APP_ADMIN_STATIC . 'image/no_pic.png" height="18" /></td>
			<td align="center">' . $v['sort'] . '</td>
			<td align="center">' . (($v['status'] == - 1) ? '<font style="color:red">禁用</font>' : '正常') . '</td>
			</tr>';
            self::getList($v['id'], $cut . '|--');
        }
        return $this->listStr;
    }

    public function getCatrgoryIds($id)
    {
        $list = self::select('article_category', '*', [
            'pid' => $id,
            'ORDER' => [
                'sort' => 'ASC'
            ]
        ]);
        
        foreach ($list as $k => $v) {
            $this->catrgoryIds[] = $v['id'];
            self::getCatrgoryIds($v['id']);
        }
        return $this->catrgoryIds;
    }

    public function add()
    {
        return $this->insert('article_category', [
            'name' => Lib::post('name'),
            'alias' => Lib::post('alias'),
            'status' => Lib::post('status'),
            'icon' => Lib::post('icon'),
            'sort' => Lib::post('sort'),
            'pid' => Lib::post('pid')
        ]);
    }

    public function getOptionList($id, $cut, $selectId = 0)
    {
        $list = DBQ::getAll('article_category', '*', [
            'pid' => $id,
            'ORDER' => [
                'sort' => 'ASC'
            ]
        ]);
        
        foreach ($list as $k => $v) {
            if ($selectId == $v['id']) {
                $this->listOptionStr .= '<option selected value="' . $v['id'] . '">' . $cut . $v['name'] . '</option>';
            } else {
                $this->listOptionStr .= '<option value="' . $v['id'] . '">' . $cut . $v['name'] . '</option>';
            }
            
            self::getOptionList($v['id'], $cut . '|--', $selectId);
        }
        return $this->listOptionStr;
    }

    public function del($id = 0)
    {
        if ($this->has('article_category', [
            'pid' => $id
        ])) {
            Dwz::err('该分类下还有子分类，请先删除子分类');
            return false;
        } else {
            // 查询该分类下是否还有资讯
            
            if ($this->has('article', [
                'category_id' => $id
            ])) {
                Dwz::err('该分类下还有资讯信息，请先删除资讯信息');
                return false;
            } else {
                return $this->delete('article_category', [
                    'id' => $id
                ]);
            }
        }
    }

    public function edit($id = 0)
    {
        return $this->update('article_category', [
            'name' => Lib::post('name'),
            'alias' => Lib::post('alias'),
            'status' => Lib::post('status'),
            'icon' => Lib::post('icon'),
            'sort' => Lib::post('sort'),
            'pid' => Lib::post('pid')
        ], [
            'id' => $id
        ]);
    }
}