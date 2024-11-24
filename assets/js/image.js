export default class Image {
    constructor(main, header, paragraph, paragraphImage) {
        this.main = main;
        this.header = header;
        this.paragraph = paragraph;
        this.paragraphImage = paragraphImage;

        this.expandedImageWrapper = document.createElement('div');
        this.expandedImage = document.createElement('img');
        this.expandedText = document.createElement('h2');
        this.closeButton = document.createElement('button');

        this.expandedImageWrapper.classList = ('expanded_image_wrapper');

        this.isTyping = false;

        this.handleClickCloseImage = (event) => this.clickCloseImage(event);
        this.handleKeyEnter = (event) => this.keyEnter(event);
        this.handleKeyEscape = (event) => this.keyEscape(event);
        this.handlePreventTabIndex = (event) => this.preventTabIndex(event);

        this.handleEventListeners();
    }

    expandImage = (image) => {
        this.main.insertBefore(this.expandedImageWrapper, this.main.firstChild);

        document.body.style.overflowY = 'hidden';
        this.main.style.pointerEvents = 'none';
	    this.header.style.pointerEvents = 'none';

        this.expandedImageWrapper.appendChild(this.expandedImage);
	    this.expandedImageWrapper.appendChild(this.expandedText);
	    this.expandedImageWrapper.appendChild(this.closeButton);

        this.expandedImage.src = image.src;

        setTimeout(() => {
            this.expandedImageWrapper.classList.add('expanded');
        }, 1);
        
        setTimeout(() => {
            this.expandedImage.classList.add('expanded');
        }, 25);

        this.isTyping = false;

        let i = 0;
	    let txt = image.dataset.opis;
	    let txtSpeed = 30;

        const typeWriter = () => {
            if (i < txt.length && !this.isTyping) {
                this.expandedText.innerHTML += txt.charAt(i);
                i++;
                setTimeout(typeWriter, txtSpeed);
            }
        }

        typeWriter();

        document.addEventListener('keydown', this.handleKeyEscape);
        document.addEventListener('keydown', this.handlePreventTabIndex);

        this.expandedImageWrapper.addEventListener('click', this.handleClickCloseImage);

        this.paragraph.forEach(paragraph => {
            paragraph.removeEventListener('keydown', this.handleKeyEnter);
        });
    }

    closeImage = () => {
        document.body.style.overflowY = '';
        this.main.style.pointerEvents = '';
	    this.header.style.pointerEvents = '';

        this.expandedImageWrapper.classList.remove('expanded');
	    this.expandedImage.classList.remove('expanded');
	    this.expandedText.innerHTML = '';

        setTimeout(() => {
            this.expandedImageWrapper.remove();
        }, 50);

        this.isTyping = true;

        document.removeEventListener('keydown', this.handleKeyEscape);
        document.removeEventListener('keydown', this.handlePreventTabIndex);

        this.expandedImageWrapper.removeEventListener('click', this.handleClickCloseImage);

        this.paragraph.forEach(paragraph => {
            paragraph.addEventListener('keydown', this.handleKeyEnter);
        });
    }

    clickCloseImage = (event) => {
        if (!this.expandedImage.contains(event.target) && !this.expandedText.contains(event.target)) {
            this.closeImage();
        }
    }

    keyEnter = (event) => {
        if (event.key === 'Enter') {
            const paragraphImage = event.currentTarget.querySelector('.section_paragraph img');
            
            if (paragraphImage) {
                this.expandImage(paragraphImage);
            }
        }
    }

    keyEscape = (event) => {
        if (event.key === 'Escape') {
            this.closeImage();
        }
    }

    preventTabIndex = (event) => {
        if (event.key === 'Tab') {
            event.preventDefault();
            this.closeButton.focus();
        }
    }

    handleEventListeners = () => {
        this.paragraph.forEach(paragraph => {
            paragraph.addEventListener('keydown', this.handleKeyEnter);
        });

        this.paragraphImage.forEach(image => {
            image.addEventListener('click', () => this.expandImage(image));
        });
    }
}