import './header.js';
import Bubble from './bubble.js';
import './keenSlider.js';
import Footer from './footer.js';

const keenSliderButton = document.querySelector('.keen-slider button');
const keenSliderArrow = document.querySelector('.keen-slider button i');

new Bubble (
	document.querySelector('.section-1--image'),
	document.querySelector('section:nth-child(2)'),
	document.querySelector('.section-2--circle')
);

const keenSlider = new KeenSlider('.keen-slider', {
	loop: false,
	slides: {perView: 1.3, spacing: 200, origin: 'center'}
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
	document.querySelector('.section-3--observed'),
	document.querySelector('footer')
);