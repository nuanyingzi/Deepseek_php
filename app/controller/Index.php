<?php
namespace app\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
        $data = [
            "角色" => "用户",
            "内容" => "hahahah"
        ];
        return json_encode($data);
    }

    public function chat()
    {
        halt(2222);
        return 'hello,' . $name;
    }
}
