<?php

/**
 * Base Controller
 * Loads the models and views, supports multiple layouts.
 */
class Controller {
    public function model($model) {
        require_once APPROOT . '/app/Models/' . $model . '.php';
        return new $model();
    }

    /**
     * Renders a view inside a layout.
     * @param string $view       Path to view file (e.g., 'dashboard/index')
     * @param array  $data       Data to extract into the view
     * @param string $layout     Layout file name (e.g., 'main' or 'app')
     */
    public function view($view, $data = [], $layout = 'main') {
        extract($data);
        $contentView = APPROOT . '/views/' . $view . '.php';
        if (!file_exists($contentView)) {
            die('View not found: ' . $view);
        }
        $layoutFile = APPROOT . '/views/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            die('Layout not found: ' . $layout);
        }
        require_once $layoutFile;
    }
}
