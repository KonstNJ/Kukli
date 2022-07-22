<?php
use Phalcon\Config;
return new Config(
	[
		'database'    => [
            'mysql' => [
                'adapter'     => 'Mysql',
                'host'        => 'localhost',
                'username'    => 'root',
                'password'    => '456456',
                'dbname'      => 'everigin',
                'charset'     => 'utf8',
            ],
			'everigin' => [
				'adapter'     => 'Postgresql',
				'host'        => '18.196.48.202',
				'port'        => 5432,
				'username'    => 'kukli',
				'password'    => 'zNL3uJjjFZZjAVO',
				'dbname'      => 'everigin',
				'schema'      => 'public'
			],
			'content' => [
				'adapter'     => 'Postgresql',
				'host'        => '18.196.48.202',
				'port'        => 5432,
				'username'    => 'kukli',
				'password'    => 'zNL3uJjjFZZjAVO',
				'dbname'      => 'kukli',
				'schema'      => 'public'
			],
            'users' => [
                'adapter'     => 'Postgresql',
                'host'        => '18.196.48.202',
                'port'        => 5432,
                'username'    => 'kukli',
                'password'    => 'zNL3uJjjFZZjAVO',
                'dbname'      => 'kukli_users',
                'schema'      => 'public'
            ],
            'localhost' => [
                'adapter'     => 'Postgresql',
                'host'        => 'localhost',
                'port'        => 5432,
                'username'    => 'postgres',
                'password'    => '456456',
                'dbname'      => 'everigin',
                'schema'      => 'public'
            ],
			'clickhouse' => [
				'host'        => '18.196.48.202',
				'port'        => 8123,
				'username'    => 'default',
				'password'    => 'UhhVxmuehIXHieOm3LOCtIa',
				'dbname'      => 'kukli',
			]
		],
        'auth' => [
            'session' => 'auth_key',
            'cookies' => 'everigin_key',
            'expire' => '+1 month',
        ],
        'service' => [
            'dadata' => [
                'key' => '11a7380c255d4d19941f3ccb4f452c8a54b5afea',
                'secret' => 'b7d82a0c111bbb1d0315d8ad53d5566935401ec6'
            ]
        ],
        'social' => [
            'vk' => [
                'provider' => 'vkontakte',
                'app_id' => '8062629',
                'app_secret' => 'QWCr6BF7RdMPWiNjzKPE',
                'app_key' => '18fa23af18fa23af18fa23af811881250a118fa18fa23af790be27a256b9272312abc06',
            ],
        ],
        'mail' => [
            'driver'     => 'smtp',
            'host'       => 'email-smtp.eu-central-1.amazonaws.com',
            'port'       => 587,
            'encryption' => 'tls',
            'username'   => 'AKIARXIPUDLVL2FDKGM6',
            'password'   => 'BNEmaL4AvlSA2NGOE3cuxRrH+5QNOh8vNaVZSHFzIYDt',
            'from'       => [
                'email' => 'noreply@everigin.com',
                'name'  => '«Everigin»',
            ],
        ],
        'paginator' => [
            'limit' => 25,
        ],
		'dir' => realpath(dirname(__FILE__, 3)) . '/public',
		'images' => [
			'dir' => '/data/',
			'size' => [
				'min' => [
					'h' => 250,
					'w' => 250
				],
				'max' => [
					'h' => 950,
					'w' => 765
				]
			]
		],
        'publicModules' => ['users','offers','blogs','shops','album','pictures','comments','reviews','community'],
        'crypt' => [
            'key' => 'lukQVeNjI4Ca13oHfDm+LBuX7qwPD1IEqqgWelrDfruZym\Y2yF9cMH79i1DQ/69757oDvm9oVW6IORoHBVuHH/+nL9B9OPDWkAPaW7K1MEc/xc4+Xk5tHminB0WjmBz+76Lpvap38GaFdc9nvuBSGShso\BhZp7tf6AMQzU1TcUkDB76TlJqdVaxHQYBjsGSE61jsbukvQcIEX7hQLPh3mNRLWDZyfLrkXkmymNBwv5YV+HzNVV2HIQk+UGW0hyzKeeTay2jsFVzL+X5EzggfANwi2QITHWP3Mlspy7+0HW6LsnGoYVmTd48sirFChBbICUS1SAAXvYyc72PkshG7',
            'cookies' => 'iocmoPYGWdaTiAemXbJTrIOw10B3zDHr2Us5dy3p9fu2v7wJW12NnQwmcbLpRWGQd1zP8j01F9YZml4RXsi4v1uEyNxhKLzV2WS6bdDKHfFVaqtnoHN3+6YKF4eEi8gZvs1p5EjPRTC57OdYtpZg/Ud8yRLOGlVPVr+t3fxd+jg=',
            'public'=>[
                'key' => 'gnBA3Cbnhd1TnzufcfaEcW+MXzngvL9YWqtsls5HW+OQhOzpZBDk8wly3ZjC3SY7SfU/xdh9xXcdokWoyVftQ5zXLlHlQ3J0+c0GC+85hhkU+TMBncCAP3L7trsDQPzNpGP+/wlt4dfVfzBfu7yHSmA15EtJ4cCdkp2gwdc6ogpXId72eYorUfw2JZxmCQthnL5VgzFjUsGthcp8UvGLXbb90OfsbcVL74rjpWd5FdpMmVDRYmntpXAPqbWl3+vOGpn+c4aSyIovlI65QRhnTyPSqVTwXM1nJKOMa6dOEzEzo9g+THLRcmC1HnBuIgQN1cTX+8ugjBKOgPjS7hXz',
                'expire' => '+1 year',
                //'expire' => '+5 minutes',
            ],
            'private'=>[
                'key' => 'Wu/mlF3puV9JGLkJVyASmL82EFfab5BvtqRcbpYk+gXSp/UFOVzrUZp4eFQlB0SiDf2jVlL87yZg+al8GUbvRx2ebZNfbViCvKv/GnHFj1m6BITqJpP+CNwwk5R0HgejoC06vWamNt+RjvKOOenCxSpIwLYOZ+DTfeXvGbZfZxn6BQj3LKEttd6teXwF4KGUxhV8W8bXwZk3i2tYcbIjQerTKhQ4U+ecdsRWUdByiL+aTQTN54kWYQ39znJYuRf7LRwJXTOcQtAV/OyM7jT8zJRmsLtUUAXOLSw0e4UJJ0KedPcRc0Re+ujMUz/89Pr7HjRW0gJIyKQUPNr3i6hLMuBuAGG2/KTbXN1my+pHH9bCHIbuf2BPcNFuLUJbzs8wxOHg5j/suQex8AquJ3508wXMathI8TZ5RSsmVEPUuYC8PgpXG5CaQIqtCognzcQMVk9yMFgSaACHfeqgPTAUiDREpLUIX/nIOfZkbZa2hsHkIMF6JoDXnym7AR/AMvfirMjVIGdduFM1s6hvctSdPPrPrh2Y9DrfYjmzEbXouBZuy+nENmhs3VBhoQTFsrcTuk2xLA2mBRII0vEok4c1aR9eYF6Kl7o+iJ2hzOudljAPuhia9Y0EPJEponX/Yxq/1KzxCSgjLPUX3BPvftlrPkWlPDCf4rndxzPa18oXuXs=',
                'expire' => '+1 year'
            ]
        ],
        'internalToken' => 'eZoeiO8uEdN5VVN82uQ76UXudITXIM53e8J4tsxPnRUwxCdyGADIJ4QqXYjJbVFAPvMainthFnZHaOf7sU9HXs7U1Eg8AvxRwG05NlM5xAa5o9QekDvM8cT1qgfXh1gk6w3v3oqJlsd0lAnIhp5IlJCdsB9frA9ZjpJq+7eyH6aJW0kSmA7Uhkg3nmPFCDT+cHFRcXZM',
    ]
);
