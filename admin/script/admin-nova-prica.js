class Toolbar { //Class for extending the toolbar and extending and hiding toolbar menus on button clicks
    constructor(expandButton, controls, adminControls, controlsButtonImage, controlsButtonAudio, adminControlsImage, adminControlsAudio) { //Constructor for the class
        this.expandButton = expandButton;
        this.controls = controls;
        this.adminControls = adminControls;
        this.controlsButtonImage = controlsButtonImage;
        this.controlsButtonAudio = controlsButtonAudio;
        this.adminControlsImage = adminControlsImage;
        this.adminControlsAudio = adminControlsAudio;

        this.loadEventListeners();
    }

    loadEventListeners() { //Initialize event listeners
        this.expandButton.addEventListener('click', () => this.expandToolbar());
        this.controlsButtonImage.addEventListener('click', () => this.controlButtons(this.controlsButtonImage, this.controlsButtonAudio, this.adminControlsImage, this.adminControlsAudio));
        this.controlsButtonAudio.addEventListener('click', () => this.controlButtons(this.controlsButtonAudio, this.controlsButtonImage, this.adminControlsAudio, this.adminControlsImage));
    }
    
    expandToolbar() {
        this.expandButton.classList.toggle('expand');

        if (!this.adminControls.classList.contains('expand')) { //If the admin controls are closed add the class, expanding the admin controls
            this.adminControls.classList.add('expand');
        }
        else {
            if (this.controls.classList.contains('expand')) { //If the controls are expanded
                this.controlsButtonImage.classList.remove('active');
                this.controlsButtonAudio.classList.remove('active');
                this.expandControls();
    
                setTimeout(() => { //Wait for the controls to close
                    this.adminControls.classList.remove('expand');
                }, 300);
            }
            else {
                this.adminControls.classList.remove('expand');
            }
        }
    }

    controlButtons(button, activeButton, menu, activeMenu) {
        button.classList.toggle('active');

        if (this.controls.classList.contains('expand') && activeMenu.style.display === 'flex') { //Switch the menus if the image or audio menu (menu to be switched) is already expanded
            this.controls.style.width = 60 + 'px'; //Close the menu
    
            setTimeout(() => { //Switch the menus and then expand the menu
                menu.style.display = 'flex';
                activeMenu.style.display = 'none';
                this.controls.style.width = 350 + 'px';
            }, 300);
        }
        else { //If the image or audio menu is not expanded, behave normally - expand the menu, and show the appropriate controls
            menu.style.display = 'flex';
            this.expandControls();
    
            if (activeMenu.style.display === 'flex') { //Hide the menu to be switched
                activeMenu.style.display = 'none';
            }
        }

        if (activeButton.classList.contains('active')) { //Remove the active class from the button associated with the menu to be switched
            activeButton.classList.remove('active');
        }
    }

    expandControls() {
        this.adminControls.classList.toggle('expanded');

        if (!this.controls.classList.contains('expand')) { //If the controls are closed, add the class, expanding the controls
            this.controls.classList.add('expand');
        }
        else {
            this.controls.style.width = 60 + 'px';
    
            setTimeout(() => { //Wait for the width animation to finish
                this.controls.style.height = 60 + 'px';
                this.controls.classList.remove('expand');
    
                setTimeout(() => {
                    this.controls.style.width = ''; //Reset the controls styles
                    this.controls.style.height = '';
                }, 1);
            }, 300);
        }
    }
}

const toolbar = new Toolbar ( //Toolbar class object
    document.querySelector('.admin_controls_button'),
    document.querySelector('.admin_controls_controls_container'),
    document.querySelector('.admin_controls'),
    document.querySelector('.admin_image_button'),
    document.querySelector('.admin_audio_button'),
    document.querySelector('.admin_controls_image'),
    document.querySelector('.admin_controls_audio')
);

const adminDiv = document.querySelector('.admin_controls_buttons_container');
const adminHeight = document.querySelector('.admin_controls_height');

