title.addEventListener('input', () => { //Update session variable with title
    AJAXSession();
});

text.addEventListener('input', () => { //Update session variable with text
    setAlignment(); //Get the element and set its alignment - line 245
    AJAXSession();
});

function deleteWhiteSpace() { //Delete the single whitespace probably added by some browsers inside the contenteditable div so that the placeholder text shows
    if (text.innerHTML.trim() === '') {
        text.innerHTML = '';
    }
}

deleteWhiteSpace();

deleteText.addEventListener('click', () => { //Clear the innerHTML of the contenteditable tags, set audioBool to false to destroy the audio player, clear the audio source
    if (getCurrentURL() === 'admin-nova-prica') { //Prevents the button from working on admin-nova-prica.php if it's populated with the data from the database
        title.textContent = '';
        text.innerHTML = '';
        const audioBool = false;
        const audioSrc = '';
        const audioLen = '';

        const sectionAudio = document.querySelector('.section_audio');

        if (sectionAudio) {
            sectionAudio.remove();
        }

        AJAXSession();
        AJAXSessionAudio(audioBool, audioSrc, audioLen);

        alignmentBefore = null; //Set the previous alignment to null on cleaning the innerHTML
        localStorage.setItem('alignment', alignmentBefore);
    }
});

$('.section_paragraph').on('keyup', function (e) { //Creates <p> tags instead of <div> tags
    var node = null;
    var sel = getSelected();

    if (sel.focusNode) {
        node = sel.focusNode.parentNode;
    }

    var nodeText = $(node).text();

    var keyShortcut = /[^\S\r\n]{3}/g;

    if (!$(node).is('p') && e.keyCode !== 8 && e.keyCode !== 46) { //Inserts <p> tag on typing inside the contenteditable <div>
        document.execCommand('formatBlock', false, 'p');

        var childNodes = text.childNodes;

        for (var i = 0; i < childNodes.length; i++) { //Remove the duplicated character (removes all content that is not <p> and <span> tags)
            var childNode = childNodes[i];

            if (childNode.nodeName.toLowerCase() !== 'p' && childNode.nodeName.toLowerCase() !== 'span') {
                text.removeChild(childNode);
            }
        }
    }

    if (e.keyCode === 13) { //Changes the <div> tag created by the first Enter key press into a <p> tag
        $('.section_paragraph').each(function () {
            $(this).html($(this).html().replace(/<div>/, '<p>').replace(/<\/div>/, '</p>'));
        });

        var newP = $('.section_paragraph p:last');
        var range = document.createRange();
        var sel = getSelected();

        range.setStart(newP[0], newP[0].childNodes.length); //Move the caret into the second element
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);

        return;
    }

    if (nodeText.match(keyShortcut) && $(node).is('p') && e.type === 'paste') { //Enter key press events and paste events create <p> tags
        $(node).replaceWith('<code></code>');
    }
    else if (nodeText.match(keyShortcut) && $(node).is('code')) {
        $(node).replaceWith('');
    }
});

function getSelected() { //Gets text selection
    if (window.getSelection) {
        return window.getSelection();
    }
    else if (document.getSelection) {
        return document.getSelection();
    }
    else {
        var selection = document.selection && document.selection.createRange();

        if (selection.text) {
            return selection.text;
        }

        return false;
    }
}

function paste(event) { //Strips HTML content off copypasted text
    event.preventDefault();

    var plainText = (event.clipboardData || window.Clipboard).getData('text/plain'); //Strips HTML content off clipboard text, replaces newline characters with <p> tags
    var formattedHTML = plainText.replace(/</g, '&lt;').replace(/>/g, '&gt;');

    document.execCommand("insertHTML", false, formattedHTML);
}

text.addEventListener('paste', paste);
title.addEventListener('paste', paste);

const imageInput = document.querySelector('.admin_controls_image input:first-of-type');
const imageLabel = document.querySelector('.admin_controls_image label');
const imageLabelPlus = document.querySelector('.admin_controls_image label i'); //<i> tag with the plus symbol inside of the label

imageInput.addEventListener('change', () => { //Input type file event listener for images, when the file is added, change the background image of the label
    var src = imageInput.files[0].name;
    src = `/priculjica/db_img/${src}`;

    imageLabel.style.backgroundImage = `url(${src})`;

    if (src) { //If the image source is set, hide the plus symbol from the label
        imageLabelPlus.style.display = 'none';
    }
});

const imageButton = document.querySelector('.admin_controls_image_insert_image');

imageButton.addEventListener('click', (src, alt, opis) => { //Inserts the image and the <span> tag wrapping it
    if (imageInput.files.length > 0 && imageInput.files[0].name != '') {
        src = imageInput.files[0].name;
        src = `/priculjica/db_img/${src}`;
        alt = document.querySelector('.admin_controls_image input:nth-of-type(2)').value;
        opis = document.querySelector('.admin_controls_image input:nth-of-type(3)').value;

        const imageSpan = document.createElement('span');
        const image = document.createElement('img');

        imageSpan.setAttribute('contenteditable', 'false'); //Set <span> tag and image attributes
        imageSpan.classList.add('paragraph_image');
        imageSpan.tabIndex = '0';
        image.setAttribute('src', src);
        image.setAttribute('alt', alt);
        image.dataset.opis = opis;

        imageSpan.appendChild(image); //Append the span <tag> and the image
        text.appendChild(imageSpan);
        
        imageInput.value = ''; //Reset the input type file to allow the same file to be selected
        document.querySelector('.admin_controls_image input:nth-of-type(2)').value = ''; //Reset the alt text value
        document.querySelector('.admin_controls_image input:last-of-type').value = ''; //Reset the opis value
        imageLabel.style.backgroundImage = ''; //Remove the label background image
        imageLabelPlus.style.display = ''; //Unhide the plus symbol from the label

        setStart();
        AJAXSession();
    }
});

