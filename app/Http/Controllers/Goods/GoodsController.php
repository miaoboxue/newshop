<?php

namespace App\Http\Controllers\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use Illuminate\Support\Facades\Redis;

class GoodsController extends Controller
{

    //商品详情
    public function index($goods_id){
        $goods_key='h_goods_key_'.$goods_id;
        echo $goods_key;
        $goods_info=Redis::hGetAll($goods_key);
        if($goods_info){
            echo 'REDIS';echo '</br>';
            echo '<pre>';print_r($goods_info);echo '</pre>';
        }else{
            echo 'MYSQL';echo '</br>';
            $goods_info=GoodsModel::where(['goods_id'=>$goods_id])->first()->toArray();
            echo '<pre>';print_r($goods_info);echo '</pre>';
        }
        //写入缓存
        $res=Redis::hmset($goods_key,$goods_info);
        //设置过期时间
        redis::expire($goods_key,10);
        //该商品是否存在
        if(!$goods_info){
            header('Refresh:2;url=/login/center');
            echo "该商品不存在,正在跳转到商品列表页面";
            exit;
        }
        $data=[
            'goods'=>$goods_info
        ];
        return view('goods.goods',$data);
    }
    //文件上传视图
    public function uploadIndex(){
        return view('goods.upload');
    }
    //文件上传
    public function uploadDF(Request $request){
        $pdf=$request->file('upload');
        $ext=$pdf->extension();
        if($ext !='pdf'){
            die('请上传PDF格式的文件');
        }
        $res=$pdf->storeAs(date('Ymd'),str_random(5).'.pdf');
        if($res){
            echo '上传成功';
        }
    }
    //搜索
    public function search(Request $request){
        $search = $request->input('s');
        $newslist =GoodsModel::where([['goods_name', 'like', "%$search%"]])->paginate(2);
        return view('goods.search', ['list'=> $newslist,'search'=>$search]);
    }
}
