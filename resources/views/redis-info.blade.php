<!DOCTYPE html>
<html>
<head>
    <title>Redis Info</title>
</head>
<body>
    <h1>Redis Configuration</h1>
    <pre>{{ print_r($redisConfig, true) }}</pre>

    <h2>Redis Connection Status</h2>
    <p>Status: <strong>{{ $redisConnected ? 'Connected' : 'Not Connected' }}</strong></p>
    <p>Ping Response: {{ $redisPing }}</p>

    @if($redisConnected)
        <h3>Redis Server Info</h3>
        <pre>{{ print_r($redisInfo, true) }}</pre>
    @else
        <p style="color: red;">Unable to connect to Redis. Check your configuration.</p>
    @endif
</body>
</html>
