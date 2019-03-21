<?php

namespace App\Http\Controllers\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use App\Model\CartModel;
class CartController extends Controller{
    public $uid;
    public function __construct(){
        $this->middleware(function($request,$next){
            $this->uid=session()->get('uid');
            return $next($request);
        });
    }
    public function index(Request $request){
//        $goods=session()->get('cart_goods');
//        if(empty($goods)){
//            echo "购物车是空的";
//        }else{
//            foreach($goods as $v){
//                echo "GOOD ID".':'.$v;echo "<br/>";
//                $detail=GoodsModel::where(['goods_id'=>$v])->first()->toArray();
//                echo '<pre>';print_r($detail);echo '</pre>';
//            }
//        }
//        session('cart_goods',null);
        //$uid=session()->get('uid');
        $cart_goods = CartModel::where(['uid'=>$this->uid])->get()->toArray();
        if(empty($cart_goods)){
            echo "购物车是空的";
            die;
        }
        if($cart_goods){
            //获取商品最新信息
            foreach($cart_goods as $v){
                $goodsInfo=GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
                $goodsInfo['num']=$v['num'];
                //echo '<pre>';print_r($goodsInfo);echo '</pre>';
                $list[] = $goodsInfo;
            }
        }
        $data = [
            'list'  => $list
        ];
        return view('cart.cartList',$data);

    }
    //添加购物车
    public function cartAdd($goods_id){
        $cart_goods=session()->get('cart_goods');
        //商品是否存在购物车中
        if(!empty($cart_goods)){
            if(in_array($goods_id,$cart_goods)){
                echo "该商品已存在于购物车中";
                die;
            }
        }
        session()->push('cart_goods',$goods_id);
        //减库存
        $where = ['goods_id'=>$goods_id];
        $store = GoodsModel::where($where)->value('store');
        if($store<=0){
            echo '库存不足';
            exit;
        }
        $res=GoodsModel::where($where)->decrement('store');
        if($res){
            header('refresh:2;url=/cart');
            echo "成功添加到购物车,正在跳转";
        }

    }
    public function add2(Request $request){
        $goods_id=$request->input('goods_id');
        $goods_num=$request->input('num');
        //检查库存
        $store=GoodsModel::where(['goods_id'=>$goods_id])->value('store');
        if($store<=0){
            $response = [
                'error' => 5001,
                'msg'   => '库存不足'
            ];
            return $response;
        }
        if(empty($this->uid)){
            $response=[
                'msg'=>"请先登录",
                'error'=>0,
            ];
            return $response;
        }
        //加入购物车表
        $data=[
            'goods_id'  => $goods_id,
            'num'       => $goods_num,
            'add_time'  => time(),
            'uid'       => session()->get('uid'),
            'session_token' => session()->get('u_token')
        ];
        $id=CartModel::insertGetId($data);
        if(empty($id)){
            $response = [
                'error' => 5002,
                'msg'   => '添加购物车失败，请重新添加'
            ];
            return $response;
        }else{
            $response = [
                'error' => 0,
                'msg'   => '添加购物车成功'
            ];
            return $response;

        }


    }
    //删除购物车商品
    public function cartDel($goods_id){
        //判断 商品是否在 购物车中
        $goods = session()->get('cart_goods');
        echo '<pre>';print_r($goods);echo '</pre>';

        if(in_array($goods_id,$goods)){
            //执行删除
            foreach($goods as $k=>$v){
                if($goods_id == $v){
                    session()->pull('cart_goods.'.$k);
                }
            }
            echo '删除成功';
        }else{
            //不在购物车中
            die("商品不在购物车中");
        }
    }
    public function cartDel2(Request $request){
        $goods_id=$request->input('goods_id');
        $res=CartModel::where(['uid'=>$this->uid,'goods_id'=>$goods_id])->delete();
        $response = [
            'error' => 0,
            'msg'   => '删除购物车成功'
        ];
        return $response;
    }
}
