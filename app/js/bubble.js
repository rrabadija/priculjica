import { debounce } from './helpers.js';

export default class Bubble {
	constructor(section1, section2, section2Circle) {
		this.section1 = section1;
		this.section2 = section2;
		this.section2Circle = section2Circle;

		this.section2Width = null;
		this.section2Height = null;
		this.section2CircleLeft = null;
		this.section2CircleDiameter = null;

		this.bubbleDiameterMin = 4.0;
		this.bubbleDiameterMax = 8.0;
		this.bubbleColorPalette = ['#5C9598', '#C4D7D1', '#F5D1C3'];

		this.bubbleTranslateXMin = -400;
		this.bubbleTranslateXMax = Math.abs(this.bubbleTranslateXMin);

		this.bubbleTranslateYMin = null;
		this.bubbleTranslateYMax = null;

		this.bubbleTimingMin = 2500;
		this.bubbleTimingMax = 5000;

		this.bubbleData = [];
			
		this.appendBubble();
		this.setDimensions();
		this.setBubble();
		this.renderBubble();
		this.animateBubble();

		window.addEventListener('resize', () => {
			this.resetBubble();
		});
	}

	createBubble = (classList) => {
		const bubble = document.createElement('div');

		bubble.className = classList;

		return bubble;
	}

	appendBubble = () => {
		for (let i = 1; i <= 18; i++) {
			if (i <= 8) {
				this.section1.appendChild(this.createBubble(`section-1--image--bubble section-1--image--bubble-${i}`));
			}

			this.section2.appendChild(this.createBubble(`section-2--bubble`));
		}

		this.bubbles = [...document.querySelectorAll('.section-2--bubble')];
	}

	dimensionsFormulae = (dimensions) => {
		if (Array.isArray(dimensions) && dimensions.length > 2) {
			return dimensions[Math.floor(Math.random() * dimensions.length)];
		}

		const [min, max] = dimensions;

		return Math.floor(Math.random() * (max - min + 1) + min);
	}

	setDimensions = () => {
		this.section2Width = this.section2.getBoundingClientRect().width;
		this.section2Height = this.section2.getBoundingClientRect().height;

		this.section2CircleLeft = this.section2Circle.getBoundingClientRect().left;
		this.section2CircleDiameter = this.section2Circle.getBoundingClientRect().width;
	}

	initBubble = () => {
		const diameter = this.dimensionsFormulae([this.bubbleDiameterMin, this.bubbleDiameterMax]);
		const color = this.dimensionsFormulae(this.bubbleColorPalette);
		const translateX = this.dimensionsFormulae([this.bubbleTranslateXMin, this.bubbleTranslateXMax]);

		const bubbleTranslateYMin = (this.section2CircleDiameter / 2) + diameter;
		const bubbleTranslateYMax = (this.section2Height - this.section2CircleDiameter);

		const directionY = Math.random() < 0.5 ? 1 : -1;
		const translateY = this.dimensionsFormulae([bubbleTranslateYMin, bubbleTranslateYMax]) * directionY;

		const timing = this.dimensionsFormulae([this.bubbleTimingMin, this.bubbleTimingMax]);

		return {
			diameter,
			color,
			translateX,
			translateY,
			timing
		};
	}

	setBubble = () => {
		this.bubbles.forEach((_, index) => {
			this.bubbleData[index] = this.initBubble();
		});
	}

	renderBubble = () => {
		this.bubbles.forEach((bubble, index) => {
			const {diameter, color} = this.bubbleData[index];

			bubble.style.setProperty('--section-2--bubble-diameter', `${diameter}%`);
			bubble.style.setProperty('--section-2--bubble-color', color);

			if (index >= 6 && index < 12) {
				bubble.style.left = `${(this.section2CircleLeft + (this.section2CircleDiameter / 2)) - ((diameter / 100) * this.section2Width / 2)}px`;
			} 
			else if (index >= 12) {
				bubble.style.right = `${(this.section2CircleLeft + (this.section2CircleDiameter / 2)) - ((diameter / 100) * this.section2Width / 2)}px`;
			}
		});
	}

	animateBubble = () => {
		this.startTimes = this.bubbles.map(() => performance.now());

		const animateAllBubbles = (currentTime) => {
			this.bubbles.forEach((bubble, index) => {
				const { translateX, translateY, timing } = this.bubbleData[index];
				const elapsed = currentTime - this.startTimes[index];
				const progress = Math.min(elapsed / timing, 1);

				bubble.style.transform = `translate(${translateX * progress}px, ${translateY * progress}px)`;
				bubble.style.opacity = (1 - progress).toFixed(2);

				if (progress === 1) {
					this.updateBubble(bubble, index);

					this.startTimes[index] = currentTime;
				}
			});

			requestAnimationFrame(animateAllBubbles);
		};

		requestAnimationFrame(animateAllBubbles);
	}

	updateBubble = (bubble, index) => {
		this.bubbleData[index] = this.initBubble();

		const {diameter, color} = this.bubbleData[index];

		bubble.style.transform = 'translate(0, 0)';
		bubble.style.opacity = '1';
		bubble.style.setProperty('--section-2--bubble-diameter', `${diameter}%`);
		bubble.style.setProperty('--section-2--bubble-color', color);
	}

	resetBubble = debounce(() => {
		this.setDimensions();
		this.setBubble();
		this.renderBubble();
	}, 200);
}
