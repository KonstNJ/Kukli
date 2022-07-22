<?php

class Token extends \Helper
{

    /**
     * @return bool
     */
    public function valid()
    {
        try {
            if ($token = $this->getAuthToken()) {
                if ($data = $this->parse($token)) {
                    if($data['roles']=='guest')
                    {
                        $new_token = $this->init($data['id'], $data['roles']);
                        $this->sessionData($new_token, $data);
                        return true;
                    }
                    $expire_date = (new \DateTimeImmutable())->setTimestamp($data['expire'])->getTimestamp();
                    $curr_date = (new \DateTimeImmutable())->getTimestamp();
                    if ($curr_date < $expire_date) {
                        $new_token = $this->init($data['id'], $data['roles']);
                        $this->sessionData($new_token, $data);
                        return true;
                    }
                }
            }
            $this->guest();
            return true;
        } catch (\Exception $exception) {
            \error_log($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
            return false;
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function guest()
    {
        $guest = $this->getDI()->getShared('guest')->init();
        $token = $this->init($guest, 'guest');
        $this->sessionData($token, [
            'id' => $guest,
            'roles' => 'guest'
        ]);
    }

    /**
     * @param string $users_id
     * @param string $role
     * @return string|mixed
     * @throws Exception
     */
    public function init(string $users_uuid, string $role = 'users', int $users_id = 0)
    {
        $config = $this->getDI()->get('config')->crypt->public;
        $token_data = implode('|', [
            (new \DateTimeImmutable($config->expire))->getTimestamp(),
            $users_uuid,
            $role,
            $users_id,
            $this->getDI()->getShared('string')->generator(55, false)
        ]);
        $token = $this->getDI()
            ->getShared('crypt')
            ->encryptBase64($token_data, $config->key);
        return $token;
    }

    /**
     * @param string $users_id
     * @param string $role
     * @return void
     * @throws Exception
     */
    public function auth(string $users_uuid, string $role = 'users', int $users_id = 0)
    {
        $token = $this->init($users_uuid, $role, $users_id);
        $this->sessionData($token, [
            'id' => $users_id,
            'uuid' => $users_uuid,
            'role' => $role
        ]);
    }

    /**
     * @param string $token
     * @return array|false
     */
    public function parse(string $token)
    {
        $config = $this->getDI()->get('config')->crypt->public;
        $data = [];
        if ($parse_token = $this->decodeToken($token, $config->key)) {
            list($data['expire'], $data['uuid'], $data['roles'], $data['id']) = explode("|", $parse_token);
            return $data;
        }
        return false;
    }

    /**
     * @return array|false|string|string[]
     */
    private function getAuthToken()
    {
        $result = '';
        $request = $this->getDI()->get('request');
        if ($request->hasServer('Authorization')) {
            $result = $request->getServer('Authorization');
        }
        if ($request->hasServer('HTTP_AUTHORIZATION')) {
            $result = $request->getServer('HTTP_AUTHORIZATION');
            $result = str_replace('Bearer ', '', trim($result));
        }
        if ($result == '') {
            return false;
        }
        return ($result == '') ? false : $result;
    }

    /**
     * @param string $token
     * @param array $data
     * @return void
     */
    private function sessionData(string $token, array $data)
    {
        $persistent = $this->getDI()->getShared('sessionBag');
        $persistent->set('users', $data);
        $persistent->set('token', $token);
    }

    /**
     * @param string $token
     * @param string $key
     * @return string|false
     */
    private function decodeToken(string $token, string $key)
    {
        try {
            return $this->getDI()->getShared('crypt')->decryptBase64($token, $key);
        } catch (\Exception $e) {
            return false;
        }

    }
}