function resizeControls() { //Resizes the admin controls <div>, making it scrollable on all screen heights
    const height = adminHeight.offsetHeight;
    adminDiv.style.height = (height - 60) + 'px';
}

resizeControls();

window.addEventListener('resize', () => {
    resizeControls();
});

const text = document.querySelector('.section_paragraph');
const title = document.querySelector('.section_header h1');
const deleteText = document.querySelector('.admin_clear_button');

function getCurrentURL() {
    var currentURL = window.location.href; //Get current URL to restrict AJAX POST to session variables

    currentURL = currentURL.split('/');
    currentURL = currentURL.filter(URL => URL !== '').pop();

    return currentURL;
}

getCurrentURL();

function getTitleEdit() {
    const currentURL = getCurrentURL();

    if (currentURL !== 'admin-nova-prica') {
        var titleEdit = title.textContent; //Get title that is set in the database for editing
    }

    return titleEdit;
}

const titleEdit = getTitleEdit();

console.log(titleEdit);

function AJAXSession() { //AJAX function for updating session variables on text editing
    var titleContent = title.textContent;
    var textContent = text.innerHTML;

    const image = text.querySelector('img'); //Get the first image inside the contenteditable div

    var imageSrc = '';
    var imageAlt = '';

    if (image) { //If the image element exists in text get its attributes
        imageSrc = image.getAttribute('src');
        imageAlt = image.getAttribute('alt');
    }

    if (getCurrentURL() === 'admin-nova-prica') { //AJAX for session variables only if the nova-prica is empty, without any data from the database
        var xhrIndex = new XMLHttpRequest(); //AJAX for admin-index.php
        xhrIndex.open('POST', '/priculjica/admin/admin.php', true);
        xhrIndex.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        var xhrNovaPrica = new XMLHttpRequest(); //AJAX for admin-nova-prica.php
        xhrNovaPrica.open('POST', '/priculjica/admin/admin-nova-prica.php', true);
        xhrNovaPrica.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        var xhrOstalePrice = new XMLHttpRequest(); //AJAX for admin-ostale-price.php
        xhrOstalePrice.open('POST', '/priculjica/admin/admin-ostale-price.php', true);
        xhrOstalePrice.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        var data = 'title=' + encodeURIComponent(titleContent) + '&textarea=' + encodeURIComponent(textContent) + '&image=' + encodeURIComponent(imageSrc)
        + '&alt=' + encodeURIComponent(imageAlt); //Encode and send data

        xhrIndex.send(data);
        xhrNovaPrica.send(data);
        xhrOstalePrice.send(data);
    }
}

function AJAXSessionAudio(audioBool, audioSrc, audioLen) { //AJAX for session variables for displaying the audio player
    if (getCurrentURL() === 'admin-nova-prica') { //AJAX for session variables only if the nova-prica is empty, without any data from the database
        var xhrAudio = new XMLHttpRequest(); //AJAX for admin-nova-prica.php
        xhrAudio.open('POST', '/priculjica/admin/admin-nova-prica.php', true);
        xhrAudio.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        var data = 'audio=' + encodeURIComponent(audioBool) + '&audioSrc=' + encodeURIComponent(audioSrc) + '&audioLen=' + encodeURIComponent(audioLen); //Encode and send data

        xhrAudio.send(data);
    }
}

