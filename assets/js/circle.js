import {debounce} from './helpers.js';

const section1ImageWrapper = document.querySelector('.section_1_content_image_wrapper');
const section2 = document.querySelector('section:nth-child(2)');

export function createSection1Circle() { //Creates the circles in section_1
	for (let i = 1; i <= 8; i++) {
		const section1Circle = document.createElement('div');
		section1Circle.classList = ('section_1_content_circle section_1_content_circle_' + i + '');
		section1ImageWrapper.insertBefore(section1Circle, section1ImageWrapper.firstChild);
	}
}

export function createSection2Circle() { //Render randomized circles
	const circleTmin = 2500, circleTmax = 5500;; //Animation time min && animation time max
	const circleRmin = 2.5, circleRmax = 4.0; //Circle width min && circle width max
	const circleColor = ['#5C9598', '#C4D7D1', '#F5D1C3']; //Circle color
	const section2Height = section2.offsetHeight / 2.5; //Height of section_2
	const circleKminY = section2Height - 25; //Keyframes transform translateY min
	const circleKmaxY = section2Height + 75; //Keyframes transform translateY max

	const section2Width = document.documentElement.clientWidth;
	const widthCondition = (section2Width <= 1200);

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

		resetCircle();
	}
}, 200));