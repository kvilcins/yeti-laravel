document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('filtersForm');
    const priceRangeSelect = document.getElementById('price_range');
    const priceFromInput = document.querySelector('input[name="price_from"]');
    const priceToInput = document.querySelector('input[name="price_to"]');

    if (!form) return;

    const handlePriceRangeChange = () => {
        if (priceRangeSelect.value) {
            priceFromInput.value = '';
            priceToInput.value = '';
        }
    };

    const handleManualPriceInput = () => {
        if (priceRangeSelect) {
            priceRangeSelect.value = '';
        }
    };

    if (priceRangeSelect) {
        priceRangeSelect.addEventListener('change', handlePriceRangeChange);
    }

    if (priceFromInput && priceToInput) {
        priceFromInput.addEventListener('input', handleManualPriceInput);
        priceToInput.addEventListener('input', handleManualPriceInput);
    }
});
