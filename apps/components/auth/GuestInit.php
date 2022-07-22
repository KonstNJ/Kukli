<?php

use App\Models\Guest;

class GuestInit extends \Helper
{
    /**
     * @return string
     * uuid string
     */
    public function init()
    {
        return $this->find() ?? $this->set();
    }

    /**
     * @return string|null
     * uuid string
     */
    private function find()
    {
        $ip = $this->request->getClientAddress();
        $agent = $this->request->getUserAgent();
        if(\is_null($ip) || \is_null($agent))
        {
            return null;
        }
        $item = $this->getDI()->get('db_users')
            ->query(
                "select getGuestIdent(:ip, :agent) as ident",
                [
                    'ip' => $ip,
                    'agent' => $agent
                ]
            )
            ->fetch(\Phalcon\Db\Enum::FETCH_ASSOC);
        return $item['ident'];
    }

    /**
     * @return string
     * uuid string
     */
    private function set()
    {
        $agent = $this->request->getUserAgent();
        if(\is_null($agent))
        {
            return 'bot';
        }
        $item = new Guest();

        $item->assign([
            'ident' => $this->getDI()->getShared('string')::uuid(),
            'clientip' => $this->request->getClientAddress(),
            'clientagent' => $this->filter->sanitize($agent, ['string', 'striptags', 'trim'])
        ]);
        $item->save();
        return $item->ident;
    }
}