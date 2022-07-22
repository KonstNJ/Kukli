<?php
namespace App\Content\Controllers;

use Phalcon\Db\Enum;

class IndexController extends ControllerBase
{

    public function indexAction(int $id = null)
    {
        $sql = (is_null($id))
            ? \sprintf("select id, getModuesData(modules, '%s') as modules, langOrFirst(content, 'en', '%s') as content from pages where parent is null and active is true order by id limit 1", $this->lang, $this->lang)
            : \sprintf("select id, getModuesData(modules, '%s') as modules, langOrFirst(content, 'en', '%s') as content from pages where id='%d' and active is true order by id limit 1", $this->lang, $this->lang, $id);
        $item = $this->db->query($sql)->fetch(Enum::FETCH_ASSOC);
        $result = $this->decodeJsonb($item['content']);
        $result['id'] = $item['id'];
        $result['modules'] = $this->decodeJsonb($item['modules']);
        $this->resultOk($result);
    }
}