<?php

namespace App;

class View
{
    public static function make(string $viewName, array $data = [])
    {
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            extract($data);
            ob_start();
            include $viewPath;
            return ob_get_clean();
        } else {
            throw new \Exception("View file not found: $viewName");
        }
    }
}
