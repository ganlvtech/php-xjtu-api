<?php
namespace XjtuApi;

class Ssfw extends XjtuApi
{
    public function login(Cas $xjtuCas)
    {
        self::__construct($xjtuCas->getConfig());
        $content = $this->request('GET', 'http://ssfw.xjtu.edu.cn/', [], '打开登录页面失败');
        $this->stringContains($content, '欢迎您', '登录失败');
        return true;
    }
}