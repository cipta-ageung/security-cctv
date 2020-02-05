<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => true,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAAunv7Gi8:APA91bGfNbbQu_nVB8NmbyDCfIz2cHmabuxolv3QGDiWZMKQ71XhG6hjVvOb7ol3L4bhPDqyRCkFM_bGdRGJHi3sL_0kZMjOan-THWNHPWzfJ2fGesct6vHKPviVwDnCTea1hiT2IusJ'),
        'sender_id' => env('FCM_SENDER_ID', '800943970863'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 30.0, // in second
    ],
];
