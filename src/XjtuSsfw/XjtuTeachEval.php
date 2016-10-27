<?php
namespace XjtuApi\XjtuSsfw;

use XjtuApi\XjtuSsfw;

class XjtuTeachEval extends XjtuSsfw
{
    public function index()
    {
        $content = $this->request('GET', 'http://ssfw.xjtu.edu.cn/index.portal?.p=Znxjb20ud2lzY29tLnBvcnRhbC5zaXRlLmltcGwuRnJhZ21lbnRXaW5kb3d8ZjExNjF8dmlld3xub3JtYWx8YWN0aW9uPXF1ZXJ5');
        if (!$content) {
            return $this->responseError('页面加载失败');
        }
        $matches = $this->match('/<fieldset.*?>(.*?)<\\/fieldset>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        $description = $matches[1];
        $matches = $this->match('/<table.*?id="queryGridf1161">(.*?)<\\/table>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        $content = $matches[1];
        $matches = $this->match_all('/<tr.*?>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<a href="(.*?)">(.*?)<\\/a>.*?<\\/tr>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误');
        }
        $response = [];
        foreach ($matches as $match) {
            $response[] = [
                'school' => html_to_text($match[1]), // 开课院系
                'course_id' => html_to_text($match[2]), // 课程代码
                'course_name' => html_to_text($match[3]), // 课程名称
                'eval_count' => html_to_text($match[4]), // 已评次数
                'teacher_name' => html_to_text($match[5]), // 上课教师
                'teacher_type' => html_to_text($match[6]), // 教师类别
                'eval_state' => html_to_text($match[7]), // 评教状态
                'eval_url' => html_to_text($match[8]), // href
                'eval_option' => html_to_text($match[9]), // 操作
            ];
        }
        return $this->responseOk([
            'description' => html_to_text($description),
            'response' => $response,
        ]);
    }

    public function getForm($url)
    {
        $content = $this->request('GET', $url, [
            'base_uri' => 'http://ssfw.xjtu.edu.cn/index.portal',
        ]);
        if (!$content) {
            return $this->responseError('页面加载失败');
        }
        $matches = $this->match('/<form.*?id="pjform".*?action="(.*?)".*?>(.*?)<\\/form>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误1');
        }
        $action = $matches[1];
        $content = $matches[2];
        // 抓取文字说明部分
        $matches = $this->match('/(学年学期：.*?)<\\/td>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误2');
        }
        $info = html_to_text($matches[1]);
        $form = [];
        // 抓取表单的文本域部分
        $matches = $this->match_all('/<textarea.*?name="(.*?)"?>(.*?)<\\/textarea>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误3');
        }
        foreach ($matches as $match) {
            $form[] = [
                'type' => 'textarea',
                'name' => $match[1],
                'value' => html_to_text($match[2]),
            ];
        }
        // 抓取表单的动态生成部分
        $matches = $this->match('/^(.*?)id="zbdfTable".*?>(.*?)<\\/table>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误4');
        }
        $hidden_content = $matches[1];
        $content = $matches[2];
        // 抓取最开始的几个隐藏表单
        $matches = $this->match_all('/<input.*?type="hidden".*?name="(.*?)".*?value="(.*?)".*?>/u', $hidden_content);
        if (!$matches) {
            return $this->responseError('内容解析错误5');
        }
        foreach ($matches as $match) {
            $form[] = [
                'type' => 'hidden',
                'name' => $match[1],
                'value' => $match[2],
            ];
        }
        // 抓取教师评价的每一行
        $matches = $this->match_all('/<tr>.*?<td.*?>(.*?)<\\/td>.*?<td>(.*?)<\\/td>.*?<td>(.*?)<\\/td>(.*?)<\\/tr>/su', $content);
        if (!$matches) {
            return $this->responseError('内容解析错误6');
        }
        foreach ($matches as $match) {
            // 抓取教师评价的每一行的隐藏表单
            $hidden_content = $match[3];
            test_dump($hidden_content);
            $input_matches = $this->match_all('/<input.*?name="(.*?)".*?type="hidden".*?value="(.*?)".*?>/u', $hidden_content);
            if (!$input_matches) {
                return $this->responseError('内容解析错误7');
            }
            // 抓取教师评价的每一行单选框
            $checkbox_content = $match[4];
            $input_matches = $this->match_all('/<input.*?type="checkbox".*?name="(.*?)"(.*?)value="(.*?)".*?>/u', $checkbox_content);
            if (!$input_matches) {
                return $this->responseError('内容解析错误8');
            }
            $checkbox = [];
            foreach ($input_matches as $input_match) {
                test_dump($input_match[2]);
                $checkbox[] = [
                    'type' => 'checkbox',
                    'name' => $input_match[1],
                    'checked' => $this->find($input_match[2], 'checked="checked"'),
                    'value' => $input_match[3],
                ];
            }
            // 教师评价的每一行内容
            $line = [
                'level1' => html_to_text($match[1]),
                'level2' => html_to_text($match[2]),
                'description' => html_to_text($match[3]),
                'checkbox' => $checkbox,
            ];
            $form[] = $line;
        }
        return $this->responseOk([
            'action' => $action,
            'info' => $info,
            'form' => $form,
        ]);
    }

}