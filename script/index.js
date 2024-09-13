const section1ImageWrapper = document.querySelector('.section_1_content_image_wrapper');
const section2 = document.querySelector('section:nth-child(2)');

function createSection1Circle() { //Creates the circles in section_1
	for (let i = 1; i <= 8; i++) {
		const section1Circle = document.createElement('div');
		section1Circle.classList = ('section_1_content_circle section_1_content_circle_' + i + '');
		section1ImageWrapper.insertBefore(section1Circle, section1ImageWrapper.firstChild);
	}
}

function createSection2Circle() { //Render randomized circles
	const circleTmin = 2500, circleTmax = 5500;; //Animation time min && animation time max
	let circleRmin = 2.5, circleRmax = 4.0; //Circle width min && circle width max
	const circleColor = ['#5C9598', '#C4D7D1', '#F5D1C3']; //Circle color
	const section2Height = section2.offsetHeight / 2.5; //Height of section_2
	const circleKminY = section2Height - 25; //Keyframes transform translateY min
	const circleKmaxY = section2Height + 75; //Keyframes transform translateY max

	const section2Width = document.documentElement.clientWidth;
	let widthCondition = (section2Width <= 1200);

	function setKeyframes(i) { //Changes the keyframes on screen width <= 1200
		return widthCondition ? `section_2_content_circle_animation_media_${i}` : `section_2_content_circle_animation_${i}`;
	}

	function createCircle(i) { //Create the circle
		let circleR = Math.floor(((Math.random() * (circleRmax - circleRmin + 1)) + circleRmin) * 10) / 10;
		const circleT = Math.floor(Math.random() * (circleTmax - circleTmin + 1)) + circleTmin;
		const circleC = circleColor[Math.floor(Math.random() * circleColor.length)];

		function calculateCircleKY() {
			let circleKY = Math.floor(Math.random() * (circleKmaxY - circleKminY + 1)) + circleKminY;
			
			if (i % 2 === 0) { //Each +2 index of translateY has a negative value
				circleKY = -circleKY;
			}
			
			return circleKY;
		}

		const section2Circle = document.createElement('div');
		section2Circle.classList = `section_2_content_circle section_2_content_circle_${i}`;
		section2Circle.style.animation = `${setKeyframes(i)} ${circleT}ms linear infinite`;

		section2Circle.style.setProperty('--circleWidth', `${circleR}%`); //Sends the variables into CSS variables
        section2Circle.style.setProperty('--circleColor', circleC);
		section2Circle.style.setProperty('--circleTransformY', `${calculateCircleKY()}%`);

		if (i > 6 && i <= 12) { //Circles coming from the left content circle
			section2Circle.classList = `section_2_content_circle section_2_content_circle_${i - 6}`;
			section2Circle.style.animation = `${setKeyframes(i - 6)} ${circleT}ms linear infinite`;
			section2Circle.classList.add('left');
		}
		else if (i > 12) { //Circles coming from the right content circle
			section2Circle.classList = `section_2_content_circle section_2_content_circle_${i - 12}`;
			section2Circle.style.animation = `${setKeyframes(i - 12)} ${circleT}ms linear infinite`;
			section2Circle.classList.add('right');
		}

		section2Circle.addEventListener('animationiteration', function() { //Re-render the circles once the animation of the circle has finished
			section2.removeChild(section2Circle);
			createCircle(i);
		});

		section2.insertBefore(section2Circle, section2.firstChild);
	}

	for (let i = 1; i <= 18; i++) { //Initial circle rendering function call
		createCircle(i);
	}
}

function debounce(func, delay) { //Debounces the event listener so it doesn't cause lag on many window resize events
	let timeout;
	
	return function () {
		const context = this;
		const args = arguments;
		
		clearTimeout(timeout); 
		
		timeout = setTimeout(() => {
			func.apply(context, args);
		}, delay);
	};
}

function resetCircle() { //Resets the circles on width change
	document.querySelectorAll('.section_2_content_circle').forEach(circle => {
		circle.remove();
	});
	
	createSection2Circle();
}

let lastSection2Width = window.innerWidth;

window.addEventListener('resize', debounce(() => { //Event listener for width changes
	let currentSection2Width = window.innerWidth;

	if (currentSection2Width !== lastSection2Width) {
		lastSection2Width = currentSection2Width;

		let section2Width = document.documentElement.clientWidth;

		if (section2Width <= 1200 || section2Width <= 992 || section2Width <= 576 || section2Width <= 480) {
			resetCircle();
		}
		else {
			resetCircle();
		}
	}
}, 300));

const keenSliderButton = document.querySelector('.keen-slider__button button');
const keenSliderArrow = document.querySelector('.keen-slider__button button i');

function keenSlider() { //Creates the slider using keen-slider
	const slider = new KeenSlider('.keen-slider', {
		loop: false
	});

	keenSliderButton.addEventListener('click', () => { //Button event listener for moving the slides
		if (slider.track.details.rel === 0) { //If the first slide is visible, move to the second and vice versa
			slider.next();
		}
		else {
			slider.prev();
		}

		keenSliderArrow.classList.toggle('active');
	});

	slider.on('slideChanged', () => { //Rotate the button depending on the slide
		if ((slider.track.details.rel === 1)) {
			keenSliderArrow.classList.add('active');
		}
		else {
			keenSliderArrow.classList.remove('active');
		}
	});
}

var observedSection3 = document.querySelector('.observed_section_3');
const section3 = document.querySelector('section:nth-child(3)');

