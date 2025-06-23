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
}