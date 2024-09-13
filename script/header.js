const main = document.querySelector('main');
const header = document.querySelector('header');
const headerButton = document.querySelector('header button');
const headerLinks = document.querySelectorAll('header a');
const footer = document.querySelector('footer');

function handleHeaderDots() { //Handles the bounce animation on header dots
    function addHoverClass() {
        const headerDots = this.querySelectorAll(".header_links_dot");
        headerDots.forEach(dot => dot.classList.add("hovered"));
		headerDots.forEach(dot => dot.classList.remove("unhovered"));
    }

    function removeHoverClass() {
        const headerDots = this.querySelectorAll(".header_links_dot");
        headerDots.forEach(dot => dot.classList.add("unhovered"));
		headerDots.forEach(dot => dot.classList.remove("hovered"));
    }

    headerLinks.forEach(link => {
        link.addEventListener("mouseover", addHoverClass);
        link.addEventListener("mouseout", removeHoverClass);
    });
}

function togglePointerEvents() { //Toggles pointer events for main and overlay on vertical header height expansion
	if (main.style.pointerEvents === 'none') {
		main.style.pointerEvents = '';
		footer.style.pointerEvents = '';
		overlay.style.pointerEvents = '';
	}
	else {
		main.style.pointerEvents = 'none';
		footer.style.pointerEvents = 'none';
		overlay.style.pointerEvents = 'all';
	}
}

const overlay = document.createElement('div'); //Creates the overlay that can be clicked to close the header

overlay.classList = ('overlay');
document.body.prepend(overlay);

function headerButtonClick() {
	if (document.body.style.overflowY === 'hidden') {
		document.body.style.overflowY = '';
	}
	else {
		document.body.style.overflowY = 'hidden';
	}
	
	togglePointerEvents();
	
	header.classList.toggle('expand');
	main.classList.toggle('expand');
	footer.classList.toggle('expand');
}

function handleHeaderExpand() { //Handles the vertical header height expansion on narrow screens
	let screenWidth = window.innerWidth;

	if (screenWidth > 900) {
		headerButton.removeEventListener('click', headerButtonClick);
	}
	else {
		headerButton.addEventListener('click', headerButtonClick);
	}

	function removeClasses() { //Remove classes on screen width change and on header button click
		document.body.style.overflowY = '';
		header.classList.remove('expand');
		main.classList.remove('expand');
		footer.classList.remove('expand');

		togglePointerEvents();
	}

	overlay.addEventListener('click', function() { //Clicking outside the expanded header closes the header
		if (main.classList.contains('expand')) {
			removeClasses();
		}
	});
	
	window.addEventListener('resize', function() {
		screenWidth = window.innerWidth;
		
		if (screenWidth > 900) {
			if (header.classList.contains('expand')) { //Close the header, if it's open, on screen sizes wider than 900px
				removeClasses();
			}

			headerButton.removeEventListener('click', headerButtonClick);
		}
		else {
			headerButton.addEventListener('click', headerButtonClick);
		}

		sideBar.style.transition = 'none'; //Prevent the CSS transitions for the sidebar on changing screen size

		setTimeout(function() { //Reset the CSS transitions for the sidebar
			sideBar.style.transition = '';
		}, 1);
	});
}

let sideBarButton = document.querySelector('.header_aside_button');
let sideBar = document.querySelector('header aside');
let cog = document.querySelector('.header_aside_button i:first-child');
let closeCross = document.querySelector('.header_aside_button i:last-child');

function sideBarToggle() { //Toggle the sidebar on sidebar button click, display the icon animations inside the button
	function sideBarButtonAnimations() {
		sideBar.classList.toggle('expand');
		sideBarButton.classList.toggle('active');
		cog.classList.toggle('active');
		closeCross.classList.toggle('active');

		if (cog.classList.contains('active')) { //Move the first icon upwards, disabling and resetting the transition effect, if the button has been clicked once
			setTimeout(function() {
				cog.style.transition = 'none';
				cog.style.transform = 'translateY(-40px)';
			}, 400);

			closeCross.style.transition = '';
			closeCross.style.transform = '';
		}
		else { //Move the first icon back to its starting position upon clicking the button twice
			cog.style.transition = '';
			cog.style.transform = '';

			sideBarButton.style.filter = 'brightness(1)'; //Prevent CSS visual glitches on certain browsers

			setTimeout(function() {
				sideBarButton.style.filter = '';
			}, 400);
		}

		sideBarButton.removeEventListener('click', sideBarButtonAnimations);
		sideBarButton.removeEventListener('click', resetCloseAnimation);

		setTimeout(function() {
			if (closeCross.classList.contains('active')) {
				sideBarButton.addEventListener('click', resetCloseAnimation);
			}
			else {
				sideBarButton.addEventListener('click', sideBarButtonAnimations);
			}
		}, 400);
	}

	function resetCloseAnimation() {
		closeCross.style.transform = 'translateY(40px)';

		sideBarButtonAnimations();

		setTimeout(function() {
			closeCross.style.transition = 'none';
			closeCross.style.transform = 'translateY(-40px)';

			sideBarButton.removeEventListener('click', resetCloseAnimation);
			sideBarButton.addEventListener('click', sideBarButtonAnimations);
		}, 400);
	}

	sideBarButton.addEventListener('click', sideBarButtonAnimations);
}