function setStart() { //Inserts a zero width character to set the caret position
    let newParagraph = document.createElement('p');

    newParagraph.innerHTML = '&#8203;'; //Insert a zero width character so that the caret moves inside the newly created paragraph
    text.appendChild(newParagraph);

    let range = document.createRange(); //Move the caret at the start of the paragraph and focus it
    let sel = window.getSelection();
    
    range.setStart(newParagraph, 0);
    range.setEnd(newParagraph, 0);
    sel.removeAllRanges();
    sel.addRange(range);
}

const alignRightButton = document.querySelector('.admin_align_right_button');
const alignCenterButton = document.querySelector('.admin_align_center_button');
const alignLeftButton = document.querySelector('.admin_align_left_button');
const alignJustifyButton = document.querySelector('.admin_align_justify_button');
const widthInput = document.querySelector('.admin_controls input');

var alignmentBefore = localStorage.getItem('alignment') || null; //Previous alignment
let selectedParagraph = null; //Variable for storing the selected paragraph

function getSelectionElement(event) { //Get the paragraph on click
    if (event && event.target.tagName === 'P') {
        selectedParagraph = event.target;
    }

    return selectedParagraph;
}

function align(alignment) { //Gives the paragraph an alignment class
    const element = getSelectionElement();

    if (element) {
        if (alignmentBefore === alignment) {
            element.classList.remove(`align_${alignment}`);
            element.removeAttribute('class');
            alignmentBefore = null;
        }
        else {
            if (alignmentBefore) {
                element.classList.remove(`align_${alignmentBefore}`);
            }

            element.classList.add(`align_${alignment}`);
            alignmentBefore = alignment;
        }

        AJAXSession();

        localStorage.setItem('alignment', alignmentBefore); //Stores the previous alignment value into the local storage, since the text persists through page refresh

        text.focus();
    }
}

function alignmentButtons() { //Alignment buttons event listeners
    alignRightButton.addEventListener('click', () => {
        align('right');
    });

    alignCenterButton.addEventListener('click', () => {
        align('center');
    });

    alignLeftButton.addEventListener('click', () => {
        align('left');
    });

    alignJustifyButton.addEventListener('click', () => {
        align('justify');
    });
}

function setAlignment() { //Get the element and set its alignment
    const element = getSelectionElement();

    if (element) {
        alignmentButtons();
        AJAXSession();
    }
}

text.addEventListener('click', (event) => { //Event listener click for the contenteditable div
    if (event.target.tagName === 'IMG') {
        expandImage(event.target);
    }
    else {
        getSelectionElement(event); //Get the paragraph on click function call
        setAlignment();
        elementWidth();
    }
});

function elementWidth() { //Gets the width of the <p> tag
    const element = getSelectionElement();

    if (element) {
        const textWidth = element.parentElement.offsetWidth;
        const width = Math.round((element.offsetWidth / textWidth) * 100);
        widthInput.value = width;
    }
}

widthInput.addEventListener('focus', () => {
    const element = getSelectionElement();

    if (element) {
        const inputEventListener = (event) => { //Sets the width of the <p> tag based on the input value on Enter press
            if (event.key === 'Enter') {
                event.preventDefault();
                element.style.width = widthInput.value + '%';

                AJAXSession();
            }
        };

        widthInput.addEventListener('keydown', inputEventListener);

        widthInput.addEventListener('blur', () => {
            widthInput.removeEventListener('keydown', inputEventListener);
        });
    }
});

const italicButton = document.querySelector('.admin_italic_button');
const boldButton = document.querySelector('.admin_bold_button');

function selectedText(tag) { //Toggle bold and italic styling (AKA the biggest BS I've ever seen)
    var selection = getSelected();
    if (!selection.rangeCount) return;

    const range = selection.getRangeAt(0);
    if (!range) return;

    if (text.contains(range.commonAncestorContainer)) {
        const selectedText = selection.toString();

        if (selectedText.length > 0) {
            let newRange = document.createRange();
            let containerNode = range.commonAncestorContainer;

            while (containerNode && containerNode.nodeName !== tag && containerNode.parentNode) {
                containerNode = containerNode.parentNode;
            }

            if (containerNode.nodeName === tag) {
                const textNode = document.createTextNode(containerNode.textContent);
                const parent = containerNode.parentNode;

                parent.replaceChild(textNode, containerNode);

                newRange.selectNodeContents(textNode);
            }
            else {
                const boldOrItalic = document.createElement(tag.toLowerCase());

                boldOrItalic.textContent = selectedText;

                range.deleteContents();
                range.insertNode(boldOrItalic);

                newRange.selectNodeContents(boldOrItalic);
            }

            selection.removeAllRanges();
            selection.addRange(newRange);
        }
    }

    AJAXSession();
}

italicButton.addEventListener('click', () => {
    selectedText('EM');
});

boldButton.addEventListener('click', () => {
    selectedText('STRONG');
});