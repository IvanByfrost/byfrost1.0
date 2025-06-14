<?php
class Views {
    public function Render($folder, $file, $data = null) {
        $viewPath = 'views/' . $folder . '/' . $file . '.php';

        if (file_exists($viewPath)) {
            if ($data) extract($data);
            require 'views/default/head.php';
            require $viewPath;
            require 'views/default/footer.php';
        } else {
            echo "Vista no encontrada: $viewPath";
        }
    }
}