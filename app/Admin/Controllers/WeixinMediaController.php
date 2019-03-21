<?php

namespace App\Admin\Controllers;

use App\Model\WeixinMedia;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp;
use Illuminate\Http\Request;

class WeixinMediaController extends Controller
{
    protected $redis_weixin_access_token = 'str:weixin_access_token';
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeixinMedia);

        $grid->id('Id');
        $grid->openid('Openid');
        $grid->add_time('Add time');
        $grid->msg_type('Msg type');
        $grid->media_id('Media id');
        $grid->format('Format');
        $grid->msg_id('Msg id');
        $grid->local_file_name('Local file name')->display(function ($file_name) {
            if (substr($file_name, -3, 3) == 'mp4') {
                $common = "<a href='https://gsqq.52self.cn/wx/video/" . $file_name . "'>下载视频</a>";
            } elseif (substr($file_name, -3, 3) == 'amr') {
                $common = "<a href='https://gsqq.52self.cn/wx/voice/" . $file_name . "'>下载语音</a>";
            } else {
                $common = '<img src="https://gsqq.52self.cn/wx/images/' . $file_name . '" width="100px" height="100px">';
            }
            return $common;
        });
        $grid->local_file_path('Local file path');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(WeixinMedia::findOrFail($id));

        $show->id('Id');
        $show->openid('Openid');
        $show->add_time('Add time');
        $show->msg_type('Msg type');
        $show->media_id('Media id');
        $show->format('Format');
        $show->msg_id('Msg id');
        $show->local_file_name('Local file name');
        $show->local_file_path('Local file path');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WeixinMedia);

        $form->text('openid', 'Openid');
        $form->number('add_time', 'Add time');
        $form->text('msg_type', 'Msg type');
        $form->text('media_id', 'Media id');
        $form->text('format', 'Format');
        $form->text('msg_id', 'Msg id');
        $form->text('local_file_name', 'Local file name');
        $form->text('local_file_path', 'Local file path');

        return $form;
    }


    /**
     * 群发消息
     */
    public function sendmsg(Content $content)
    {
        return $content
            ->header('群发消息')
            ->description('description')
            ->body($this->send_msg());
    }

    public function send_msg()
    {
        $form = new Form(new WeixinMedia);
        $form->textarea('content', '群发内容');
        return $form;
    }

    /**
     * 获取微信AccessToken
     */
    public function getWXAccessToken()
    {
        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if (!$token) {        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . env('WEIXIN_APPID') . '&secret=' . env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url), true);
            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token, $token);
            Redis::setTimeout($this->redis_weixin_access_token, 3600);
        }
        return $token;
    }

    /**
     * 群发消息
     */
    public function all(Request $request)
    {
        $text = $request->input('content');
        $access_token = $this->getWXAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $access_token;
        //var_dump($url);exit;
        $client = new GuzzleHttp\Client(['base_url' => $url]);
        $param = [
            "filter" => [
                "is_to_all" => true
            ],
            "text" => [
                "content" => $text
            ],
            "msgtype" => "text"
        ];
        ///var_dump($param);exit;
        $r = $client->Request('POST', $url, [
            'body' => json_encode($param, JSON_UNESCAPED_UNICODE)
        ]);
        //var_dump($r);exit;
        $response_arr = json_decode($r->getBody(), true);
        //echo '<pre>';
        //print_r($response_arr);
        // echo '</pre>';

        if ($response_arr['errcode'] == 0) {
            echo "发送成功";
        } else {
            echo "发送失败";
            echo '</br>';
            echo $response_arr['errmsg'];
        }
    }
}
