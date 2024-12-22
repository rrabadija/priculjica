import Toolbar from './toolbar.js';

class Editor {
    constructor(contentEditableTitle, contentEditableText) {
        this.contentEditableTitle = contentEditableTitle;
        this.contentEditableText = contentEditableText;

        this.clearContentEditable(this.contentEditableTitle);
        this.clearContentEditable(this.contentEditableText);

        this.contentEditableTitle.addEventListener('input', () => {
            this.writeSessionData();
            this.clearContentEditable(contentEditableTitle);
        });

        this.contentEditableText.addEventListener('input', () => {
            this.writeSessionData();
            this.clearContentEditable(contentEditableText);
        });
    }

    clearContentEditable = (contentEditable) => {
        if (contentEditable.innerText.trim() === '') {
            contentEditable.innerHTML = '';
        }
    }

    writeSessionData = () => {
        fetch('/php/story.php', {
            method: 'POST',
            body: JSON.stringify({
                title: this.contentEditableTitle.innerHTML,
                text: this.contentEditableText.innerHTML
            })
        })
    }
}

new Editor (
    document.querySelector('.section_header h1'),
    document.querySelector('.section_paragraph')
);

let toolbarButtons;

new Toolbar (
    document.querySelector('.toolbar_height'),
    document.querySelector('.toolbar_toggle_button'),
    document.querySelector('.toolbar'),
    document.querySelector('.toolbar_buttons_container'),
    
    toolbarButtons = [
        document.querySelector('.toolbar_image'),
        document.querySelector('.toolbar_audio')
    ],

    document.querySelector('.toolbar_controls_container')
);