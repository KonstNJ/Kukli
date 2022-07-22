<?php
namespace App\Backend\Controllers;
use App\Models\Languages;
use Phalcon\Mvc\Controller;
use Phalcon\Filter;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;


abstract class ControllerBase extends Controller
{
    /**
     * @var
     */
    protected $local;
    protected $lang;
    protected $modules;
    protected $sidebar;

    /**
     * Initialize backend modules
     */
    public function initialize()
    {
        // $admin_panel_uri . '/' . $current_module;
        $this->initAssets();
        $this->initLang();
        $this->initLocal();
        $this->initModules();
        $this->view->admin_panel_uri = $this->getPanelUri();
        $this->view->current_module = $this->dispatcher->getControllerName();
        $this->view->current_action = $this->dispatcher->getActionName();
        $this->view->users_info = $this->session->get($this->getDI()->get('config')->auth->session);
        $this->view->curr_url = $this->view->getVar('admin_panel_uri')
                                .'/'.
                                $this->view->getVar('current_module');
    }

    /**
     * @return string
     */
    protected function getCurrUrl()
    {
        $host = $this->getDI()->getShared('helper')->getHost();
        return $host . $this->getPanelUri() . '/' . $this->dispatcher->getControllerName();
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        $host = $this->getDI()->getShared('helper')->getHost();
        return $host . $this->getPanelUri() . '/';
    }

    /**
     * @return string
     */
    protected function getConfigDir()
    {
        return dirname(__DIR__, 1) . '/config/';
    }

    /**
     * @return void
     */
    protected function initModules()
    {
        $dir = $this->getConfigDir();
        $files = ['modules', 'sidebar'];
        foreach ($files as $file)
        {
            if(file_exists($dir . $file . '.php') && is_file($dir . $file . '.php'))
            {
                $this->{$file} = (include $dir . $file. '.php');
                $this->view->{$file} = $this->{$file}->toArray();
            }
        }
    }

    /**
     * @param \Phalcon\Validation $model
     * @param array $data
     * @return \Phalcon\Messages\Messages
     */
    protected function validData(\Phalcon\Validation $validation, array $data)
    {
        return $validation->validate($data);
    }

    /**
     * @return void
     */
    protected function initLocal()
    {
        $dir = $this->getConfigDir();
        $dir .= 'i18n/';
        $messages = [];
        if(file_exists($dir . $this->lang . '.php') && is_file($dir . $this->lang . '.php'))
        {
            require $dir . $this->lang . '.php';
        } else {
            require $dir . 'ru.php';
        }
        $factory      = new TranslateFactory((new InterpolatorFactory()));
        $this->local = $factory->newInstance('array', [
                                'content' => $messages,
                            ]);
        $this->view->local = $this->local;
    }

    protected function initLang()
    {
        $lang = 'ru';
        if($this->session->has('lang'))
        {
            $lang = $this->session->get('lang');
        }
        $this->lang = $lang;
    }

    /**
     * @return string
     */
    protected function getPanelUri()
    {
        //$admin_panel = $this->getDI()->getShared('helper')->router('admin-panel');
        return $this->router->getRouteByName('admin-panel')->getPattern();
    }

    /**
     * @return string
     */
    protected function initUrl()
    {
        $currentController = $this->dispatcher->getControllerName();
        return $this->getPanelUri() . '/'. $currentController;
    }

    /**
     * @param array $sort
     * @return array
     */
    protected function sortDesc(array $sort)
    {
        $result = [];
        $list = $this->getDI()->getShared('helper')->lists($sort);
        foreach ($list as $item)
        {
            $result[$item] = $this->local->_($item);
        }
        return $result;
    }

    /**
     * @param bool $result
     * @param string $msg
     * @param string|null $url
     * @return \Phalcon\Http\ResponseInterface|void
     */
    protected function returnNotice(bool $result, $msg, ?string $url = null)
    {
        if($this->isAjax())
        {
            return $result ? $this->resultOk()
                            : $this->resultError($msg);
        } else {
            $type = $result ? 'success' : 'error';
            return $this->notice($type, $msg, $url);
        }
    }

