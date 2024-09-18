/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/css/app.scss":
/*!********************************!*\
  !*** ./resources/css/app.scss ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/js/modules/form-validation.js":
/*!*************************************************!*\
  !*** ./resources/js/modules/form-validation.js ***!
  \*************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
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
  const handleFileInputChange = event => {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = e => {
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
  const handleSubmitClick = event => {
    checkFormForErrors();
    if (form.checkValidity()) form.submit();
    if (fileInput && !fileInput.value) photoLabel.style.display = 'block';
  };
  submitButton.addEventListener('click', handleSubmitClick);
  if (fileInput) fileInput.addEventListener('change', handleFileInputChange);
});

/***/ }),

/***/ "./resources/js/modules/search-suggestions.js":
/*!****************************************************!*\
  !*** ./resources/js/modules/search-suggestions.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// Обработчик ввода в поле поиска
document.getElementById('search-input').addEventListener('input', event => {
  const query = event.target.value;

  // Если запрос больше 2 символов, запрашиваем подсказки
  if (query.length > 2) {
    fetch(`/search-suggestions?query=${query}`).then(response => response.json()).then(data => {
      // Создаем список подсказок и добавляем его в DOM
      const suggestions = data.map(item => `<li class="suggestion-item">${item.title}</li>`).join('');
      const suggestionsContainer = document.getElementById('search-suggestions');
      suggestionsContainer.innerHTML = suggestions;

      // Добавляем обработчик кликов на подсказки
      document.querySelectorAll('.suggestion-item').forEach(item => {
        item.addEventListener('click', () => {
          document.getElementById('search-input').value = item.innerText;
          performSearch(item.innerText);
        });
      });
    }).catch(error => console.error('Error fetching suggestions:', error));
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
const performSearch = query => {
  if (query.length > 0) {
    window.location.href = `/search?search=${encodeURIComponent(query)}`;
  }
};

/***/ }),

/***/ "./resources/js/modules/viewed_lots.js":
/*!*********************************************!*\
  !*** ./resources/js/modules/viewed_lots.js ***!
  \*********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', () => {
  const lotIdMetaTag = document.querySelector('meta[name="lot-id"]');
  if (lotIdMetaTag) {
    const lotId = parseInt(lotIdMetaTag.getAttribute('content'));
    if (!isNaN(lotId)) addViewedLot(lotId);
  }
  updateViewedLots();
});
const addViewedLot = lotId => {
  const viewedLots = JSON.parse(localStorage.getItem('viewed_lots')) || [];
  if (!viewedLots.includes(lotId)) {
    viewedLots.push(lotId);
    localStorage.setItem('viewed_lots', JSON.stringify(viewedLots));
  }
};
const updateViewedLots = () => {
  const viewedLots = JSON.parse(localStorage.getItem('viewed_lots')) || [];
  document.cookie = `viewed_lots=${JSON.stringify(viewedLots)}; path=/`;
};

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _css_app_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../css/app.scss */ "./resources/css/app.scss");
/* harmony import */ var _modules_form_validation_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/form-validation.js */ "./resources/js/modules/form-validation.js");
/* harmony import */ var _modules_viewed_lots_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modules/viewed_lots.js */ "./resources/js/modules/viewed_lots.js");
/* harmony import */ var _modules_search_suggestions_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./modules/search-suggestions.js */ "./resources/js/modules/search-suggestions.js");




/******/ })()
;
//# sourceMappingURL=app.js.map