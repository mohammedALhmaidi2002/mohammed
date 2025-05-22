<?php

namespace App\Core;

class View {
    protected $basePath;

    public function __construct() {
        $this->basePath = BASE_PATH . '/resources/views/';
    }

    /**
     * Render a view file.
     *
     * @param string $view The view file path (e.g., 'home.index' or 'auth.login').
     * @param array $data Data to extract into the view.
     * @return void
     * @throws \Exception if the view file is not found.
     */
    public function render($view, $data = []) {
        $path = $this->resolveViewPath($view);

        if (!file_exists($path)) {
            throw new \Exception("View file not found: {$path}");
        }

        // Extract data into the current symbol table
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        include $path;

        // Get the content of the buffer and clean it
        $content = ob_get_clean();

        // Output the content
        echo $content;
    }

    /**
     * Resolve the view path from a dot notation string.
     * e.g., 'home.index' becomes 'home/index.php'
     *
     * @param string $view
     * @return string
     */
    protected function resolveViewPath($view) {
        return $this->basePath . str_replace('.', '/', $view) . '.php';
    }

    /**
     * Static method to quickly render a view.
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    public static function make($view, $data = []) {
        (new static())->render($view, $data);
    }
}

?>
