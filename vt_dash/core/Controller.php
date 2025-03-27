<?php
namespace core;

use \src\Config;

class Controller {

    protected function redirect($url) {
        header("Location: ".$this->getBaseUrl().$url);
        exit;
    }

    private function getBaseUrl() {
        $base = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
        $base .= $_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT'] != '80') {
            $base .= ':'.$_SERVER['SERVER_PORT'];
        }
        $base .= Config::BASE_DIR;
        
        return $base;
    }

    private function _render($folder, $viewName, $viewData = []) {
        // Certifica-se de que $viewData é um array antes de extraí-lo
        if (!is_array($viewData)) {
            throw new \InvalidArgumentException("Expected array for view data, " . gettype($viewData) . " given.");
        }

        if (file_exists('../src/views/'.$folder.'/'.$viewName.'.php')) {
            extract($viewData); // Extrai as variáveis do array para o escopo local
            $render = fn($vN, $vD = []) => $this->renderPartial($vN, $vD);
            $base = $this->getBaseUrl();
            require '../src/views/'.$folder.'/'.$viewName.'.php';
        } else {
            throw new \Exception("View file not found: ../src/views/$folder/$viewName.php");
        }
    }

    private function renderPartial($viewName, $viewData = []) {
        $this->_render('partials', $viewName, $viewData);
    }

    public function render($viewName, $viewData = []) {
        $this->_render('pages', $viewName, $viewData);
    }

    /**
     * Renderiza uma página dentro de um layout, como header e footer.
     * 
     * @param string $layout Nome do layout (pasta em `partials`)
     * @param string $viewName Nome da página a ser renderizada
     * @param array $viewData Dados a serem passados para a página
     */
    public function renderLayout($layout, $viewName, $viewData = [], $pageData = []) {
        if (!is_array($viewData)) {
            throw new \InvalidArgumentException("Expected array for view data, " . gettype($viewData) . " given.");
        }
    
        // Cria o objeto $page a partir dos dados fornecidos
        $page = (object) $pageData;
    
        // Renderiza o cabeçalho
        $this->renderPartial("$layout/header", array_merge($viewData, ['page' => $page]));
    
        // Renderiza a página principal
        $this->_render('pages', "$layout/$viewName", array_merge($viewData, ['page' => $page]));
    
        // Renderiza o rodapé
        $this->renderPartial("$layout/footer", array_merge($viewData, ['page' => $page]));
    }
    
    
    
}
