export function debounce(callback, delay) {
	let timeout = null;
	
	return (...args) => {
		window.clearTimeout(timeout);
		
		timeout = window.setTimeout(() => {
			callback(...args);
		}, delay);
	};
}

export function throttle(callback, delay) {
	let lastCall = 0;

	return (...args) => {
		const now = new Date().getTime();

		if (now - lastCall >= delay) {
			lastCall = now;

			callback(...args);
		}
	};
}

export function getURL() {
	let URL = window.location.pathname.split('/');
	
	URL = URL.pop() || URL.pop();

	return URL;
}