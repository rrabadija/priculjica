import './header.js';
import {createSection1Circle, createSection2Circle} from './circle.js';
import './keenSlider.js';
import Footer from './footer.js';

const keenSliderButton = document.querySelector('.keen-slider__button button');
const keenSliderArrow = document.querySelector('.keen-slider__button button i');

createSection1Circle();
	
createSection2Circle();

const keenSlider = new KeenSlider('.keen-slider', {
	loop: false
});

keenSliderButton.addEventListener('click', () => {
	if (keenSlider.track.details.rel === 0) {
		keenSlider.next();
	}
	else {
		keenSlider.prev();
	}
	
	keenSliderArrow.classList.toggle('active');
});
	
keenSlider.on('slideChanged', () => {
	if ((keenSlider.track.details.rel === 1)) {
		keenSliderArrow.classList.add('active');
	}
	else {
		keenSliderArrow.classList.remove('active');
	}
});

export const footer = new Footer (
	document.querySelector('section:nth-child(3)'),
	document.querySelector('.section_3_content_wrapper'),
	document.querySelector('.observed_section_3'),
	document.querySelector('footer')
);