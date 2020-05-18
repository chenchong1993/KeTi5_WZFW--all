<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'api/apiTest',
        'api/apiUserAdd',
        'api/apiUserDelete',
        'api/apiUserUpdate',
        'api/apiSearchResult',
        'api/apiGetUid',
        'api/apiLogin',
        'api/apiLogout',
        'api/apiAddTerminalUserLocation',
        'api/apiAddObs',
        'api/apiAddBluetooth',
        'api/apiAddSensor',
        'api/heatMapData',
        'api/apiAddRtUserLocation',
        'api/apiNameSeach',
        'api/apiGetLocationList',
        'api/getUsersByName',
        'api/getUsersByPhone',
        'api/getUsersByUid',
        'api/msgTxAdd',
        'api/msgRxAdd',
        'api/getCarByName',
        'api/memberAdd',
        'api/memberList',
        'api/memberDel',
        'api/apiAddGroup',
        'api/apiDelGroup',
        'api/apiAddPointList',
        'api/apiGetUserAndArea',
        'api/apiAreaDelete',
    ];
}
