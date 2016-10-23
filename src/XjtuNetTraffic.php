<?php
namespace XjtuApi;

class XjtuNetTraffic extends XjtuApi
{
    private static function iconv($content)
    {
        return iconv('GB2312', 'UTF-8', $content);
    }

    public function request($method, $uri = '', array $options = [])
    {
        return $this->iconv(parent::request($method, $uri, $options));
    }

    public function login($username, $password)
    {
        $content = $this->request('POST', 'http://auth.xjtu.edu.cn/', [
            'form_params' => [
                '__VIEWSTATE' => '',
                'TB_userName' => $username,
                'TB_password' => $password,
                'Button1' => '',
            ],
        ]);
        if (!$content) {
            return $this->responseError('登录失败，请重试');
        }
        if ($this->find($content, '用户名或密码不正确')) {
            return $this->responseError('用户名或密码不正确');
        }
        return $this->responseOk();
    }

    public function current()
    {
        $content = $this->request('GET', 'http://auth.xjtu.edu.cn/current.aspx');
        if (!$content) {
            return $this->responseError('页面加载失败');
        }
        $matches = $this->match('/用户名.*?<td>(.*?)<\\/td>.*?ip.*?<td>(.*?)<\\/td>.*?入流量.*?<td>(.*?)<\\/td>.*?出流量.*?<td>(.*?)<\\/td>.*?费用.*?<td.*?>(.*?)<\\/td>.*?时间.*?<td>(.*?)<\\/td>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        return $this->responseOk([
            'username' => html_to_text($matches[1]),
            'ip' => html_to_text($matches[2]),
            'in' => html_to_text($matches[3]),
            'out' => html_to_text($matches[4]),
            'pay' => html_to_text($matches[5]),
            'time' => html_to_text($matches[6]),
        ]);
    }

    public function history($page = 1)
    {
        $content = $this->request('POST', 'http://auth.xjtu.edu.cn/history.aspx', [
            'form_params' => [
                '__EVENTTARGET' => 'ctl00$mainContent$GridView1',
                '__EVENTARGUMENT' => 'Page$' . $page,
            ],
        ]);
        if (!$content) {
            return $this->responseError('页面加载失败');
        }
        $matches = $this->match('/<table.*?id="ctl00_mainContent_GridView1".*?>(.*?)<\\/table>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        $content = $matches[1];
        $matches = $this->match_all('/<tr.*?>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<\\/tr>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        $response = [];
        foreach ($matches as $val) {
            $response[] = [
                'username' => html_to_text($val[1]),
                'ip' => html_to_text($val[2]),
                'in' => html_to_text($val[3]),
                'out' => html_to_text($val[4]),
                'pay' => html_to_text($val[5]),
                'time' => html_to_text($val[6]),
            ];
        }
        return $this->responseOk($response);
    }

    public function details($year = null, $month = null, $page = 1)
    {
        if (is_null($year) || is_null($month)) {
            $content = $this->request('GET', 'http://auth.xjtu.edu.cn/details.aspx');
        } else {
            $content = $this->request('POST', 'http://auth.xjtu.edu.cn/details.aspx', [
                'form_params' => [
                    '__EVENTTARGET' => 'ctl00$mainContent$GridView1',
                    '__EVENTARGUMENT' => 'Page$' . $page,
                    'ctl00$mainContent$TB_datetime_year' => $year,
                    'ctl00$mainContent$TB_datetime' => $month,
                ],
            ]);
        }
        if (!$content) {
            return $this->responseError('页面加载失败');
        }
        $matches = $this->match('/<table.*?id="ctl00_mainContent_GridView1".*?>(.*?)<\\/table>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        $content = $matches[1];
        $matches = $this->match_all('/<tr.*?>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<\\/tr>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        $response = [];
        foreach ($matches as $val) {
            $response[] = [
                'username' => html_to_text($val[1]),
                'ip' => html_to_text($val[2]),
                'in' => html_to_text($val[3]),
                'out' => html_to_text($val[4]),
                'login_time' => html_to_text($val[5]),
                'logout_time' => html_to_text($val[6]),
            ];
        }
        return $this->responseOk($response);
    }

    public function state()
    {
        $content = $this->request('GET', 'http://auth.xjtu.edu.cn/state.aspx');
        if (!$content) {
            return $this->responseError('页面加载失败');
        }
        $matches = $this->match('/您的帐户费用为：(.*?)<\\/span>.*?当前状态为：(.*?)<\\/span>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        return $this->responseOk([
            'pay' => html_to_text($matches[1]),
            'state' => html_to_text($matches[2]),
        ]);
    }

    public function account()
    {
        $content = $this->request('GET', 'http://auth.xjtu.edu.cn/account.aspx');
        if (!$content) {
            return $this->responseError('页面加载失败');
        }
        $matches = $this->match('/<span.*?id="ctl00_mainContent_Label1".*?>(.*?)<\\/span>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        return $this->responseOk([
            'content' => html_to_text($matches[1]),
        ]);
    }

    public function userinfo()
    {
        $content = $this->request('GET', 'http://auth.xjtu.edu.cn/userinfo.aspx');
        if (!$content) {
            return $this->responseError('页面加载失败');
        }
        $matches = $this->match('/用户名.*?<td>(.*?)<\\/td>.*?真实姓名.*?<td>(.*?)<\\/td>.*?学号.*?<td>(.*?)<\\/td>.*?IP.*?<td>(.*?)<\\/td>.*?邮箱地址.*?<td>(.*?)<\\/td>.*?注册时间.*?<td>(.*?)<\\/td>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        return $this->responseOk([
            'username' => html_to_text($matches[1]),
            'name' => html_to_text($matches[2]),
            'stu_num' => html_to_text($matches[3]),
            'email' => html_to_text($matches[4]),
            'ip' => html_to_text($matches[5]),
            'create_time' => html_to_text($matches[6]),
        ]);
    }

    public function changepwd($old_pwd, $new_pwd)
    {
        $content = $this->request('POST', 'http://auth.xjtu.edu.cn/changepwd.aspx', [
            'form_params' => [
                '__VIEWSTATE' => '',
                'ctl00$mainContent$TB_oldPwd' => $old_pwd,
                'ctl00$mainContent$TB_newPwd' => $new_pwd,
                'ctl00$mainContent$TB_reNewPwd' => $new_pwd,
                'ctl00$mainContent$Button1' => '',
            ],
        ]);
        if (!$content) {
            return $this->responseError('修改密码失败');
        }
        if (!$this->find($content, '操作成功')) {
            return $this->responseError('修改密码失败');
        }
        return $this->responseOk();
    }

    public function logout()
    {
        $content = $this->request('GET', 'http://auth.xjtu.edu.cn/logout.aspx');
        if (!$content) {
            return $this->responseError('注销失败');
        }
        if (!$this->find($content, '用户登录')) {
            return $this->responseError('注销失败');
        }
        return $this->responseOk();
    }
}