function AJAXDatabase(id) { //AJAX function for writing, deleting and editing database data
    var titleContent = title.textContent;
    var textContent = text.innerHTML;

    const image = text.querySelector('img'); //Get the first image inside the contenteditable div

    var imageSrc = '';
    var imageAlt = '';

    if (image) { //If the image element exists in text get its attributes
        imageSrc = image.getAttribute('src');
        imageAlt = image.getAttribute('alt');
    }

    const audio = document.querySelector('audio'); //Get the audio element if it exists

    var audioBool = false;
    var audioSrc = '';
    var audioLen = null;

    if (audio) { //If the audio element exists in text get its attributes
        audioBool = true;
        audioSrc = splitSrc(audio.src);
        audioLen = audio.duration;
    }

    if (titleContent !== '') {
        var xhrDatabase = new XMLHttpRequest(); //AJAX for controls.php (text, image audio)
        xhrDatabase.open('POST', '/priculjica/admin/php/admin-controls.php', true);
        xhrDatabase.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhrDatabase.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);

                if (getCurrentURL() === 'admin-nova-prica') {
                    const resetSession = true;

                    var xhrResetSession = new XMLHttpRequest(); //AJAX for nova-prica.php (resetting the session variables)
                    xhrResetSession.open('POST', '/priculjica/admin/admin-nova-prica.php', true);
                    xhrResetSession.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    var data = 'resetSession=' + encodeURIComponent(resetSession);

                    xhrResetSession.send(data);
                    window.location.href = '/priculjica/admin/admin-ostale-price.php'; //Redirect to admin-ostale-price.php if session variables are reset
                }
            }
        };

        var data = 'title=' + encodeURIComponent(titleContent) + '&textarea=' + encodeURIComponent(textContent) + '&image=' + encodeURIComponent(imageSrc)
        + '&alt=' + encodeURIComponent(imageAlt) + '&audio=' + encodeURIComponent(audioBool) + '&audioSrc=' + encodeURIComponent(audioSrc) + '&audioLen=' + encodeURIComponent(audioLen); //Encode and send data
            
        var buttonId = id;

        if (id === 2) { //Edit text and image from the database
            data += '&edit=' + encodeURIComponent(titleEdit);

            if (titleEdit !== title.textContent) { //If, upon editing, the title is changed, redirect to admin-ostale-price.php
                window.location.href = '/priculjica/admin/admin-ostale-price.php';
            }
        }
        else if (id === 3) { //Delete text and image from the database
            if (titleEdit === title.textContent) {
                data += '&delete=' + encodeURIComponent(titleContent);

                window.location.href = '/priculjica/admin/admin-ostale-price.php'; //Redirect to admin-ostale-price.php if a database record is deleted
            }
        }

        data += '&buttonId=' + encodeURIComponent(buttonId);

        setTimeout(function() {
            xhrDatabase.send(data);
        }, 100);
    }
}

function AJAXSitemap(id) { //Ajax function for writing, editing and deleting records from sitemap.xml
    if (title.textContent !== '') {
        var xhrSitemap = new XMLHttpRequest(); //AJAX for sitemap-generator.php
        xhrSitemap.open('POST', '/priculjica/php/sitemap-generator.php', true);
        xhrSitemap.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        var data = '';

        if (id === 1) {
            data = 'write=' + encodeURIComponent(title.textContent); //Write into sitemap.xml
        }
        else if (id === 2) {
            data = 'edit=' + encodeURIComponent(title.textContent) + '&titleEdit=' + encodeURIComponent(titleEdit); //Edit sitemap.xml
        }
        else {
            data = 'delete=' + encodeURIComponent(title.textContent); //Delete from sitemap.xml
        }

        xhrSitemap.send(data);
        console.log(data);
        
        xhrSitemap.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
            }
        };
    }
}

const saveButton = document.querySelector('.admin_save_button');
const deleteButton = document.querySelector('.admin_delete_button');
const editButton = document.querySelector('.admin_edit_button');

saveButton.addEventListener('click', () => { //Write to database
    AJAXDatabase(1);
    AJAXSitemap(1);
});

editButton.addEventListener('click', () => { //Edit from database
    if (getCurrentURL() !== 'admin-nova-prica') { //Prevents the button from working on admin-nova-prica.php if it's not populated with the data from the database
        AJAXDatabase(2);
        AJAXSitemap(2);
    }
});

deleteButton.addEventListener('click', () => { //Delete from database
    if (getCurrentURL() !== 'admin-nova-prica') { //Prevents the button from working on admin-nova-prica.php if it's not populated with the data from the database
        AJAXDatabase(3);
        AJAXSitemap(3);
    }
});