const languageSelector = document.querySelector('.header_aside_language_select');

function AJAXLanguage() { //AJAX for sending the selected language to index.php
	const languageValue = languageSelector.value;

	var xhrIndexLanguage = new XMLHttpRequest(); //AJAX for index.php
    xhrIndexLanguage.open('POST', '/priculjica/php/translate.php', true);
    xhrIndexLanguage.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

	var xhrFooterLanguage = new XMLHttpRequest(); //AJAX for footer.php
    xhrFooterLanguage.open('POST', '/priculjica/php/footer.php', true);
    xhrFooterLanguage.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

	class Translate {
		constructor(xhrIndexLanguage, xhrFooterLanguage, languageValue, elements, footer) { //Constructor for the class
			this.xhrIndexLanguage = xhrIndexLanguage;
			this.xhrFooterLanguage = xhrFooterLanguage;
			this.languageValue = languageValue;
			this.elements = elements;
			this.footer = footer;
			this.HTMLLangStyle();

			this.getData(); //Attach the event listener for changing the DOM
		}

		sendData() { //Send the data with POST to translate.php
			let keys = []; //Initialize an empty array for storing the keys from elements

			Object.keys(this.elements).forEach(key => { //Fill the array with the keys from the list of the elements
				keys.push(encodeURIComponent(key));
			});

			const data = 'language=' + encodeURIComponent(this.languageValue) + '&keys=' + keys.join(','); //Turn the array objects into a string because of the POST request, splitting them with a comma
	
			this.xhrIndexLanguage.send(data);
			this.xhrFooterLanguage.send('language=' + encodeURIComponent(this.languageValue));
		}

		getData() { //Get JSON encoded data from translate.php
			this.xhrIndexLanguage.onreadystatechange = () => {
				if (this.xhrIndexLanguage.readyState == 4 && this.xhrIndexLanguage.status == 200) {
					const translations = JSON.parse(this.xhrIndexLanguage.responseText);

					Object.keys(translations).forEach(key => { //Based on the key in the translations array, change the innerHTML to the linked translation of the element inside of the list of the elements to be translated
						if (this.elements[key]) { //If it exists
							this.elements[key].innerHTML = translations[key];

							if (this.elements[key].tagName === 'IMG') { //If the element is an image, change the alt attribute instead
								this.elements[key].alt = translations[key];
							}

							if (key === 'index.p-2' || key === 'index.p-3') { //For some reason the <p> tag inside gets deleted if its innerHTML is dynamically changed, when it comes to those elements, output the translation wrapped in <p> tags
								this.elements[key].innerHTML = '<p>' + translations[key] + '</p>';
							}
						}
					});

					this.HTMLLangStyle();
				}

				if (this.xhrFooterLanguage.readyState == 4 && this.xhrFooterLanguage.status == 200) {
					this.footer.innerHTML = this.xhrFooterLanguage.responseText; //Footer text
				}
			}
		}

		HTMLLangStyle() { //Change the button text (because the text is set with :after pseudoselector...)
			const existingStyle = document.getElementById('language-style');
			const style = document.createElement('style');

			style.id = 'language-style';
		
			if (this.languageValue === 'en') { //If language changed to english
				style.textContent = `.section_1_content_button button:after {
										content: 'Tell me';
										z-index: 1;
									}`;

				if (document.querySelector('.section_2_content_circle_image_text_wrapper button')) {
					style.textContent += `.section_2_content_circle_image_text_wrapper button:after {
											content: 'Tell me!';
											z-index: 0;
										}`
				}

				if (!existingStyle) { //Check if the style already exists before appending it
					document.head.appendChild(style);
				}

				document.documentElement.lang = 'en'; //Change the HTML lang attribute to appropriate language
			}
			else {
				if (existingStyle) { //Check if the style already exists before removing it
					existingStyle.remove();
				}
				
				document.documentElement.lang = 'hr'; //Change the HTML lang attribute to appropriate language
			}
		}
	}

	const elements = { //List of elements that are to be translated with their keys (for finding the translation inside the database table)
		'index.h1' : document.querySelector('.section_1_content_header h1'),
		'index.p-1' : document.querySelector('.section_1_content_paragraph p'),
		'index.img-alt-1' : document.querySelector('.section_1_content_circle_image img'),
		'index.p-2' : document.querySelector('.keen-slider__slide:nth-child(1)'),
		'index.p-3' : document.querySelector('.keen-slider__slide:nth-child(2)')
	};

	const translate = new Translate ( //Translate class object
		xhrIndexLanguage,
		xhrFooterLanguage,
		languageValue,
		elements,
		document.querySelector('footer')
	);

	translate.sendData(); //Call the Translate class method for sending data
}

function languageSelectorChange() {
	AJAXLanguage();

	languageSelector.removeEventListener('change', languageSelectorChange);
	languageSelector.disabled = true;

	setTimeout(() => {
		languageSelector.addEventListener('change', languageSelectorChange);
		languageSelector.disabled = false;
	}, 1000);
}

languageSelector.addEventListener('change', languageSelectorChange);

document.addEventListener('DOMContentLoaded', function() {
		
	handleHeaderDots();
	
	handleHeaderExpand();

	sideBarToggle();
	
});
