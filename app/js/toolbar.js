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

        const throttleToolbarButtons = throttle((button) => this.toggleToolbarControls(button), 300);

        toolbarButtons.forEach((button) => {
            button.addEventListener('click', () => throttleToolbarButtons(button));
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

            this.toolbar.classList.add('expanded');
            this.toolbarControls.classList.add('expand');

            this.toolbarButtons.forEach(button => button.classList.remove('active'));
            button.classList.add('active');
        }
        else {
            this.currentToolbarControl = button.classList;

            this.toolbarControls.style.width = '60px';

            if (this.currentToolbarControl !== this.lastToolbarControl) {
                this.lastToolbarControl = button.classList;

                this.toolbarButtons.forEach(button => button.classList.remove('active'));
                button.classList.add('active');

                setTimeout(() => {
                    this.toolbarControls.style.width = '';
                }, 300);
            }
            else {
                this.closeToolbarControls();
            }
        }

        this.generateToolbarControls(button.classList[0]);
    }

    closeToolbarControls = () => {
        this.toolbarControls.classList.remove('expand');
        this.toolbarButtons.forEach(button => button.classList.remove('active'));

        this.toolbarControls.style.height = '50%';

        setTimeout(() => {
            this.toolbar.classList.remove('expanded');

            this.toolbarControls.style.width = '';
            this.toolbarControls.style.height = '';
        }, 300);
    }

    generateToolbarControls = (toolbarControl) => {
        fetch('/php/toolbar.php', {
            method: 'POST',
            body: JSON.stringify({toolbarControl: toolbarControl})
        })

        .then(response => response.json())
        .then(data =>
            this.currentToolbarControl === this.lastToolbarControl
                ? setTimeout(() => {this.toolbarControls.innerHTML = data}, 250)
                : this.toolbarControls.innerHTML = data
        );
    }

    resizeControls = () => {
        this.toolbarButtonsContainer.style.height = `${(this.toolbarHeight.offsetHeight - 60)}px`;
    }
}