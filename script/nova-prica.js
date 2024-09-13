const paragraph = document.querySelectorAll('.paragraph_image');
const paragraphImage = document.querySelectorAll('.section_paragraph img');
const expandedImageWrapper = document.createElement('div'); //Create the elements
const expandedImage = document.createElement('img');
const expandedText = document.createElement('h2');
const closeButton = document.createElement('button');
expandedImageWrapper.classList = ('expanded_image_wrapper');

let isTyping = false;

function handleParagraphImage() { //Event listeners for expanding the image on image click
	closeButton.addEventListener('click', function() { //Close button event listener
		closeParagraphImage();
	});

	paragraphImage.forEach(image => { //Open the image on click
		image.addEventListener('click', function() {
			expandImage(image);
		});
	});

	paragraph.forEach(p => { //Attach the enter event listener
		p.addEventListener('keydown', keyEnter);
	});
}

function expandImage(image) { //Append elements for the expanded image and text, expand the image
	main.insertBefore(expandedImageWrapper, main.firstChild);

	expandedImage.src = image.src;

	expandedImageWrapper.appendChild(expandedImage);
	expandedImageWrapper.appendChild(expandedText);
	expandedImageWrapper.appendChild(closeButton);
	document.body.style.overflowY = 'hidden';
	header.style.pointerEvents = 'none';
	main.style.pointerEvents = 'none';
	isTyping = false;
	
	setTimeout(function() {
		expandedImageWrapper.classList.add('expanded');
	}, 1);
	
	setTimeout(function() {
		expandedImage.classList.add('expanded');
	}, 25);
	
	let i = 4; //Typewriter settings
	let txt = image.dataset.opis;
	let txtSpeed = 30;
	
	function typeWriter() { //Types the text letter by letter
		if (i < txt.length && !isTyping) {
			expandedText.innerHTML += txt.charAt(i);
			i++;
			setTimeout(typeWriter, txtSpeed);
		}
	}
	
	typeWriter();

	document.addEventListener('keydown', keyEscape);

	paragraph.forEach(p => { //Detach the enter event listener
		p.removeEventListener('keydown', keyEnter);
	});

	headerButton.tabIndex = "-1"; //Prevent the header from opening when tab indexing when the image is open

	headerLinks.forEach(link => {
		link.tabIndex = "-1";
	});

	expandedImageWrapper.addEventListener('click', clickClose);
}

function closeParagraphImage() { //Close the expanded image, remove the elements
	expandedText.innerHTML = '';
	document.body.style.overflowY = '';
	header.style.pointerEvents = '';
	main.style.pointerEvents = '';
	expandedImageWrapper.classList.remove('expanded');
	expandedImage.classList.remove('expanded');
	isTyping = true;

	setTimeout(function() {
		expandedImageWrapper.remove();
	}, 50);

	document.removeEventListener('keydown', keyEscape);

	paragraph.forEach(p => { //Reattach the enter event listener on closing the image
		p.addEventListener('keydown', keyEnter);
	});

	headerButton.tabIndex = ""; //Enable back the header from opening when tab indexing when the image is open

	headerLinks.forEach(link => {
		link.tabIndex = "";
	});

	expandedImageWrapper.removeEventListener('click', clickClose);
}

function clickClose(event) { //Clicking inside the expanded image wrapper closes the expanded image (except when clicking on the image)
	if (!expandedImage.contains(event.target)) {
		closeParagraphImage();
	}
}

function keyEnter(event) { //Open the image on enter key down
	if (event.key === 'Enter') {
		const image = event.currentTarget.querySelector('.section_paragraph img');
		
		if (image) {
			expandImage(image);
		}
	}
}

function keyEscape(event) { //Close the image on esc key
	if (event.key === 'Escape') {
		closeParagraphImage();
	}
}

document.addEventListener('DOMContentLoaded', function() {
		
	handleParagraphImage();
	
});