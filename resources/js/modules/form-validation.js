document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.form--add-lot');
    if (!form) return;
    
    const submitButton = form.querySelector('[type="submit"]');
    const photoLabel = form.querySelector('label[for="photo2"]');
    const photoPreview = form.querySelector('.preview__img img');
    const previewContainer = form.querySelector('.preview');
    const inputFileContainer = form.querySelector('.form__input-file');
    const fileInput = form.querySelector('input[type="file"]');
    
    // Проверка формы на наличие ошибок
    const checkFormForErrors = () => {
        let hasErrors = false;
        form.querySelectorAll('input, select, textarea').forEach(input => {
            const isValid = input.checkValidity();
            input.classList.toggle('form__item--invalid', !isValid);
            if (input.nextElementSibling) {
                input.nextElementSibling.style.display = isValid ? 'none' : 'block';
            }
            hasErrors = !isValid || hasErrors;
        });
        form.classList.toggle('form--invalid', hasErrors);
    };
    
    // Отображение выбранного изображения в превью
    const handleFileInputChange = (event) => {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = (e) => {
            if (photoPreview) photoPreview.src = e.target.result;
            if (previewContainer) {
                previewContainer.classList.add('form__item--uploaded');
                previewContainer.style.display = 'block';
                previewContainer.style.position = 'unset';
            }
            if (inputFileContainer) inputFileContainer.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    };
    
    // Обработка клика по кнопке отправки формы
    const handleSubmitClick = (event) => {
        checkFormForErrors();
        if (form.checkValidity()) form.submit();
        if (fileInput && !fileInput.value) photoLabel.style.display = 'block';
    };
    
    submitButton.addEventListener('click', handleSubmitClick);
    if (fileInput) fileInput.addEventListener('change', handleFileInputChange);
});
