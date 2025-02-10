import {headerLinksDots} from './header.js';
import {getURL} from './helpers.js';

export const languageSelector = document.querySelector('header aside select');

class Translate {
	constructor(languageSelector, elements, headerLinksDots) { //Constructor for the class
		this.languageSelector = languageSelector;
		this.elements = elements;
		this.headerLinksDots = headerLinksDots;

		this.setCurrentLanguage();
		this.getData();
	}

	async sendData() { //Send the data with POST to translate.php
		let keys = []; //Initialize an empty array for storing the keys from elements

		Object.keys(this.elements).forEach(key => { //Fill the array with the keys from the list of the elements
			keys.push(encodeURIComponent(key));
		});

		const response = await fetch('/php/language.php', {
			method: 'POST',	
			body: JSON.stringify({
				language: this.languageSelector.value,
				key: keys.join(',')
			})
		})

		const data = await response.json();

		return data;
	}

	async getData() { //Get JSON encoded data from translate.php
		const translations = await this.sendData();

		Object.keys(translations).forEach(key => { //Based on the key in the translations array, change the innerHTML to the linked translation of the element inside of the list of the elements to be translated
			if (this.elements[key]) { //If it exists
				this.elements[key].innerHTML = translations[key];

				if (this.elements[key].tagName === 'A') { //The header links dots get deleted on dynamic change of the innerHTML, append them to the translated header links
					this.elements[key].innerHTML = `<div class="header--links--dot"></div>${translations[key]}`;

					this.headerLinksDots();
				}

				if (this.elements[key].tagName === 'IMG') { //If the element is an image, change the alt attribute instead
					this.elements[key].alt = translations[key];
				}
			}
		});

		this.HTMLLangStyle();
	}

	HTMLLangStyle() { //Change the HTML lang attribute based on the selected language
		if (this.languageSelector.value === 'en') { //If language changed to english
			document.documentElement.lang = 'en'; //Change the HTML lang attribute to appropriate language
		}
		else {	
			document.documentElement.lang = 'hr'; //Change the HTML lang attribute to appropriate language
		}
	}

	setCurrentLanguage = () => {
		const currentLanguage = localStorage.getItem('language') || 'hr';

		localStorage.setItem('language', this.languageSelector.value);

		if (currentLanguage && currentLanguage !== 'hr') {
			fetch('/php/language.php', {
				method: 'POST',
				body: JSON.stringify({language: currentLanguage})
			});

			this.HTMLLangStyle();
		}
	}
}

export function languageSelectorChange() {
	const currentPage = getURL();

	const headerLinksMap = {
		'' : ['header.a-2', 'header.a-3', 'header.a-4', 'header.a-5'],
		'ostale-price' : ['header.a-1', 'header.a-2', 'header.a-4', 'header.a-5'],
		'o-meni' : ['header.a-1', 'header.a-2', 'header.a-3', 'header.a-5'],
		'pisi-mi' : ['header.a-1', 'header.a-2', 'header.a-3', 'header.a-4'],
		'default' :['header.a-1', 'header.a-3', 'header.a-4', 'header.a-5']
	}

	const currentHeaderLinksMap = headerLinksMap[currentPage] || headerLinksMap['default'];

	const headerLinks = currentHeaderLinksMap.reduce((acc, key, index) => {
		acc[key] = document.querySelector(`.header--links a:nth-child(${index + 1})`);
		
		return acc;
	}, {});

	const elements = { //List of elements that are to be translated with their keys (for finding the translation inside the database table)
		'index.h1' : document.querySelector('.section-1--text h1'),
		'index.p-1' : document.querySelector('.section-1--text p'),
		'index.img-alt-1' : document.querySelector('.section-1--image img'),
		'index.p-2' : document.querySelector('.keen-slider__slide:nth-child(1)'),
		'index.p-3' : document.querySelector('.keen-slider__slide:nth-child(2)'),

		...headerLinks,

		'footer.p' : document.querySelector('footer p span')
	};

	new Translate ( //Translate class object
		languageSelector,
		elements,

		headerLinksDots
	);
}

document.addEventListener('DOMContentLoaded', () => {

	languageSelector.value = localStorage.getItem('language') || 'hr';

	languageSelectorChange();

});