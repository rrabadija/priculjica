import {headerLinksDots} from './header.js';
import {getURL} from './helpers.js';

export const languageSelector = document.querySelector('.header_aside_language_select');

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
					this.elements[key].innerHTML = `<div class="header_links_dot"></div>${translations[key]}`;

					this.headerLinksDots();
				}

				if (this.elements[key].tagName === 'IMG') { //If the element is an image, change the alt attribute instead
					this.elements[key].alt = translations[key];
				}

				if (key === 'index.p-2' || key === 'index.p-3') { //The <p> tag inside the keen-slider slide gets deleted if its innerHTML is dynamically changed, output the translations wrapped in <p> tags
					this.elements[key].innerHTML = `<p>${translations[key]}</p>`;
				}
			}
		});

		this.HTMLLangStyle();
	}

	HTMLLangStyle() { //Change the button text (because the text is set with :after pseudoselector...)
		const languageStyle = document.getElementById('language-style');
		const style = document.createElement('style');

		style.id = 'language-style';
		
		if (this.languageSelector.value === 'en') { //If language changed to english
			style.textContent = `.section_1_content_button button:after,
								.section_2_content_circle_image_text_wrapper button:after {
									content: 'Tell me';
									z-index: 1;
								}`;

			if (!languageStyle) { //Check if the style already exists before appending it
				document.head.appendChild(style);
			}

			document.documentElement.lang = 'en'; //Change the HTML lang attribute to appropriate language
		}
		else {
			if (languageStyle) { //Check if the style already exists before removing it
				languageStyle.remove();
			}
				
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
	let headerLinks = {};
	const currentPage = getURL();

	switch (currentPage) {
		case '':
			headerLinks = {
				'header.a-2' : document.querySelector('header a:nth-child(1)'),
				'header.a-3' : document.querySelector('header a:nth-child(2)'),
				'header.a-4' : document.querySelector('header a:nth-child(3)'),
				'header.a-5' : document.querySelector('header a:nth-child(4)'),
			}

			break;

		case 'ostale-price':
			headerLinks = {
				'header.a-1' : document.querySelector('header a:nth-child(1)'),
				'header.a-2' : document.querySelector('header a:nth-child(2)'),
				'header.a-4' : document.querySelector('header a:nth-child(3)'),
				'header.a-5' : document.querySelector('header a:nth-child(4)'),
			}

			break;

		case 'o-meni':
			headerLinks = {
				'header.a-1': document.querySelector('header a:nth-child(1)'),
				'header.a-2': document.querySelector('header a:nth-child(2)'),
				'header.a-3': document.querySelector('header a:nth-child(3)'),
				'header.a-5': document.querySelector('header a:nth-child(4)'),
			};

			break;

		case 'pisi-mi':
			headerLinks = {
				'header.a-1': document.querySelector('header a:nth-child(1)'),
				'header.a-2': document.querySelector('header a:nth-child(2)'),
				'header.a-3': document.querySelector('header a:nth-child(3)'),
				'header.a-4': document.querySelector('header a:nth-child(4)'),
			};

			break;

		default:
			headerLinks = {
				'header.a-1': document.querySelector('header a:nth-child(1)'),
				'header.a-3': document.querySelector('header a:nth-child(2)'),
				'header.a-4': document.querySelector('header a:nth-child(3)'),
				'header.a-5': document.querySelector('header a:nth-child(4)'),
			};
			
			break;
	}

	const elements = { //List of elements that are to be translated with their keys (for finding the translation inside the database table)
		'index.h1' : document.querySelector('.section_1_content_header h1'),
		'index.p-1' : document.querySelector('.section_1_content_paragraph p'),
		'index.img-alt-1' : document.querySelector('.section_1_content_circle_image img'),
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