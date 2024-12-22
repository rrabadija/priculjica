import {getURL} from './helpers.js';

const startFlag = '';
const currentPage = getURL();

window.onload = () => {
    if (!document.querySelector('audio') || currentPage !== 'nova-prica') {
        return;
    }

    fetch('/php/audio-counter.php', {
        method: 'POST',
        body: JSON.stringify({startFlag: startFlag})
    })

    .then(response => response.json())
	.then(data => console.log(data))
};

export default class AudioPlayer {
	constructor(audio, playButton, seekBar, currentTime, duration) {
		this.audio = audio;
		this.playButton = playButton;
		this.seekBar = seekBar;
		this.currentTime = currentTime;
		this.duration = duration;

		this.playState = 'play';
		this.userInteraction = false;

		if (this.audio.readyState > 0) {
			this.seekBar.max = Math.floor(this.audio.duration);
			this.duration.textContent = this.calculateTime(this.audio.duration);
		}
		else {
			this.audio.addEventListener('loadedmetadata', () => {
				this.seekBar.max = Math.floor(this.audio.duration);
				this.duration.textContent = this.calculateTime(this.audio.duration);
			})
		}

		this.audio.addEventListener('timeupdate', () => {
			if (!this.userInteraction) {
				this.seekBar.value = Math.floor(this.audio.currentTime);
				this.currentTime.textContent = this.calculateTime(this.seekBar.value);

				this.setSeekBarBackgroundSize();
			}

			if (this.playState === 'pause') {
				this.seekBar.addEventListener('mouseup', this.audioPlay);
				this.seekBar.addEventListener('mousedown', this.audioPause);
			}
			else {
				this.seekBar.removeEventListener('mouseup', this.audioPlay);
				this.seekBar.removeEventListener('mousedown', this.audioPause);
			}

			this.audioCount();
		});

		this.audio.addEventListener('ended', (event) => {
			if (event) {
				this.playButton.classList.remove('active');

				this.seekBar.removeEventListener('mouseup', this.audioPlay);
				this.seekBar.removeEventListener('mousedown', this.audioPause);

				this.playState = 'play';
			}
		})

		this.playButton.addEventListener('click', () => {
			if (this.playState === 'play') {
				this.audio.play();
				this.playState = 'pause';
			}
			else {
				this.audio.pause();
				this.playState = 'play';
			}

			this.playButton.classList.toggle('active');
		});

		this.seekBar.addEventListener('input', () => {
			this.currentTime.textContent = this.calculateTime(this.seekBar.value);
			this.userInteraction = true;

			this.setSeekBarBackgroundSize();
		});

		this.seekBar.addEventListener('change', () => {
			this.audio.currentTime = this.seekBar.value;
			this.userInteraction = false;
		});
	}

	calculateTime = (time) => {
		const sec = Math.floor(time % 60);
		const seconds = sec < 10 ? `0${sec}` : `${sec}`;
		const minutes = Math.floor(time / 60);

		return `${minutes}:${seconds}`;
	}

	setSeekBarBackgroundSize = () => {
		this.seekBar.style.setProperty('--background-size', `${this.getSeekBarBackgroundSize()}%`);
	}

	getSeekBarBackgroundSize = () => {
		const min = this.seekBar.min || 0;
		const max = this.seekBar.max || 100;
		const value = this.seekBar.value;

		let size = (value - min) / (max - min);

		if (this.seekBar.value <= 5) {
			size = size * 150;
		}
		else if (this.seekBar.value <= 15) {
			size = size * 115;
		}
		else {
			size = size * 99;
		}
		
		return size;
	}

	audioPlay = () => {
		this.audio.play()
	}

	audioPause = () => {
		this.audio.pause();
	}

	audioCount = () => {
		const currentTime = parseInt(this.audio.currentTime);
    	const audioDuration = parseInt(this.audio.duration);

		fetch('/php/audio-counter.php', {
			method: 'POST',
			body: JSON.stringify({
				currentTime: currentTime,
				audioDuration: audioDuration
			})
		})
	
		.then(response => response.json())
		.then(data => console.log(data))
	}
}