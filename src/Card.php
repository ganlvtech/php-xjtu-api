<?php
namespace XjtuApi;

class Card extends XjtuApi
{
    public function login(Cas $cas)
    {
        self::__construct($cas->getConfig());
        $content = $this->request('GET', 'http://card.xjtu.edu.cn/', [], '打开登录页面失败');
        $this->stringContains($content, '你好', '登录失败');
        return true;
    }

    public function basicInfo()
    {
        $content = $this->request('GET', 'http://card.xjtu.edu.cn/CardManage/CardInfo/BasicInfo', [], '获取校园卡基本信息失败');
        $matches = $this->match('/姓.*?名：(.*?)<\\/em>.*?学工号：(.*?)<\\/em>.*?校园卡号：(.*?)<\\/em>.*?校园卡余额：(.*?)<\\/em>.*?过渡余额：(.*?)<\\/em>.*?挂失状态：(.*?)<\\/em>.*?冻结状态：(.*?)<\\/em>.*?电子账户\\(电子钱包\\)(.*?)<\\/span>/su', $content, '解析校园卡基本信息页面错误');
        return [
            'name' => html_to_text($matches[1]),
            'stu_num' => html_to_text($matches[2]),
            'card_id' => html_to_text($matches[3]),
            'account' => html_to_text($matches[4]),
            'transfer' => html_to_text($matches[5]),
            'lost' => html_to_text($matches[6]),
            'freeze' => html_to_text($matches[7]),
            'storage' => html_to_text($matches[8]),
        ];
    }

    public function transferAccount($password, $amount)
    {
        $numKeyMap = $this->getNumKeyPad()['map'];
        $encoded_password = '';
        foreach ($password as $char) {
            $encoded_password .= $numKeyMap[$char];
        }
        $encoded_password = $password;
        $checkCode = $this->getCheckCode();
        $json = $this->requestJsonDecode('POST', 'http://card.xjtu.edu.cn/CardManage/CardInfo/TransferAccount', [
            'form_params' => [
                'password' => $encoded_password,
                'checkCode' => $checkCode,
                'amt' => $amount,
                'fcard' => 'bcard',
                'tocard' => 'card',
                'bankno' => '',
                'bankpwd' => '',
            ],
        ], '转账失败，请重试');
        if (!$json['ret']) {
            return $json['msg'];
        }
        return true;
    }

    public function getNumKeyPad()
    {
        $content = $this->request('GET', 'http://card.xjtu.edu.cn/Account/GetNumKeyPadImg', [], '密码键盘加载失败');
        $numKeyPadImg = imagecreatefromstring($content);
        $numKeyPad = [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
        $map = [];
        for ($i = 1; $i <= 10; ++$i) {
            $map[$i] = array_search($i, $numKeyPad);
        }
        // TODO 智能识别
        return [
            'num_key_pad' => $numKeyPad,
            'map' => $map,
        ];
    }

    public function getCheckCode()
    {
        $content = imagecreatefromstring($this->request('GET', 'http://card.xjtu.edu.cn/Account/GetCheckCodeImg', [], '验证码加载失败'));
        $checkCodeImg = imagecreatefromstring($content);
        // TODO 智能识别
        return [
            'check_code' => '1234',
        ];
    }
}
