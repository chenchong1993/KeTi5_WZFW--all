<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/9
 * Time: 15:31
 * 用户传感器模型
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class HeatMapData extends Model
{
    protected $table = "heatmapdata";
    public $timestamps =  false;

}