<?php
namespace XjtuApi;

class XjtuCas extends XjtuApi
{
    public function login($username, $password)
    {
        $content = $this->request('GET', 'https://cas.xjtu.edu.cn/login');
        if (!$content) {
            return $this->responseError('获取登录凭证失败，请重试');
        }
        $matches = $this->match('/name="lt" value="(.*?)".*?name="execution" value="(.*?)".*?name="_eventId" value="(.*?)"/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        $content = $this->request('POST', 'https://cas.xjtu.edu.cn/login', [
            'form_params' => [
                'username' => $username,
                'password' => $password,
                // 'code' => '',
                'lt' => $matches[1],
                'execution' => $matches[2],
                '_eventId' => $matches[3],
            ],
        ]);
        if (!$content) {
            return $this->responseError('登录失败，请重试');
        }
        if (!$this->find($content, '成功')) {
            return $this->responseError('登录失败，请重试');
        }
        return $this->responseOk();
    }

    public function logout()
    {
        $content = $this->request('GET', 'https://cas.xjtu.edu.cn/logout');
        if (!$content) {
            return $this->responseError('注销失败，请重试');
        }
        if (!$this->find($content, '成功')) {
            return $this->responseError('注销失败，请重试');
        }
        return $this->responseOk();
    }

    public function getClient()
    {
        return $this->client;
    }
}
