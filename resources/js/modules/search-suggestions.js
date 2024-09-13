document.getElementById('search-input').addEventListener('input', function () {
    let query = this.value;
    
    if (query.length > 2) {
        fetch(`/search-suggestions?query=${query}`)
            .then(response => response.json())
            .then(data => {
                let suggestions = data.map(item => `<option value="${item.title}">${item.title}</option>`).join('');
                document.getElementById('search-suggestions').innerHTML = suggestions;
            })
            .catch(error => console.error('Error:', error));
    }
});

document.getElementById('search-suggestions').addEventListener('change', function (event) {
    let selectedValue = event.target.value;
    
    if (selectedValue.length > 0) {
        document.getElementById('search-input').value = selectedValue;
        performSearch(selectedValue);
    }
});

document.getElementById('search-input').addEventListener('change', function () {
    let enteredValue = this.value;
    
    if (enteredValue.length > 0) {
        performSearch(enteredValue);
    }
});

document.getElementById('search-button').addEventListener('click', function () {
    let query = document.getElementById('search-input').value;
    performSearch(query);
});

function performSearch(query) {
    if (query.length > 0) {
        window.location.href = `/search?search=${encodeURIComponent(query)}`;
    }
}
