document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.form');
    if (!form) return;

    const submitButton = form.querySelector('[type="submit"]');
    const photoLabel = form.querySelector('label[for="lot-img"]');
    const photoPreview = form.querySelector('.form__preview img');
    const previewContainer = form.querySelector('.form__preview');
    const inputFileContainer = form.querySelector('.form__file-label');
    const fileInput = form.querySelector('input[type="file"]');

    const checkFormForErrors = () => {
        let hasErrors = false;
        form.querySelectorAll('input, select, textarea').forEach(input => {
            const isValid = input.checkValidity();
            input.classList.toggle('form__item--invalid', !isValid);
            if (input.nextElementSibling) {
                input.nextElementSibling.classList.toggle('form__error--visible', !isValid);
            }
            hasErrors = !isValid || hasErrors;
        });
        form.classList.toggle('form--invalid', hasErrors);
    };

    const handleFileInputChange = (event) => {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            if (photoPreview) photoPreview.src = e.target.result;
            if (previewContainer) {
                previewContainer.classList.add('form__preview--visible');
            }
            if (inputFileContainer) inputFileContainer.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    };

    const handleSubmitClick = (event) => {
        checkFormForErrors();
        if (form.checkValidity()) form.submit();
        if (fileInput && !fileInput.value) {
            photoLabel.classList.remove('hidden');
        }
    };

    submitButton.addEventListener('click', handleSubmitClick);
    if (fileInput) fileInput.addEventListener('change', handleFileInputChange);
});
