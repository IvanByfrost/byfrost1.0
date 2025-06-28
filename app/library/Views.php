<?php
class Views {
    public function Render($folder, $file, $data = null) {
        $viewPath = ROOT . '/app/views/' . $folder . '/' . $file . '.php';

        if (file_exists($viewPath)) {
            if ($data) extract($data);
            require ROOT . '/app/views/layouts/head.php';
            require $viewPath;
            require ROOT . '/app/views/layouts/footer.php';
        } else {
            echo "Vista no encontrada: $viewPath";
        }
    }
    
    /**
     * Renderiza una vista parcial sin header y footer
     * Útil para cargar contenido en dashboards o AJAX
     */
    public function RenderPartial($folder, $file, $data = null) {
        $viewPath = ROOT . '/app/views/' . $folder . '/' . $file . '.php';

        if (file_exists($viewPath)) {
            if ($data) extract($data);
            require $viewPath;
        } else {
            echo "Vista parcial no encontrada: $viewPath";
        }
    }
    
    /**
     * Renderiza solo el contenido de la vista sin ningún layout
     */
    public function RenderContent($folder, $file, $data = null) {
        $viewPath = ROOT . '/app/views/' . $folder . '/' . $file . '.php';

        if (file_exists($viewPath)) {
            if ($data) extract($data);
            ob_start();
            require $viewPath;
            $content = ob_get_clean();
            return $content;
        } else {
            return "Vista no encontrada: $viewPath";
        }
    }
}