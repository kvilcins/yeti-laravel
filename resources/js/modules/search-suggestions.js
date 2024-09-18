// Обработчик ввода в поле поиска
document.getElementById('search-input').addEventListener('input', (event) => {
    const query = event.target.value;
    
    // Если запрос больше 2 символов, запрашиваем подсказки
    if (query.length > 2) {
        fetch(`/search-suggestions?query=${query}`)
            .then(response => response.json())
            .then(data => {
                // Создаем список подсказок и добавляем его в DOM
                const suggestions = data.map(item =>
                    `<li class="suggestion-item">${item.title}</li>`
                ).join('');
                const suggestionsContainer = document.getElementById('search-suggestions');
                suggestionsContainer.innerHTML = suggestions;
                
                // Добавляем обработчик кликов на подсказки
                document.querySelectorAll('.suggestion-item').forEach(item => {
                    item.addEventListener('click', () => {
                        document.getElementById('search-input').value = item.innerText;
                        performSearch(item.innerText);
                    });
                });
            })
            .catch(error => console.error('Error fetching suggestions:', error));
    } else {
        // Очищаем список подсказок, если запрос меньше 3 символов
        document.getElementById('search-suggestions').innerHTML = '';
    }
});

// Обработчик изменения поля поиска
document.getElementById('search-input').addEventListener('change', () => {
    const enteredValue = document.getElementById('search-input').value;
    if (enteredValue.length > 0) {
        performSearch(enteredValue);
    }
});

// Обработчик клика на кнопку поиска
document.getElementById('search-button').addEventListener('click', () => {
    const query = document.getElementById('search-input').value;
    performSearch(query);
});

// Функция для выполнения поиска
const performSearch = (query) => {
    if (query.length > 0) {
        window.location.href = `/search?search=${encodeURIComponent(query)}`;
    }
};
