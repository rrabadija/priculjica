<?php header('Content-Type: application/json; charset=UTF-8');
    require_once 'helpers.php';

    class Toolbar {
        private $toolbar = '';
        private $toolbarControl = '';

        public function __construct() {
            global $FETCH;

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($FETCH['toolbarControl'])) {
                    echo json_encode($this -> generateToolbarControls(sanitize($FETCH['toolbarControl'] ?? '')));
                }
            }
        }

        public function generateToolbarStory() {
            $this -> toolbar = '<div class="toolbar_height"></div>

                        <button class="toolbar_toggle_button"><i class="fa fa-plus"></i></button>
        
                        <aside class="toolbar">

                            <div class="toolbar_buttons_container">

                                <button class="toolbar_save"><i class="fa fa-save"></i></button>

                                <button class="toolbar_edit"><i class="fa fa-pencil"></i></button>

                                <button class="toolbar_delete"><i class="fa fa-trash"></i></button>

                                <button class="toolbar_image"><i class="fa fa-camera"></i></button>

                                <button class="toolbar_audio"><i class="fa fa-volume-up"></i></button>

                                <button class="toolbar_clear"><i class="fa fa-close"></i></button>

                                <button class="toolbar_italic"><i class="fa fa-italic"></i></button>

                                <button class="toolbar_bold"><i class="fa fa-bold"></i></button>

                                <button class="toolbar_align_right"><i class="fa fa-align-right"></i></button>

                                <button class="toolbar_align_center"><i class="fa fa-align-center"></i></button>

                                <button class="toolbar_align_left"><i class="fa fa-align-left"></i></button>

                                <button class="toolbar_justify"><i class="fa fa-align-justify"></i></button>

                                <input type="number" min="1" max="100" value="100">

                            </div>

	                    </aside>
                        
                        <aside class="toolbar_controls_container"></aside>';

            return $this -> toolbar;
        }

        public function generateToolbarSearch() {
            $this -> toolbar = '<div class="toolbar_height"></div>

                        <button class="toolbar_toggle_button"></button>
        
                        <aside class="toolbar">

                            <div class="toolbar_buttons_container">
                        
                                <button class="toolbar_pdf"><i class="fa fa-file-pdf-o"></i></button>

                                <button class="toolbar_stats"><i class="fa fa-pie-chart"></i></button>

                                <button class="toolbar_stats"><i class="fa fa-sort-alpha-asc"></i></button>

                            </div>
                        
                        </aside>';

            return $this -> toolbar;
        }

        private function generateToolbarControls($control) {
            $toolbarControls = '<div class="toolbar_controls ' . str_replace('_', '_controls_', $control) . '">

                                    ' . $this -> toolbarControl($control) . '

                                </div>';

            return $toolbarControls;
        }

        private function toolbarControl($control) {
            switch ($control) {
                case 'toolbar_image':
                    $this -> toolbarControl = '<input type="file" id="file_image" name="file_image">

			                                    <label for="file_image" class="toolbar_label"><i class="fa fa-plus"></i></label>

			                                    <input type="text" placeholder="alt tekst...">

			                                    <input type="text" placeholder="opis...">

			                                    <button></button>';

                    break;

                case 'toolbar_audio':
                    $this -> toolbarControl = '<input type="file" id="file_audio" name="file_audio">

			                                    <label for="file_audio" class="toolbar_label"><i class="fa fa-volume-up"></i></label>

			                                    <p></p>

			                                    <button></button>';

                    break;
            }

            return $this -> toolbarControl;
        }
    }

    $toolbar = new Toolbar;