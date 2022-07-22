<?php
use Phalcon\Mvc\Model as ModelData;
class ModelControl extends \Helper
{
    public static function init($model, ?int $id = null) : ?ModelData
    {
        $item = (!is_null($id) && $id > 0)
            ? $model::findFirstById($id)
            : new $model();
        return $item;
    }
}