const audioInput = document.querySelector('.admin_controls_audio input:first-of-type');
const audioLabel = document.querySelector('.admin_controls_audio label');
const audioTitle = document.querySelector('.admin_controls_audio p');

audioInput.addEventListener('change', () => { //Input type file event listener for audio, when the file is added, change the background color and border of the label
    var src = audioInput.files[0].name;

    audioLabel.style.backgroundColor = '#F5D1C3';
    audioLabel.style.border = '15px solid #F5D1C3';
    audioTitle.innerHTML = src; //Name of the audio file to be displayed
});

const audioButton = document.querySelector('.admin_controls_audio_insert_audio');

audioButton.addEventListener('click', (src) => { //Creates and inserts the audio element and its source based on the file attached to the input type file
    const audioElement = document.querySelector('.section_audio'); //If the audio player already exists, prevent the creation and insertion of another one

    if (audioInput.files.length > 0 && audioInput.files[0].name != '' && !audioElement) {
        src = audioInput.files[0].name;

        const sectionAudio = document.createElement('div');
        sectionAudio.classList.add('section_audio');
        const sectionAudioButton = document.createElement('div');
        sectionAudioButton.classList.add('section_audio_button');
        sectionAudio.appendChild(sectionAudioButton);
        const sectionAudioButtonCircle = document.createElement('div');
        sectionAudioButtonCircle.classList.add('section_audio_button_circle');
        sectionAudioButton.appendChild(sectionAudioButtonCircle);
        const h3First = document.createElement('h3');
        sectionAudioButtonCircle.appendChild(h3First);

        const spanArrayFirst = ['P', 'O', 'S', 'L', 'U', 'Š', 'A', 'J'];

        for (i = 0; i < 8; i++) {
            const span = document.createElement('span');
            span.innerHTML = spanArrayFirst[i];
            h3First.appendChild(span);
        }

        const button = document.createElement('button');

        for (i = 0; i < 2; i++) {
            const buttonDiv = document.createElement('div');
            button.appendChild(buttonDiv);
        }

        sectionAudioButtonCircle.appendChild(button);
        const h3Second = document.createElement('h3');
        sectionAudioButtonCircle.appendChild(h3Second);

        const spanArraySecond = ['P', 'R', 'I', 'Č', 'U'];

        for (i = 0; i < 5; i++) {
            const span = document.createElement('span');
            span.innerHTML = spanArraySecond[i];
            h3Second.appendChild(span);
        }

        const sectionAudioSeek = document.createElement('div');
        sectionAudioSeek.classList.add('section_audio_seek');
        sectionAudio.appendChild(sectionAudioSeek);
        const timeSpanFirst = document.createElement('span');
        timeSpanFirst.classList.add('section_audio_timing');
        timeSpanFirst.innerHTML = '0:00';
        sectionAudioSeek.appendChild(timeSpanFirst);
        const range = document.createElement('input');
        range.type = 'range';
        range.max = '100';
        range.value = '0';
        range.setAttribute('preload', 'metadata');
        sectionAudioSeek.appendChild(range);
        const timeSpanLast = document.createElement('span');
        timeSpanLast.classList.add('section_audio_timing');
        timeSpanLast.innerHTML = '0:00';
        sectionAudioSeek.appendChild(timeSpanLast);

        const audio = document.createElement('audio');
        audio.src = `/priculjica/audio/${src}`;
        audio.setAttribute('type', 'audio/mpeg');
        sectionAudio.appendChild(audio);

        document.querySelector('section').insertBefore(sectionAudio, text);

        audio.addEventListener('loadedmetadata', () => {
            const audioBool = true;
            const audioSrc = splitSrc(audio.src);
            const audioLen = audio.duration;

            AJAXSessionAudio(audioBool, audioSrc, audioLen);
        });

        audioPlayer(); //Execute the audio player code logic
    }
});

function splitSrc(url) { //Split the path of the file so that only the name of the file remains
    const path = url.split('/');

    return path[path.length - 1];
}