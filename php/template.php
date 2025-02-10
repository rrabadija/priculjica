<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'helpers.php';

    class Template {
        public static function render($file, $data = []) {
            $path = __DIR__ . '/templates/' . $file;

            if (!file_exists($path)) {
                return 'Error: Template not found.';
            }

            $template = file_get_contents($path);

            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    preg_match_all('/{' . $key . '}/', $template, $matches);

                    $placeholders = count($matches[0]);

                    for ($i = 0; $i < $placeholders; $i++) {
                        $template = preg_replace('/{' . $key . '}/', $value[$i], $template, 1);
                    }
                }
                else {
                    $template = str_replace('{' . $key . '}', $value, $template);
                }
            }

            return $template;
        }
    }

    new Template;