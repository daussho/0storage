<?php

namespace App\Basic;

class WebController extends Controller
{
    protected function returnView(string $template, array $data)
    {
        $view = new View($template);
        foreach ($data as $key => $value) {
            $view->assign($key, $value);
        }
    }
}
