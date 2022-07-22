<?php
namespace App\Api\Controllers;

use App\Models\Chat;
use App\Models\ChatClickhouse;
use App\Models\Favorite;
use App\Models\Lots;
use App\Models\Products;
use App\Models\Stats;
use App\Models\Users;
use ClickHouseDB\Type\UInt64;
use Phalcon\Db\Column;
use Phalcon\Db\Enum;
use Phalcon\Di;
use Phalcon\Filter;

class IndexController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
    }

    public function indexAction()
    {
        var_dump('Api modules');
    }


}