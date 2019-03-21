<?php

namespace App\Admin\Controllers;

use App\Model\WeixinMaterial;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use GuzzleHttp;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class WeixinMaterialController extends Controller
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
            ->header('上传文件')
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
        $grid = new Grid(new WeixinMaterial);

        $grid->id('ID');
        $grid->media_id('MEDIA ID');
        $grid->url('URL')->display(function($file_name){
            return '<img src="'.$file_name.'" width="100px" height="100px">';
        });


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
        $show = new Show(WeixinMaterial::findOrFail($id));



        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WeixinMaterial);
        $form->file('media','图片');


        return $form;
    }
    /**
     * 获取微信AccessToken
     */
    public function getWXAccessToken()
    {
        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);
            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;
    }
    /**
     * 上传永久素材
     * @param $file_path
     */
    public function upMaterialTest($file_path)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->getWXAccessToken().'&type=image';
        $client = new GuzzleHttp\Client();
        $response = $client->request('POST',$url,[
            'multipart' => [
                [
                    'name'     => 'media',
                    'contents' => fopen($file_path, 'r')
                ],
            ]
        ]);

        $body = $response->getBody();
        echo $body;echo '<hr>';
        $d = json_decode($body,true);
        //入库
        WeixinMaterial::insertGetId($d);
        echo '<pre>';print_r($d);echo '</pre>';


    }
    public function formShow(Request $request){
//        echo '<pre>';print_r($_POST);echo '</pre>';echo '<hr>';
//        echo '<pre>';print_r($_FILES);echo '</pre>';echo '<hr>';
        //保存文件
        $img_file=$request->file('media');
        //echo '<pre>';print_r($img_file);echo '</pre>';echo '<hr>';
        //原文件名
        $img_origin_name = $img_file->getClientOriginalName();
        echo 'originName: '.$img_origin_name;echo '</br>';
        //获取文件扩展名
        $file_ext = $img_file->getClientOriginalExtension();
        echo 'ext: '.$file_ext;echo '</br>';

        //重命名
        $new_file_name=str_random(10).'.'.$file_ext;
        echo 'ext: '.$new_file_name;echo '</br>';
        //保存文件
        $save_file_path=$request->media->storeAs('form_test',$new_file_name);//服务器保存路径
        echo 'save_file_path:'.$save_file_path;echo '<hr>';
        //上传至微信永久素材
        $this->upMaterialTest($save_file_path);
    }

}
