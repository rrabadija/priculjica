import './story.js';
import './toolbar.js';

class Editor {
    constructor(contentEditableTitle, contentEditableText) {
        this.contentEditableTitle = contentEditableTitle;
        this.contentEditableText = contentEditableText;

        this.deleteWhiteSpace();

        this.contentEditableTitle.addEventListener('input', () => {

        });

        this.contentEditableText.addEventListener('input', () => {

        });
    }

    deleteWhiteSpace = () => {
        if (this.contentEditableText.innerHTML.trim() === '') {
            this.contentEditableText.innerHTML = '';
        }
    }
}

new Editor (
    document.querySelector('.section_header h1'),
    document.querySelector('.section_paragraph')
);