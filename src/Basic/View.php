<?php

namespace App\Basic;

class View
{
    private $data = array();

    private $render = false;

    public function __construct($template)
    {
        $file = __DIR__ . '/../Views/' . strtolower($template) . '.php';

        if (file_exists($file)) {
            $this->render = $file;
        } else {
            throw new \Exception('Template ' . $template . ' not found!');
        }
    }

    public function assign($variable, $value)
    {
        $this->data[$variable] = $value;
    }

    public function __destruct()
    {
        extract($this->data);
        include $this->render;

    }
}
