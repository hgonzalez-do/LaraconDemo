<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PGInfoController extends Controller
{
    public function index()
    {
        $redisConfig = config('database.redis');

        try {
            $connection = Redis::connection();
            $redisInfo = $connection->info();
            $redisPing = $connection->ping();
            $redisConnected = true;
        } catch (\Exception $e) {
            $redisInfo = [];
            $redisPing = 'N/A';
            $redisConnected = false;
        }

        return view('pg-info', [
            'redisConfig' => $redisConfig,
            'redisInfo' => $redisInfo,
            'info' => $redisInfo,
            'redisPing' => $redisPing,
            'redisConnected' => $redisConnected,
        ]);
    }
}
