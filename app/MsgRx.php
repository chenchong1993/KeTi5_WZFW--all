<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MsgRx extends Model
{
    protected $table = 'msgs_rx';
    
    /**
     * 获取发送信息对应的终端用户
     * @return [type] [description]
     */
    public function terminalUser()
    {
        return $this->belongsTo('App\TerminalUser');
    }

}
