import './header.js';
import AudioPlayer from './audio.js';
import Image from './image.js';

if (document.querySelector('audio')) {
    new AudioPlayer (
        document.querySelector('audio'),
        document.querySelector('.section_audio button'),
        document.querySelector('.section_audio input'),
        document.querySelector('.section_audio_timing:first-of-type'),
        document.querySelector('.section_audio_timing:last-of-type')
    );
}

new Image (
    document.querySelector('main'),
    document.querySelector('header'),
    document.querySelectorAll('.paragraph_image'),
    document.querySelectorAll('.section_paragraph img')
);