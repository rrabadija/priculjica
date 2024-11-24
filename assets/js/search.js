const searchInput = document.querySelector('.main_searchbar_wrapper input');
const searchResults = document.querySelector('section');

searchInput.addEventListener('input', function() {
	const query = searchInput.value.trim();

	fetch('/priculjica/php/search.php', {
		method: 'POST',
		body: JSON.stringify({query: query})
	})

	.then(response => response.json())
	.then(data => searchResults.innerHTML = data.join(''));
});