<?php
/**
 * Default configuration for XHGui.
 *
 * To change these, create a called `config.php` file in the same directory,
 * and return an array from there with your overriding settings.
 */

$mongoUri = getenv('XHGUI_MONGO_URI') ?: '127.0.0.1:27017';
$mongoUri = str_replace('mongodb://', '', $mongoUri);

return array(
    'debug'     => false,
    'mode'      => 'development',

    // Can be mongodb, file or upload.

    // For file
    //
    //'save.handler' => 'file',
    //'save.handler.filename' => dirname(__DIR__) . '/cache/' . 'xhgui.data.' . microtime(true) . '_' . substr(md5($url), 0, 6),

    // For upload
    // Saving profile data by upload is only recommended with HTTPS
    // endpoints that have IP whitelists applied.
    //
    // The timeout option is in seconds and defaults to 3 if unspecified.
    //
    //'save.handler'                => 'upload',
    //'save.handler.upload.uri'     => 'https://example.com/run/import',
    //'save.handler.upload.timeout' => 3,


    // For MongoDB
    'save.handler' => 'mongodb',
    'db.host' => sprintf('mongodb://%s', $mongoUri),
    'db.db' => getenv('XHGUI_MONGO_DB') ?: 'xhprof',

    'pdo' => array(
        'dsn' => 'sqlite:/tmp/xhgui.sqlite3',
        'user' => null,
        'pass' => null,
        'table' => 'results'
    ),

    // Allows you to pass additional options like replicaSet to MongoClient.
    // 'username', 'password' and 'db' (where the user is added)
    'db.options' => array(),

    // call fastcgi_finish_request() in shutdown handler
    'fastcgi_finish_request' => true,

    // Profile x in 100 requests. (E.g. set XHGUI_PROFLING_RATIO=50 to profile 50% of requests)
    // You can return true to profile every request.
    'profiler.enable' => function() {
        $ratio = getenv('XHGUI_PROFILING_RATIO') ?: 100;
        return (getenv('XHGUI_PROFILING') !== false) && (mt_rand(1, 100) <= $ratio);
    },

    'profiler.simple_url' => function($url) {
        return preg_replace('/\=\d+/', '', $url);
    },

    //'profiler.replace_url' => function($url) {
    //    return str_replace('token', '', $url);
    //},

    // Options passed to (uprofiler|tideways|xhprof)_enable. Mainly ignored_functions list
    'profiler.options' => array(),

    // UI related settings
    'templates.path' => dirname(__DIR__) . '/src/templates',
    'date.format' => 'M jS H:i:s',
    'detail.count' => 6,
    'page.limit' => 25,
);
