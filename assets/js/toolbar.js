import {throttle} from './helpers.js';

export default class Toolbar {
    constructor(toolbarHeight, toolbarToggleButton, toolbar, toolbarButtonsContainer, toolbarButtons, toolbarControls) {
        this.toolbarHeight = toolbarHeight;
        this.toolbarToggleButton = toolbarToggleButton;
        this.toolbar = toolbar;
        this.toolbarButtonsContainer = toolbarButtonsContainer;
        this.toolbarButtons = toolbarButtons;
        this.toolbarControls = toolbarControls;

        this.lastToolbarControl = null;
        this.currentToolbarControl = null;

        this.resizeControls();

        window.addEventListener('resize', this.resizeControls);

        toolbarToggleButton.addEventListener('click', throttle(() => {
            this.toggleToolbar();
        }, 300));

        toolbarButtons.forEach((button, index) => {
            if (index <= 1) {
                button.addEventListener('click', throttle(this.toggleToolbarControls.bind(this, button), 300));
            }
        });
    }

    toggleToolbar = () => {
        if (this.toolbar.classList.contains('expand') && this.toolbarControls.classList.contains('expand')) {
            this.lastToolbarControl = null;
            this.currentToolbarControl = null;

            this.closeToolbarControls();

            setTimeout(() => {
                this.toolbar.classList.remove('expand');
                this.toolbarToggleButton.classList.remove('expand');

                this.toolbarControls.innerHTML = '';
            }, 300);

            return;
        }

        this.toolbar.classList.toggle('expand');
        this.toolbarToggleButton.classList.toggle('expand');
    }

    toggleToolbarControls = (button) => {
        if (!this.toolbarControls.classList.contains('expand')) {
            this.lastToolbarControl = button.classList;

            this.toolbarControls.classList.add('expand');

            this.toolbarButtons.forEach(button => button.classList.remove('active'));
            button.classList.add('active');

            this.toolbar.classList.add('expanded');
        }
        else {
            this.currentToolbarControl = button.classList;

            this.toolbarControls.style.width = '60px';

            if (this.currentToolbarControl !== this.lastToolbarControl) {
                this.lastToolbarControl = button.classList;

                this.toolbarButtons.forEach(button => button.classList.remove('active'));
                button.classList.add('active');

                setTimeout(() => {
                    this.toolbarControls.style.width = '350px';

                    setTimeout(() => {
                        this.toolbarControls.style.width = '';
                    }, 1);
                }, 300);
            }
            else {
                this.closeToolbarControls();
            }
        }

        this.generateToolbarControls(button.classList[0]);
    }

    closeToolbarControls = () => {
        this.toolbarButtons.forEach(button => button.classList.remove('active'));

        this.toolbarControls.style.height = '50%';

        this.toolbarControls.classList.remove('expand');

        setTimeout(() => {
            this.toolbar.classList.remove('expanded');

            this.toolbarControls.style.width = '';
            this.toolbarControls.style.height = '';
        }, 300);
    }

    generateToolbarControls = (toolbarControl) => {
        fetch('/priculjica/php/toolbar.php', {
            method: 'POST',
            body: JSON.stringify({toolbarControl: toolbarControl})
        })

        .then(response => response.json())
        .then(data => {
            if (this.currentToolbarControl === this.lastToolbarControl) {
                setTimeout(() => {
                    this.toolbarControls.innerHTML = data;
                }, 200);
            }
            else {
                this.toolbarControls.innerHTML = data;
            }
        });
    }

    resizeControls = () => {
        this.toolbarButtonsContainer.style.height = `${(this.toolbarHeight.offsetHeight - 60)}px`;
    }
}

let toolbarButtons = [];

const toolbar = new Toolbar (
    document.querySelector('.toolbar_height'),
    document.querySelector('.toolbar_toggle_button'),
    document.querySelector('.toolbar'),
    document.querySelector('.toolbar_buttons_container'),
    
    toolbarButtons = [
        document.querySelector('.toolbar_image'),
        document.querySelector('.toolbar_audio')
    ].filter(button => button !== null),

    document.querySelector('.toolbar_controls_container')
);