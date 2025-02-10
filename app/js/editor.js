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
    document.querySelector('.toolbar-height'),
    document.querySelector('.toolbar-toggle-button'),
    document.querySelector('.toolbar'),
    document.querySelector('.toolbar-buttons-container'),
        
    toolbarButtons = [
        document.querySelector('.toolbar-image'),
        document.querySelector('.toolbar-audio')
    ],

    document.querySelector('.toolbar-controls-container')
);