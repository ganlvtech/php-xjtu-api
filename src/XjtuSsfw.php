<?php
namespace XjtuApi;

class XjtuSsfw extends XjtuApi
{
    public function login(XjtuCas $xjtuCas)
    {
        self::__construct($xjtuCas->getConfig());
        $content = $this->request('GET', 'http://ssfw.xjtu.edu.cn/');
        if (!$content) {
            return $this->responseError('登录失败，请重试');
        }
        if (!$this->find($content, '欢迎您')) {
            return $this->responseError('登录失败，请重试');
        }
        return $this->responseOk();
    }
}