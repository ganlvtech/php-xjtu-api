<?php
namespace XjtuApi;

class Cas extends XjtuApi
{
    public function login($username, $password)
    {
        $content = $this->request('GET', 'https://cas.xjtu.edu.cn/login', [], '打开登录页面失败');
        $matches = $this->match('/name="lt" value="(.*?)".*?name="execution" value="(.*?)".*?name="_eventId" value="(.*?)"/su', $content, '解析登录凭证失败');
        $content = $this->request('POST', 'https://cas.xjtu.edu.cn/login', [
            'form_params' => [
                'username' => $username,
                'password' => $password,
                // 'code' => '', // 验证码
                'lt' => $matches[1],
                'execution' => $matches[2],
                '_eventId' => $matches[3],
            ],
        ], '发送登录请求失败');
        $this->stringContains($content, '成功', 'CAS登录失败');
        return true;
    }

    public function logout()
    {
        $content = $this->request('GET', 'https://cas.xjtu.edu.cn/logout', [], '发送注销请求失败');
        $this->stringContains($content, '成功', '注销失败');
        return true;
    }
}
