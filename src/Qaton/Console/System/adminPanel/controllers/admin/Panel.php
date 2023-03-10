<?php

namespace App\Controllers\Admin;

use VirX\Qaton\Auth;
use VirX\Qaton\HttpHeaders;

// TODO: this only supports FileDatabase so far, give support for others

class Panel
{
    public $data = [];

    public function __construct()
    {
        Auth::guard('/admin/login');
        $this->data['title'] = 'Admin Panel';
        $this->data['user'] = Auth::user();
        $this->data['subtitle'] = 'Dashboard';
        $this->data['models'] = $this->getModels();
    }

    public function keep_alive()
    {
        echo json_encode(['time' => date('H:i:s', time())]);
    }

    public function model(string $model_slug)
    {
        $this->data['model_slug'] = $model_slug;
        $this->data['subtitle'] = '<b>Model:</b> '
                                    . str_replace(':', '/', $model_slug);

        if ($model = $this->getModelInstanceBySlug($model_slug)) {
            $this->data['schema'] = $model->explain();
            $this->data['data'] = $model->withFiles('assets/images/', true)->get(); // TODO: implement pagination
            $this->data['model'] = $model;
            $this->view->section("sections/admin/panel/model", "layouts/admin/panel", $this->data);
        }
    }

    public function table(string $action, string $model_slug, int $id)
    {
        $model = $this->getModelInstanceBySlug($model_slug);
        $this->data['model_slug'] = $model_slug;
        $this->data['model'] = $model;
        $this->data['schema'] = $model->explain();

        switch ($action) {
            case 'view':
            case 'clone':
                $this->data['data'] = $model->where('id', $id)->withFiles('assets/images/', true)->allForeign()->first();
                break;
            case 'edit':
                $this->data['data'] = $model->where('id', $id)->withFiles('assets/images/', true)->allForeign()->first();
                $this->data['foreigners'] = $model->getForeign();
                break;
            case 'create':
                $this->data['foreigners'] = $model->getForeign();
                break;
        }

        switch ($action) {
            case 'view':
                $this->data['subtitle'] = '<b>Model [View]:</b> '
                                            . str_replace(':', '/', $model_slug);
                $this->view->section("sections/admin/panel/table/view", "layouts/admin/panel", $this->data);
                break;

            case 'create':
                $this->data['subtitle'] = '<b>Model [Create]:</b> '
                                            . str_replace(':', '/', $model_slug);
                $this->view->section("sections/admin/panel/table/create", "layouts/admin/panel", $this->data);
                break;

            case 'insert': // to process 'create'
                $this->data['subtitle'] = '<b>Model [Create]:</b> '
                                            . str_replace(':', '/', $model_slug);
                $data = [];
                foreach ($this->data['schema'] as $column => $props) {
                    if (!isset($this->request->post[$column])) {
                        continue;
                    }
                    switch ($this->data['schema'][$column]['type']) {
                        case 'int':
                        case 'integer':
                        case 'foreign':
                            $data[$column] = (int)$this->request->post[$column];
                            break;
                        case 'float':
                        case 'double':
                            $data[$column] = (float)$this->request->post[$column];
                            break;
                        case 'timestamp':
                            break;
                        default:
                            $data[$column] = $this->request->post[$column];
                    }
                }
                if ($model->where('id', $id)->insert($data)) {
                    HttpHeaders::redirect('/admin/panel/model/' . $model_slug);
                }
                $this->view->section("sections/admin/panel/table/create", "layouts/admin/panel", $this->data);
                break;

            case 'edit':
                $this->data['subtitle'] = '<b>Model [Edit]:</b> '
                                            . str_replace(':', '/', $model_slug);
                $this->view->section("sections/admin/panel/table/edit", "layouts/admin/panel", $this->data);
                break;

            case 'update': // to process 'edit'
                $this->data['subtitle'] = '<b>Model [Update]:</b> '
                                            . str_replace(':', '/', $model_slug);

                $data = [];
                foreach ($this->data['schema'] as $column => $props) {
                    if (isset($this->request->post[$column]) && $this->data['schema'][$column]['type'] !== 'masked') {
                        $data[$column] = $this->request->post[$column];
                    }
                }

                if ($model->where('id', $id)->update($data)) {
                    HttpHeaders::redirect('/admin/panel/table/view/' . $model_slug . '/' .  $id);
                }
                $this->view->section("sections/admin/panel/table/update", "layouts/admin/panel", $this->data);
                break;

            case 'clone':
                $this->data['subtitle'] = '<b>Model [Clone]:</b> '
                                            . str_replace(':', '/', $model_slug);

                if ($ids = $model->where('id', $id)->clone()) {
                    if (isset($ids[0])) {
                        HttpHeaders::redirect("/admin/panel/table/edit/{$model_slug}/{$ids[0]}");
                    }
                }
                $this->view->section("sections/admin/panel/table/clone", "layouts/admin/panel", $this->data);
                break;

            case 'delete':
                $this->data['subtitle'] = '<b>Model [Trash]:</b> '
                                            . str_replace(':', '/', $model_slug);
                if ($model->where('id', $id)->delete()) {
                    HttpHeaders::redirect('/admin/panel/model/' . $model_slug);
                }
                $this->view->section("sections/admin/panel/table/delete", "layouts/admin/panel", $this->data);
                break;

            case 'purge':
                $this->data['subtitle'] = '<b>Model [Purge]:</b> '
                                            . str_replace(':', '/', $model_slug);
                if ($model->where('id', $id)->purge()) {
                    HttpHeaders::redirect('/admin/panel/model/' . $model_slug);
                }
                $this->view->section("sections/admin/panel/table/purge", "layouts/admin/panel", $this->data);
                break;
        }
    }

    public function index()
    {
        $this->view->section("sections/admin/panel/", "layouts/admin/panel", $this->data);
    }

    private function getModelInstanceBySlug(string $model_slug)
    {
        $model = str_replace(':', '/', $model_slug);
        $models_dir = _config('APP_PATHS')['MODELS'];
        $models_subpath = mb_substr($models_dir, mb_strlen(_config('APP_PATH')));
        $model_file = $models_dir . $model . '.php';
        $model_with_namespace = substr(_getNamespaceByPath(
            APP_NAME . DIRECTORY_SEPARATOR . $models_subpath . mb_substr($model, 1)
        ) . '\\' . basename($model), 1);

        if (realpath($model_file)) {
            require_once($model_file); // this is not necessary since autoloading has been implemented
            if (class_exists($model_with_namespace)) {
                $model = new $model_with_namespace();
                if (method_exists($model, 'all')) {
                    return $model;
                }
            }
            HttpHeaders::setByCode(500);
            $this->view->render('Errors/error500');
        } else {
            HttpHeaders::setByCode(404);
            $this->view->render('Errors/error404');
        }
        return false;
    }

    private function getModels(string $path = '')
    {
        $models_dir = realpath(_config('APP_PATHS')['MODELS'] . $path);
        $models = [];
        foreach (scandir($models_dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (is_dir(($models_dir . DIRECTORY_SEPARATOR . $item))) {
                $models[$path][] = $this->getModels($path . DIRECTORY_SEPARATOR . $item);
            } else {
                $models[$path][] = $item;
            }
        }
        return $models;
    }
}
