<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use GuzzleHttp\Client;
use think\facade\Validate;

class Chat extends BaseController
{
    public function index()
    {
        halt(121);
    }

    public function chat()
    {
        // 参数验证
        $validate = Validate::rule([
            'content|内容' => 'require|max:2000'
        ]);

        if (!$validate->check(Request::post())) {
            return json([
                'code' => 400,
                'msg'  => $validate->getError(),
                'data' => null
            ]);
        }
        $text = Request::post('content');

        $deepseekApiKey = env('DEEPSEEK_API_KEY');

        try {
            $client = new Client([
                'timeout' => 60,
            ]);
            $response = $client->post('https://api.deepseek.com/v1/chat/completions', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $deepseekApiKey
                ],
                'json' => [
                    'model' => 'deepseek-chat',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $text
                        ]
                    ]
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents(), true);

            if ($statusCode === 200 && isset($result['choices'][0]['message']['content'])) {
                return json([
                    'code' => 200,
                    'msg'  => 'success',
                    'data' => [
                        'content' => $result['choices'][0]['message']['content']
                    ]
                ]);
            }

            return json([
                'code' => 500,
                'msg'  => $result['error']['message'] ?? 'API请求异常',
                'data' => null
            ]);

        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg'  => '服务异常: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }
}
