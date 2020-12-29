<?php

namespace VirX\Qaton;

use VirX\Qaton\Error;

class View
{
    public const PHP_EXT = '.php';

    public $base_url;
    public $page_path;
    public $page_name;
    public $views_path;
    public $view;
    public $data = [];
    public $section;

    public function __construct()
    {
        //
    }

    public function section(string $section = null, string $layout = null, array $data = [])
    {
        $this->setData($data);
        $this->setSection($section);
        $this->render($layout, $this->data);
    }

    public function yeild(string $sub_section_view = null)
    {
        if (is_string($this->section)) {
            if (realpath($this->views_path . DIRECTORY_SEPARATOR . $this->section . DIRECTORY_SEPARATOR . $sub_section_view . self::PHP_EXT)) {
                $this->render($this->section . DIRECTORY_SEPARATOR . $sub_section_view, $this->data);
            }
        }
    }

    public function render(string $view = null, array $data = [])
    {
        $this->setView($view);
        $this->setData($data);
        __debug(['view' => $this, 'data' => $this->data], 'VIEW @' .  __METHOD__ . " >> {$this->view}", 2); // Level 2 only

        if (is_array($this->data)) {
            extract($this->data);
        }
        include($this->view);
    }

    public function fetch(string $view = null, array $data = [])
    {
        $this->setView($view);
        $this->setData($data);
        __debug(['view' => $this, 'data' => $this->data], 'VIEW @' .  __METHOD__ . " >> {$this->view}", 2); // Level 2 only

        if (is_array($this->data)) {
            extract($this->data);
        }
        ob_start();
        include($this->view);
        return ob_get_clean();
    }

    public function baseUrl()
    {
        echo $this->base_url;
    }

    public function pageUrl()
    {
        echo $this->base_url . mb_substr($this->page_path, 1);
    }

    public function pageName()
    {
        echo $this->page_name;
    }

    public function isActive($match, $class = 'active')
    {
        if ($this->page_path == $match) {
            if ($class) {
                echo $class;
            } else {
                return true;
            }
        } else {
            if ($class) {
                echo null;
            } else {
                return true;
            }
        }
    }

    public function setPageName(string $name = null)
    {
        $this->page_name = $name;
    }

    public function setPagePath(string $path = null)
    {
        $this->page_path = $path;
    }

    public function setView(string $view = null)
    {
        if (!$this->view = realpath($this->views_path . DIRECTORY_SEPARATOR . $view . self::PHP_EXT)) {
            throw new Error('View Not Found', ['view' => $view], 1002, __LINE__, __METHOD__, __CLASS__, __FILE__);
        }
    }

    public function setSection($section)
    {
        if (realpath($dir = $this->views_path . DIRECTORY_SEPARATOR . $section)) {
            if (is_dir($dir)) {
                $this->section = $section;
                return;
            }
        }

        throw new Error('View Section Not Found', ['section' => $section], 1002, __LINE__, __METHOD__, __CLASS__, __FILE__);
    }

    public function setData(array $data = [])
    {
        if (!is_array($this->data)) {
            $this->data = [];
        }

        if (is_array($data) && !empty($data)) {
            if (empty($this->data)) {
                $this->data = $data;
            } else {
                $this->data = array_merge($this->data, $data);
            }
        }
    }

    public function setBaseUrl(string $url = null)
    {
        $this->base_url = $url;
    }

    public function setViewsPath(string $path = null)
    {
        $this->views_path = $path;
    }
}
