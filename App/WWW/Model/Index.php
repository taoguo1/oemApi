<?php
namespace App\WWW\Model;
use Core\Base\Model;
use Core\DB\DB;
use PDO;
use Core\Lib;

class Index extends Model {

     public function getuseraccount($sql){
        $data = $this->db->query($sql)->fetchAll();
        return $data;
     }

    public function addBillRid($data){
        $this->db->insert('bill',$data);
        return $this->insertID();
    }

    public function addAccountid($data){
        $this->db->insert('user_account',$data);
        return $this->insertID();
    }

}