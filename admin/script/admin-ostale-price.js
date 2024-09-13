const expandInfoButton = document.querySelector('.admin_info_button');
const info = document.querySelector('.admin_info');
const adminInfo = document.querySelector('.admin_info_data_container');

function toggleInfo() {
    expandInfoButton.classList.toggle('expand');
    info.classList.toggle('expand');
    adminInfo.classList.toggle('active');
}

expandInfoButton.addEventListener('click', toggleInfo);

const infoDiv = document.querySelector('.admin_info_buttons_container');
const infoHeight = document.querySelector('.admin_info_height');

function resizeControls() { //Resizes the admin controls <div>, making it scrollable on all screen heights
    const height = infoHeight.offsetHeight;
    infoDiv.style.height = (height - 60) + 'px';
}

resizeControls();

window.addEventListener('resize', () => {
    resizeControls();
});