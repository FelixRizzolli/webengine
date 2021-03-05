<?php


namespace app\core;


/**
 * Class View
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core
 */
class View {
    private string $title = '';
    private array $breadcrumbs = array();

    public function setTitle(string $title): void {
        $this->title = $title;
    }
    public function setBreadcrumbs(array $breadcrumbs): void{
        $this->breadcrumbs = $breadcrumbs;
    }
    public function addBreadcrumb(string $url, string $title): void {
        $this->breadcrumbs[] = [
            "title" => $title,
            "url" => $url
        ];
    }

    public function getTitle(): string {
        return $this->title;
    }


    public function renderView($view, $params = array()): string {
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->renderLayout();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
    private function renderContent($viewContent): string {
        $layoutContent = $this->renderLayout();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
    private function renderLayout(): string {
        $layout = Application::$app->getLayout();
        if (Application::$app->getController()) {
            $layout = Application::$app->getController()->getLayout();
        }
        ob_start();
        include_once(Application::$ROOT_DIR . "/views/layouts/{$layout}.view.php");
        return ob_get_clean();
    }
    private function renderOnlyView($view, $params = array()): string {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once(Application::$ROOT_DIR . "/views/{$view}.view.php");
        return ob_get_clean();
    }
}