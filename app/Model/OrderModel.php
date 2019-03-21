<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    //
    public $table = 'p_orders';
    public $timestamps = false;
    //生成订单号
    public static function generateOrderSN(){
        return 'xmiao'. date('ymd').rand(1245,9875).rand(1345,9865).'mix';
    }
}
