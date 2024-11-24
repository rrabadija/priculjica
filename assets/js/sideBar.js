export default class SideBar {
	constructor(sideBar, sideBarButton, sideBarButtonIcon1, sideBarButtonIcon2) {
		this.sideBar = sideBar;
		this.sideBarButton = sideBarButton;
		this.sideBarButtonIcon1 = sideBarButtonIcon1;
		this.sideBarButtonIcon2 = sideBarButtonIcon2;
	}

	sideBarButtonAnimations = () => {
		this.sideBarButton.classList.toggle('active');
		this.sideBarButtonIcon1.classList.toggle('active');
		this.sideBarButtonIcon2.classList.toggle('active');
	}

	resetSideBarButtonAnimations = () => {
		this.sideBarButton.style.transition = 'none';
		this.sideBarButtonIcon1.style.transition = 'none';
		this.sideBarButtonIcon2.style.transition = 'none';

		this.sideBarButtonAnimations();

		setTimeout(() => {
			this.sideBarButton.style.transition = '';
			this.sideBarButtonIcon1.style.transition = '';
			this.sideBarButtonIcon2.style.transition = '';
		}, 1);
	}
}