    /**
     * @param string $type
     * @param string $msg
     * @param string|null $url
     * @return \Phalcon\Http\ResponseInterface|void
     */
    protected function notice(string $type, $msg, ?string $url = null)
    {
        if(in_array($type, ['error', 'notice', 'success', 'warning']))
        {
            if(is_object($msg) || is_array($msg))
            {
                foreach ($msg as $messages)
                {
                    $_messages = (!empty($messages->getMessage()))
                        ? $messages->getMessage()
                        : (string) $messages;
                    $this->flashSession->{$type}($_messages);
                }
            } else {
                $this->flashSession->{$type}($msg);
            }
        }
        if(!is_null($url))
        {
            return $this->response->redirect($url)->send();
        }
    }

    /**
     * @param Filter $filter
     * @param array $data
     * @return array
     */
    protected function parseSection(Filter $filter, array $data) : array
    {
        $result = [];
        if(!empty($data))
        {
            foreach ((new \ArrayIterator($data)) as $item)
            {
                $result[] = [
                    'name' => $filter->sanitize($item['name'], 'name'),
                    'block' => $filter->sanitize($item['block'], 'section'),
                    'content' => $filter->sanitize($item['content'], 'content')
                ];
            }
        }
        return $result;
    }

    /**
     * @return Languages|Languages[]|\Phalcon\Mvc\Model\ResultsetInterface
     */
    protected function initLangs()
    {
        $items = Languages::find([
            'columns' => 'name,code',
            'order' => 'defaults desc, id'
        ]);
        if($items)
        {
            return $items->toArray();
        }
        return [];
    }

    /**
     * @param $model
     * @param int|null $id
     * @return bool
     */
    protected function delete($model, ?int $id = null)
    {
        if($ids = $this->getId($id))
        {
            $items = $model::find([
                'conditions' => 'id in({items:array})',
                'bind' => [
                    'items' => (array) $ids
                ]
            ]);
            if($items->delete())
            {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $data
     * @param string|array $msg
     * @param int $code
     * @return void
     */
    protected function resultOk($data = [], string $msg = 'Ok', int $code = 200)
    {
        $this->getJson([
            'status' => true,
            'msg' => $msg,
            'result' => $data
        ], $code)
        ;
    }

    /**
     * @param string|array $msg
     * @return void
     */
    protected function resultError($msg = 'Error')
    {
        $messages = '';
        if(is_array($msg))
        {
            foreach ($msg as $str)
            {
                $messages .= $str.'<br />';
            }
        } else {
            $messages = $msg;
        }
        $this->getJson([
            'status' => false,
            'msg' => $messages
        ], 400)
        ;
    }

    /**
     * @param int|null $id
     * @return false|int|mixed
     */
    protected function getId(?int $id = null)
    {
        if(is_null($id))
        {
            if($this->request->hasPost('id'))
            {
                return $this->request->getPost('id', 'int');
            }
            return false;
        } else {
            return $id;
        }
    }

    /**
     * @return bool
     */
    protected function isAjax() : bool
    {
        if($this->request->isAjax() || in_array($this->request->getContentType(), ['application/json','application/json;charset=UTF-8']))
        {
            return true;
        }
        return false;
    }

    /**
     * @param $data
     * @param int $code
     * @return mixed
     */
    private function getJson($data, int $code = 200)
    {
        $this->view->disable();
        return $this->getDI()
            ->getShared('resp')
            ->getJson($data, $code)
            ;
    }

    /**
     * @return void
     */
    private function initAssets()
    {
        $this->assets->collection('jsCollection')
            ->addInlineJs("const baseUrl = '".$this->getCurrUrl()."';")
            ->addInlineJs("const adminUrl = '".$this->getBaseUrl()."';")
            ->addJs('assets/js/__/__/coreui.bundle.min.js')
            ->addJs('assets/js/__/__/simplebar.min.js')
            ->addJs('assets/js/__/__/theme.js')
            ->addJs('assets/js/__/__/prism.js')
            ->addJs('assets/js/__/__/prism-autoloader.min.js')
            ->addJs('assets/js/__/__/prism-unescaped-markup.min.js')
            ->addJs('assets/js/__/__/prism-normalize-whitespace.js')
            ->addJs('assets/js/__/__/main.js?'.time())
        ;
        $this->assets->collection('cssCollection')
            ->addCss('assets/css/__/__/prism.css')
            ->addCss('assets/css/__/__/simplebar.css')
            ->addCss('assets/css/__/__/style.css?'.time())
        ;
    }
}