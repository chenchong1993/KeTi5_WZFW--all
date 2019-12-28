<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MsgTx extends Model
{
    //
    protected $table = 'msgs_tx';


    /**
     * 获取发送信息对应的终端用户
     * @return [type] [description]
     */
    public function terminalUser()
    {
        return $this->belongsTo('App\TerminalUser');
    }
}
