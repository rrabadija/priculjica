document.documentElement.setAttribute('data-theme', localStorage.getItem('theme') || 'light');

class Theme {
    constructor(toggleSwitch) {
        this.toggleSwitch = toggleSwitch;

        this.setCurrentTheme();

        toggleSwitch.addEventListener('change', this.switchTheme, false);
    }

    switchTheme = (event) => {
        if (event.target.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        }
        else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
    }

    setCurrentTheme = () => {
        const currentTheme = localStorage.getItem('theme') || 'light';

        if (currentTheme) {
            document.documentElement.setAttribute('data-theme', currentTheme);

            if (currentTheme === 'dark') {
                this.toggleSwitch.checked = true;
            }
        }
    }
}

document.addEventListener("DOMContentLoaded", () => {

    new Theme (
        document.querySelector('header aside input')
    );

});