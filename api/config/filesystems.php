<?php



    $root = storage_path('files/');

return [
	'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root'   => $root,
        ],
        'localktp'    => [
            'driver'    =>'local',
            'root'      =>storage_path('files/images')
        ],

        's3' => [
            'driver' => 's3',
            'key'    => env('S3_KEY'),
            'secret' => env('S3_SECRET'),
            'region' => env('S3_REGION'),
            'bucket' => env('S3_BUCKET'),
        ],
    ],
];