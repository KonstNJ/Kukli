<?php

use Phalcon\{Crypt, Db\Adapter\Pdo\Postgresql, Loader};
use Phalcon\Cache\{AdapterFactory, CacheFactory};
use Phalcon\Cache;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Di\FactoryDefault;
use Phalcon\Filter\FilterFactory;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Http\Response\Cookies;
use Phalcon\Mvc\Application as BaseApplication;
use Phalcon\Session\{Adapter\Stream as SessionAdapter, Bag as SessionBag, Manager as SessionManager};
use Phalcon\Storage\SerializerFactory;

class App extends BaseApplication
{
	protected function registerServices()
	{
		$di = new FactoryDefault();
		$loader = new Loader();

		$loader
			->registerDirs([
                __DIR__ . '/../apps/components/',
                __DIR__ . '/../apps/components/auth',
                __DIR__ . '/../apps/components/mail',
                __DIR__ . '/../apps/components/http',
                __DIR__ . '/../apps/components/models',
                __DIR__ . '/../apps/components/media',
                __DIR__ . '/../apps/components/session',
                __DIR__ . '/../apps/components/string',
                __DIR__ . '/../apps/components/permissions',
                __DIR__ . '/../apps/components/exception',
            ])
			->registerNamespaces([
				'App\\Router\\Group' => './../apps/router/group',
                'App\\Models' => './../apps/models/',
                'App\\Validation' => './../apps/validation/',
                'App\\Models\\Traits' => './../apps/models/trait/',
			])
			->register();
        $di->setShared('config', function () {
            return include __DIR__ . '/config/config.php';
        });
        $di->set('mysql', function () {
            $config = $this->getConfig();
            return new Mysql(
                [
                    'host'     => $config->database->mysql->host,
                    'username' => $config->database->mysql->username,
                    'password' => $config->database->mysql->password,
                    'dbname'   => $config->database->mysql->dbname,
                    'charset'   => $config->database->mysql->charset
                ]
            );
        });
        $di->set('db', function () {
            $config = $this->getConfig();
            return new Postgresql(
                [
                    'host'     => $config->database->everigin->host,
                    'username' => $config->database->everigin->username,
                    'password' => $config->database->everigin->password,
                    'dbname'   => $config->database->everigin->dbname,
                    'port'     => $config->database->everigin->port,
                    'schema'   => $config->database->everigin->schema
                ]
            );
        });
        $di->set('local', function () {
            $config = $this->getConfig();
            return new Postgresql(
                [
                    'host'     => $config->database->localhost->host,
                    'username' => $config->database->localhost->username,
                    'password' => $config->database->localhost->password,
                    'dbname'   => $config->database->localhost->dbname,
                    'port'     => $config->database->localhost->port,
                    'schema'   => $config->database->localhost->schema
                ]
            );
        });
        $di->set('db_old', function () {
            $config = $this->getConfig();
            return new Postgresql(
                [
                    'host'     => $config->database->content->host,
                    'username' => $config->database->content->username,
                    'password' => $config->database->content->password,
                    'dbname'   => $config->database->content->dbname,
                    'port'     => $config->database->content->port,
                    'schema'   => $config->database->content->schema
                ]
            );
        });
        $di->set('db_users', function () {
            $config = $this->getConfig();
            return new Postgresql(
                [
                    'host'     => $config->database->users->host,
                    'username' => $config->database->users->username,
                    'password' => $config->database->users->password,
                    'dbname'   => $config->database->users->dbname,
                    'port'     => $config->database->users->port,
                    'schema'   => $config->database->users->schema
                ]
            );
        });
        $di->set('clickhouse', function() {
            $config = $this->getConfig()->database->clickhouse->toArray();
            $db = new \ClickHouseDB\Client($config);
            $db->database($config['dbname']);
            $db->setTimeout(1.5);      // 1500 ms
            $db->setTimeout(10);       // 10 seconds
            $db->setConnectTimeOut(5); // 5 seconds
            return $db;
        });
        $di->set('session', function () {
            $session = new SessionManager();
            $files = new SessionAdapter([
                'savePath' => \sys_get_temp_dir(),
            ]);
            $session->setAdapter($files);
            $session->start();
            return $session;
        });
        $di->set('sessionBag', function () {
            $session_bag = new SessionBag("sessionBag");
            return $session_bag;
        });
        $di->setShared('filter', function() use ($di) {
            $filter = new FilterFactory();
            $init = $filter->newInstance();
            $init->set('code', function($val) {
                return \mb_substr($val, 0, 2);
            });
            $init->set('currency', function($val) {
                return \mb_substr($val, 0, 3);
            });
            $init->set('sortdir', function($val) {
                $result = 'asc';
                if(\mb_strtolower($val)=='desc')
                {
                    $result = 'desc';
                }
                return $result;
            });
            $init->set('date', function($val) {
                try {
                    return (new \DateTimeImmutable($val))->format('Y-m-d');
                } catch (\Exception $e) {
                    return null;
                }
            });
            $init->set('datetime', function($val) {
                try {
                    return (new \DateTimeImmutable($val))->format('Y-m-d H:i:s.u');
                } catch (\Exception $e) {
                    return null;
                }
            });
            $init->set('name', function($val) use ($init) {
                /*$val = \preg_replace('/((?>[^\p{L}0-9]+)|( {2,}))/imu', ' ', $val);*/
                return $init->sanitize($val, ['string', 'striptags', 'trim']);
            });
            $init->set('content', function ($val) use ($init) {
                return \nl2br($init->sanitize($val));
            });
            $init->set('money', function($val) use ($init) {
                $format = \sprintf("%01.2f", $val);
                return $init->sanitize($format, 'int');
            });
            $init->set('translate', function($val) {
                $val = \Str::translate($val);
                return $val;
            });
            $init->set('bool', function($val) {
                if(\is_bool($val))
                {
                    return $val ? 't' : 'f';
                }
                $val = (\intval($val, 0)==1 || $val=='t') ? 't' : 'f';
                return $val;
            });
            $init->set('ipv4', function ($val) {
                return \filter_var($val, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
            });
            $init->set('section', function($val) {
                if(\in_array($val, ['center', 'left', 'bottom']))
                {
                    return $val;
                }
                return 'center';
            });
            return $init;
        });
        $di->set('modelsCache', function() {
            $options = [
                'defaultSerializer' => 'Json',
                'lifetime'          => 100,
            ];
            $serializerFactory = new SerializerFactory();
            $adapterFactory    = new AdapterFactory(
                $serializerFactory,
                $options
            );
            $cacheFactory = new CacheFactory($adapterFactory);
            $cache = $cacheFactory->newInstance('apcu');
            return $cache;
        });
        $di->setShared('cache', function () {
            $serializerFactory = new SerializerFactory();
            $adapterFactory    = new AdapterFactory($serializerFactory);
            $options = [
                'defaultSerializer' => 'Json',
                'lifetime'          => 60
            ];
            $adapter = $adapterFactory->newInstance('apcu', $options);
            return new Cache($adapter);
        });
        $di->set('crypt', function () {
            $key = $this->getConfig()->crypt->key;
            $crypt = new Crypt();
            $crypt->setKey($key)
                ->setCipher('aes256');
            return $crypt;
        });
        $di->set('cookies', function() {
            $config = $this->getConfig();
            $key = $config->cookies;
            $cookies = new Cookies();
            $cookies->setSignKey($key);
            return $cookies;
        });
        $di->setShared('mail', function() {
            $config = $this->getConfig()->mail->toArray();
            $mailer = new \Phalcon\Incubator\Mailer\Manager($config);
            return new \Mail($mailer);
        });
        $di->setShared('notifications', function() {
            return new \Notifications();
        });
        $di->set('request', function() {
            return (new Request());
        });
        $di->set('response', function() {
            return (new Response());
        });
		$di->set('router', function() {
			return include './../apps/router/router.php';
		});
        $di->setShared('resp', function() {
            return (new \Resp());
        });
        $di->setShared('params', function() {
            return (new \Params());
        });
        $di->setShared('images', function() {
            return (new \Images());
        });
        $di->setShared('helper', function () {
            return (new \Helper());
        });
        $di->setShared('string', function () {
            return (new \Str());
        });
        $di->setShared('local', function () {
            return (new \Local());
        });
        $di->setShared('model', function() {
            return (new \ModelControl());
        });
        $di->setShared('translate', function() {
            return (new \Translate());
        });
        $di->setShared('guest', function() {
            return (new \GuestInit());
        });
        $di->setShared('token', function () {
            return (new \Token());
        });
        $di->setShared('auth', function() {
            return (new \Auth());
        });
		$this->setDI($di);
	}

	public function init()
	{
		$this->registerServices();
		$this->registerModules([
			'frontend' => [
				'className' => 'App\Frontend\Module',
				'path'      => '../apps/modules/frontend/Module.php',
			],
			'content' => [
				'className' => 'App\Content\Module',
				'path'      => '../apps/modules/content/Module.php',
			],
			'backend'  => [
				'className' => 'App\Backend\Module',
				'path'      => '../apps/modules/backend/Module.php',
			],
			'api'  => [
				'className' => 'App\Api\Module',
				'path'      => '../apps/modules/api/Module.php',
			],
			'users'  => [
				'className' => 'App\Users\Module',
				'path'      => '../apps/modules/users/Module.php',
			]
		]);
		$response = $this->handle($_SERVER['REQUEST_URI']);
		$response->send();
	}
}
