<?php
use Phalcon\Cli\Console;
use Phalcon\Cli\Dispatcher;
use Phalcon\Di\FactoryDefault\Cli as FactoryDefault;
use Phalcon\Loader;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use Phalcon\Filter\FilterFactory;

class CliApp extends Console
{
    protected function registerServices()
    {
        $di = new FactoryDefault();
        $loader = new Loader();
        $dispatcher = new Dispatcher();
        $loader->registerDirs([
            __DIR__ . '/../apps/components/',
            __DIR__ . '/../apps/components/auth',
            __DIR__ . '/../apps/components/http',
            __DIR__ . '/../apps/components/string',
            __DIR__ . '/../apps/components/mail',
            __DIR__ . '/../apps/components/models',
            __DIR__ . '/../apps/components/media',
            __DIR__ . '/../apps/components/session',
            __DIR__ . '/../apps/components/permissions',
        ])
            ->registerNamespaces([
                'App\\Tasks' => './../apps/tasks/',
                'App\\Models' => './../apps/models/',
                'App\\Models\\Traits' => './../apps/models/trait/',
            ])
            ->register();
        $dispatcher->setDefaultNamespace('App\Tasks');
        $di->setShared('config', function () {
            return include __DIR__ . '/config/config.php';
        });
        $di->setShared('dispatcher', $dispatcher);
        $di->setShared('filter', function() use ($di) {
            $filter = new FilterFactory();
            $init = $filter->newInstance();
            $init->set('code', function($val) {
                return mb_substr($val, 0, 2);
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
            $init->set('sortdir', function($val) {
                $result = 'asc';
                if(mb_strtolower($val)=='desc')
                {
                    $result = 'desc';
                }
                return $result;
            });
            $init->set('name', function($val) use ($init) {
                $val = preg_replace('/((?>[^\p{L}0-9]+)|( {2,}))/imu', ' ', $val);
                return $init->sanitize($val, ['string', 'striptags', 'trim']);
            });
            $init->set('content', function ($val) use ($init) {
                $val = nl2br($init->sanitize($val, ['string', 'striptags', 'trim']));
                return $val;
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
                $val = (intval($val, 0)==1 || $val=='t') ? 't' : 'f';
                return $val;
            });
            return $init;
        });
        $di->setShared('string', function () {
            return (new \Str());
        });
        $di->set('db', function () {
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
        $di->set('clickhouse', function() {
            $config = $this->getConfig()->database->clickhouse->toArray();
            $db = new \ClickHouseDB\Client($config);
            $db->database($config['dbname']);
            $db->setTimeout(1.5);      // 1500 ms
            $db->setTimeout(10);       // 10 seconds
            $db->setConnectTimeOut(5); // 5 seconds
            return $db;
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
        $di->setShared('helper', function () {
            return (new \Helper());
        });
        $this->setDI($di);
    }

    public function init($argv)
    {
        $this->registerServices();
        $this->handle($argv);
    }
}