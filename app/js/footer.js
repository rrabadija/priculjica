export default class Footer {
	constructor(section, observed, footer) {
		this.section = section;
		this.observed = observed;
		this.footer = footer;

		this.observer();

		this.touchStartY = 0;
		this.touchEndY = 0;

		this.footerScrollTrigger = false;

		this.buttons = document.querySelectorAll('button');
		this.lastButton = this.buttons[this.buttons.length - 1];
		this.footerLinks = document.querySelectorAll('footer a');

		this.lastButtonHandler = (event) => this.footerTab(event, 1);
		this.firstFooterLinkHandler = (event) => this.footerTab(event, 2);
		this.lastFooterLinkHandler = (event) => this.footerTab(event, 3);

		this.handleEventListeners();

		window.addEventListener('resize', this.handleEventListeners);
	}

	observer() {
		const observer = new IntersectionObserver((entries) => {
			const entry = entries[0];

			if (entry.isIntersecting) {
				this.section.addEventListener('wheel', this.footerScroll, {passive: true});
				this.section.addEventListener('touchstart', this.handleTouchStart, {passive: true});
				this.section.addEventListener('touchmove', this.handleTouchMove, {passive: false});
			}
			else {
				this.section.removeEventListener('wheel', this.footerScroll);
				this.section.removeEventListener('touchstart', this.handleTouchStart);
				this.section.removeEventListener('touchmove', this.handleTouchMove);
			}
		}, {threshold: 1});

		observer.observe(this.observed);
	}

	footerScroll = (event) => {
		if (event.deltaY > 0 && !this.footerScrollTrigger) {
			this.showFooter();
		}
		else if (event.deltaY < 0 && this.footerScrollTrigger) {
			this.hideFooter();
		}
	}

	handleTouchStart = (event) => {
		this.touchStartY = event.touches[0].clientY;
	}

	handleTouchMove = (event) => {
		const sensitivity = 5;
		this.touchEndY = event.touches[0].clientY;

		if (this.touchStartY > this.touchEndY + sensitivity && !this.footerScrollTrigger) {
			event.preventDefault();

			this.showFooter();
		}
		else if (this.touchStartY < this.touchEndY - sensitivity && this.footerScrollTrigger) {
			event.preventDefault();

			this.hideFooter();
		}
	}

	showFooter = () => {
		document.documentElement.style.overflowY = 'hidden';
		document.body.style.overflowY = 'hidden';

		this.section.classList.add('scroll');

		this.footer.classList.remove('unscroll');
		this.footer.classList.add('scroll');

		this.footer.addEventListener('animationend', (event) => {
			window.addEventListener('scroll', this.lockScroll, { passive: false});
			window.addEventListener('wheel', this.lockScroll, { passive: false});

			this.footerScrollTrigger = true;

			window.addEventListener('resize', this.footerResize);

			this.footer.removeEventListener('animationend', event);
		});
	}

	hideFooter = () => {
		this.section.classList.remove('scroll');

		this.footer.classList.add('unscroll');
		this.footer.classList.remove('scroll');

		document.documentElement.style.overflowY = '';

		this.footer.addEventListener('animationend', (event) => {
			window.removeEventListener('scroll', this.lockScroll);
			window.removeEventListener('wheel', this.lockScroll);

			document.body.style.overflowY = '';

			this.footer.classList.remove('unscroll');

			this.footerScrollTrigger = false;

			window.removeEventListener('resize', this.footerResize);

			this.footer.removeEventListener('animationend', event);
		});
	}

	lockScroll = (event) => {
		event.preventDefault();
	}

	checkScroll = () => {
		if (this.section.getBoundingClientRect().bottom < window.innerHeight) {
			window.scrollTo(0, window.scrollY + this.section.getBoundingClientRect().bottom - window.innerHeight);
		}
	}

	footerResize = () => {
		if (this.footerScrollTrigger) {
			this.hideFooter();
		}
	}

	footerTab = (event, i) => {
		if (event.key === 'Tab') {
			if (!event.shiftKey && i === 1) {
				event.preventDefault();

				window.scrollTo(0, document.body.scrollHeight);

				this.showFooter();

				setTimeout(() => {
					this.footerLinks[0].focus();
				}, 100);
			}
			else if (event.shiftKey && i === 2) {
				this.hideFooter();
			}
			else if (!event.shiftKey && i === 3) {
				this.hideFooter();
			}
		}
	}

	handleEventListeners = () => {
		const windowWidth = window.innerWidth;

		const buttonFocus = (event) => {
			if (event.target.matches(':focus-visible') && this.footerScrollTrigger) {
				this.hideFooter();
			}
		}

		if (windowWidth > 768) {
			this.lastButton.addEventListener('keydown', this.lastButtonHandler);
			this.footerLinks[0].addEventListener('keydown', this.firstFooterLinkHandler);
			this.footerLinks[this.footerLinks.length - 1].addEventListener('keydown', this.lastFooterLinkHandler);

			this.buttons.forEach(button => {
				button.addEventListener('focus', buttonFocus);
			});

			this.checkScroll();

			window.addEventListener('scroll', this.checkScroll);
		}
		else {
			this.lastButton.removeEventListener('keydown', this.lastButtonHandler);
			this.footerLinks[0].removeEventListener('keydown', this.firstFooterLinkHandler);
			this.footerLinks[this.footerLinks.length - 1].removeEventListener('keydown', this.lastFooterLinkHandler);

			this.buttons.forEach(button => {
				button.removeEventListener('focus', buttonFocus);
			});

			window.removeEventListener('scroll', this.checkScroll);
		}
	}
}