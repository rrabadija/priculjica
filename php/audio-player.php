<?php header('Content-Type: text/html; charset=UTF-8');
    function audioPlayer($rows) { //Generate the audio player
        if ((isset($rows['audio']) && $rows['audio'] === 1)) {
            echo '<div class="section_audio">

                    <div class="section_audio_button">

                        <div class="section_audio_button_circle">

                            <h3>

                                <span>P</span><span>O</span><span>S</span><span>L</span><span>U</span><span>Š</span><span>A</span><span>J</span>

                            </h3>

                            <button>

                                <div></div>

                                <div></div>

                            </button>

                            <h3>

                                <span>P</span><span>R</span><span>I</span><span>Č</span><span>U</span>

                            </h3>

                        </div>

                    </div>

                    <div class="section_audio_seek">

                        <span class="section_audio_timing">0:00</span>

                        <input type="range" max="100" value="0" preload="metadata">

                        <span class="section_audio_timing">0:00</span>

                    </div>

                    <audio src="' . htmlspecialchars($rows['put_do_audio']) . '" type="audio/mpeg">

            </div>';
        }
        else {
            echo '';
        }
    }