function handleFooter() { //Handles the sliding animation to footer on mouse scroll
	const footer = document.querySelector('footer');
	var footerActionTrigger = false;

	var observer = new IntersectionObserver(function(entries) { //section_3 observer
		var entry = entries[0];

		if (entry.isIntersecting) {
			section3.addEventListener('wheel', footerScroll);
			section3.addEventListener('touchstart', handleTouchStart, {passive: true});
            section3.addEventListener('touchmove', handleTouchMove, {passive: false});
		}
		else {
			section3.removeEventListener('wheel', footerScroll);
			section3.removeEventListener('touchstart', handleTouchStart);
            section3.removeEventListener('touchmove', handleTouchMove);
		}

	}, {threshold: 1});

	observer.observe(observedSection3);
	
	function handleTouchStart(event) {
		touchStartY = event.touches[0].clientY;
    }
	
	function handleTouchMove(event) {
        touchEndY = event.touches[0].clientY;

		const sensitivity = 5;

        if (touchStartY > touchEndY + sensitivity && !footerActionTrigger) {
			event.preventDefault();
            showFooter();
        } 
		else if (touchStartY < touchEndY - sensitivity && footerActionTrigger) {
			event.preventDefault();
            hideFooter();
        }
    }

	function footerScroll(event) { //Handles footer sliding animation
		if (event.deltaY > 0 && !footerActionTrigger) {
			showFooter();
		}
		else if (event.deltaY < 0 && footerActionTrigger) {
			hideFooter();
		}
	}
	
	function showFooter() { //Handles footer sliding up
		document.documentElement.style.overflowY = 'hidden';
		document.body.style.overflowY = 'hidden';
		section3.classList.add('scrolled_footer');
		footer.classList.remove('unscrolled');
		footer.classList.add('scrolled');
		window.addEventListener("resize", checkWidth);

		footer.addEventListener('animationend', function handleAnimationEnd() {
			footerActionTrigger = true;
			footer.removeEventListener('animationend', handleAnimationEnd);
		});
	}
	
	function hideFooter() { //Handles footer sliding down
		footer.classList.remove('scrolled');
		footer.classList.add('unscrolled');
		section3.classList.remove('scrolled_footer');

		document.documentElement.style.overflowY = '';
		
		footer.addEventListener('animationend', function handleAnimationEnd() {
			document.body.style.overflowY = '';
			footerActionTrigger = false;
			footer.classList.remove('unscrolled');
			footer.removeEventListener('animationend', handleAnimationEnd);
			window.removeEventListener('resize,', checkWidth);
		});

		footerLinks[0].blur();
		footerLinks[footerLinks.length - 1].blur();
	}

	const buttons = document.querySelectorAll('button');
	const lastButton = buttons[buttons.length - 1];
	const footerLinks = document.querySelectorAll('footer a');

	function footerTab(event, i) {
		if (event.key === 'Tab') {
			if (!event.shiftKey && i === 1) { //Scroll to the footer and focus the first link
				event.preventDefault();
				window.scrollTo(0, document.body.scrollHeight);
				showFooter();
	
				setTimeout(function() { //Prevent from focus scrolling the div to the center of the viewport
					footerLinks[0].focus();
				}, 100);
			}
			else if (event.shiftKey && i === 2) { //Shift tab from the first footer link hides the footer
				hideFooter();
			}
			else if (!event.shiftKey && i === 3) { //Shift tab from the second footer link doesn't hide the footer, tab does
				hideFooter();
			}
		}
	}

	const lastButtonHandler = (event) => footerTab(event, 1);
	const footerLink1Handler = (event) => footerTab(event, 2);
	const footerLink2Handler = (event) => footerTab(event, 3);

	function footerWindowWidth() {
		const windowWidth = window.innerWidth;

		if (windowWidth > 768) {
			lastButton.addEventListener('keydown', lastButtonHandler);
			footerLinks[0].addEventListener('keydown', footerLink1Handler);
			footerLinks[footerLinks.length - 1].addEventListener('keydown', footerLink2Handler);
		} 
		else {
			lastButton.removeEventListener('keydown', lastButtonHandler);
			footerLinks[0].removeEventListener('keydown', footerLink1Handler);
			footerLinks[footerLinks.length - 1].removeEventListener('keydown', footerLink2Handler);
		}
	}

	footerWindowWidth();

	window.addEventListener('resize', footerWindowWidth);

	buttons.forEach(button => { //If the footer is on and a button outside of it is tab indexed, hide the footer
		button.addEventListener('focus', () => {
			if (footerActionTrigger) {
				hideFooter();
			}
		});
	});
	
	function checkWidth() { //Resets section_3 and footer when the screen width is less than 992px wide
		const windowWidth = window.innerWidth;

		if (windowWidth <= 992) {
			footerActionTrigger = false;

			setTimeout(function() {
				document.documentElement.style.overflowY = '';
				document.body.style.overflowY = '';
			}, 100);

			footerClasses();
		}
		else if (windowWidth <= 768) {
			setTimeout(function() {
				document.documentElement.style.overflowY = '';
				document.body.style.overflowY = '';
			}, 100);
		}
		else {
			document.documentElement.style.overflowY = '';
			document.body.style.overflowY = '';
			footerActionTrigger = false;
			
			footerClasses();
		}
	}
	
	function footerClasses() {
		section3.style.transition = 'none';

		setTimeout(function () {
			section3.style.transition = '';
		}, 10);
			
		section3.classList.remove('scrolled_footer');
		footer.classList.remove('scrolled');
		footer.classList.remove('unscrolled');
	}
}

document.addEventListener('DOMContentLoaded', function() {
		
	createSection1Circle();
	
	createSection2Circle();

	keenSlider();

	handleFooter();
	
});