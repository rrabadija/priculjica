import SideBar from './sideBar.js';
import {languageSelector, languageSelectorChange} from './translate.js';
import './button.js';
import {throttle} from './helpers.js';

export default class Header {
	constructor(main, header, headerButton, headerLinks, sideBar, sideBarButton, sideBarButtonAnimations, resetSideBarButtonAnimations, footer) {
		this.main = main;
		this.header = header;
		this.headerButton = headerButton;
		this.headerLinks = headerLinks;
		this.sideBar = sideBar;
		this.sideBarButton = sideBarButton;
		this.sideBarButtonAnimations = sideBarButtonAnimations;
		this.resetSideBarButtonAnimations = resetSideBarButtonAnimations;
		this.footer = footer;

		this.screenWidth = 0;
		this.sideBarResizeTrigger = true;
		this.throttleHeader = throttle(this.toggleHeader, 500);

		this.headerPreventTabIndex = (event) => this.preventTabIndex(event, true);
		this.sideBarButtonPreventTabIndex = (event) => this.preventTabIndex(event, false);
		this.sideBarPreventTabIndex = (event) => this.preventTabIndex(event, true);
    	this.sideBarlastChildPreventTabIndex = (event) => this.preventTabIndex(event, false);

		this.overlay = document.createElement('div');
		this.overlay.classList = ('overlay');

		document.body.prepend(this.overlay);

		this.headerResize();
		this.headerLinksDots();

		this.sideBarButton.addEventListener('click', throttle(() => {
			this.sideBar.classList.toggle('expand');

			this.sideBarButtonAnimations();
			this.sideBarResize();
			this.sideBarTabIndex();
		}, 400));

		window.addEventListener('resize', () => {
			this.headerResize();
			this.disableTransition(this.sideBar);
			this.sideBarResize();
			this.sideBarTabIndex();
		});
	}

	toggleHeader = () => {
		document.body.classList.toggle('expand');

		this.header.classList.toggle('expand');
		this.overlay.classList.toggle('expand');
		this.main.classList.toggle('expand');
		this.footer.classList.toggle('expand');

		this.overlay.addEventListener('click', this.closeHeader);

		if (this.header.classList.contains('expand')) {
			this.headerTabIndex('', -1, '');

			this.headerButton.addEventListener('keydown', this.headerPreventTabIndex);
			this.sideBarButton.addEventListener('keydown', this.sideBarButtonPreventTabIndex);
		}
		else {
			this.headerTabIndex(-1, -1, -1);

			this.headerButton.removeEventListener('keydown', this.headerPreventTabIndex);
			this.sideBarButton.removeEventListener('keydown', this.sideBarButtonPreventTabIndex);
		}
	}

	closeHeader = () => {
		this.toggleHeader();

		this.overlay.removeEventListener('click', this.closeHeader);
	}

	headerResize = () => {
		this.screenWidth = window.innerWidth;

		if (this.screenWidth <= 900) {
			this.headerButton.addEventListener('click', this.throttleHeader);

			this.headerTabIndex(-1, -1, -1);

			if (this.sideBar.classList.contains('expand') && !this.sideBarResizeTrigger) {
				this.sideBar.classList.remove('expand');

				this.sideBarResizeTrigger = true;

				this.resetSideBarButtonAnimations();
			}
		}
		else {
			this.headerButton.removeEventListener('click', this.throttleHeader);

			this.headerTabIndex('', -1, '');

			if (this.header.classList.contains('expand')) {
				this.toggleHeader();
				this.disableTransition(this.overlay);
			}

			if (this.sideBar.classList.contains('expand')) {
				this.sideBarResizeTrigger = false;
			}
		}
	}

	headerTabIndex = (value1, value2, value3) => {
		const sideBarElements = Array.from(this.sideBar.firstElementChild.children);

		this.headerLinks.forEach(link => {
			link.tabIndex = `${value1}`
		});

		sideBarElements.forEach(element => {
			element.tabIndex = `${value2}`;
		});

		if (value3 === -1) {
			this.sideBarButton.tabIndex = '-1';
		}
		else {
			this.sideBarButton.tabIndex = '';
		}
	}

	sideBarResize = () => {
		this.screenWidth = window.innerWidth;

		if (this.screenWidth > 900 && this.sideBar.classList.contains('expand')) {
			this.sideBarResizeTrigger = false;
		}
	}

	sideBarTabIndex = () => {
		const sideBarLastChild = this.sideBar.firstElementChild.children[this.sideBar.firstElementChild.children.length - 1];

		this.screenWidth = window.innerWidth;

		if (this.sideBar.classList.contains('expand')) {
			this.headerTabIndex(-1, '', '');

			if (this.header.classList.contains('expand')) {
				this.sideBarButton.removeEventListener('keydown', this.sideBarButtonPreventTabIndex);
			}

			this.sideBarButton.addEventListener('keydown', this.sideBarPreventTabIndex);
			sideBarLastChild.addEventListener('keydown', this.sideBarlastChildPreventTabIndex);
		}
		else {
			this.sideBarButton.removeEventListener('keydown', this.sideBarPreventTabIndex);
			sideBarLastChild.removeEventListener('keydown', this.sideBarlastChildPreventTabIndex);

			if (this.header.classList.contains('expand') || this.screenWidth > 900) {
				this.headerTabIndex('', -1, '');
			}

			if (this.header.classList.contains('expand')) {
				this.sideBarButton.addEventListener('keydown', this.sideBarButtonPreventTabIndex);
			}
		}
	}

	preventTabIndex = (event, shiftKey) => {
		if (event.key === 'Tab' && event.shiftKey && shiftKey || event.key === 'Tab' && !event.shiftKey && !shiftKey) {
			event.preventDefault();
		}
	}

	disableTransition = (element) => {
		element.style.transition = 'none';

		setTimeout(() => {
			element.style.transition = '';
		}, 1);
	}

	headerLinksDots = () => {
		this.headerLinks.forEach(link => {
			const headerDots = link.querySelectorAll('.header--links--dot');

			link.addEventListener('mouseover', () => {
				headerDots.forEach(dot => dot.classList.add("hover"));
				headerDots.forEach(dot => dot.classList.remove("unhover"));
			});

			link.addEventListener('mouseout', () => {
				headerDots.forEach(dot => dot.classList.add("unhover"));
				headerDots.forEach(dot => dot.classList.remove("hover"));
			});
		});
	}
}

const sideBar = new SideBar (
	document.querySelector('header aside'),
	document.querySelector('.header--button-aside-toggle'),
	document.querySelector('.header--button-aside-toggle i:first-child'),
	document.querySelector('.header--button-aside-toggle i:last-child'),
);

export const header = new Header (
	document.querySelector('main'),
	document.querySelector('header'),
	document.querySelector('.header--logo button'),
	document.querySelectorAll('.header--links a'),

	sideBar.sideBar,
	sideBar.sideBarButton,
	sideBar.sideBarButtonAnimations,
	sideBar.resetSideBarButtonAnimations,

	document.querySelector('footer')
);

export const headerLinksDots = () => header.headerLinksDots();

languageSelector.addEventListener('change', languageSelectorChange);