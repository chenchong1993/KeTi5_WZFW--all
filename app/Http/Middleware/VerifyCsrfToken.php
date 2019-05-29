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
        'api/apiAddUserLocation',
        'api/apiAddObs',
        'api/apiAddBluetooth',
        'api/apiAddSensor',
        'api/heatMapData',
        'api/apiAddRtUserLocation',
        'api/apiNameSeach',
    ];
}
