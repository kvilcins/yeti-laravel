document.addEventListener('DOMContentLoaded', () => {
    const initSearchSuggestions = () => {
        const searchInput = document.getElementById('search-input');
        const suggestionsContainer = document.getElementById('search-suggestions');
        const searchButton = document.getElementById('search-button');

        if (!searchInput || !suggestionsContainer || !searchButton) return;

        const performSearch = (query) => {
            if (query.length > 0) {
                window.location.href = `/search?search=${encodeURIComponent(query)}`;
            }
        };

        const hideSuggestions = () => {
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.classList.remove('active');
        };

        searchInput.addEventListener('input', (event) => {
            const query = event.target.value.trim();

            if (query.length > 2) {
                fetch(`/search-suggestions?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const suggestions = data.map(item =>
                                `<li class="suggestion-item">${item.title}</li>`
                            ).join('');
                            suggestionsContainer.innerHTML = suggestions;
                            suggestionsContainer.classList.add('active');

                            document.querySelectorAll('.suggestion-item').forEach(item => {
                                item.addEventListener('click', () => {
                                    searchInput.value = item.innerText;
                                    performSearch(item.innerText);
                                });
                            });
                        } else {
                            hideSuggestions();
                        }
                    })
                    .catch(error => {
                        hideSuggestions();
                    });
            } else {
                hideSuggestions();
            }
        });

        searchButton.addEventListener('click', () => {
            const query = searchInput.value.trim();
            performSearch(query);
        });

        document.addEventListener('click', (event) => {
            if (!searchInput.contains(event.target) && !suggestionsContainer.contains(event.target)) {
                hideSuggestions();
            }
        });
    };

    initSearchSuggestions();
});
