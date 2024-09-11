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
  const checkFormForErrors = () => {
    let hasErrors = false;
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
      if (!input.checkValidity()) {
        input.classList.add('form__item--invalid');
        if (input.nextElementSibling) {
          input.nextElementSibling.style.display = 'block';
        }
        hasErrors = true;
      } else {
        input.classList.remove('form__item--invalid');
        if (input.nextElementSibling) {
          input.nextElementSibling.style.display = 'none';
        }
      }
    });
    if (hasErrors) {
      form.classList.add('form--invalid');
    } else {
      form.classList.remove('form--invalid');
    }
  };
  submitButton.addEventListener('click', event => {
    checkFormForErrors();
    if (form.checkValidity()) {
      form.submit(); // Отправка формы, если нет ошибок
    }
  });

  // Отображение выбранного изображения в превью
  const fileInput = form.querySelector('input[type="file"]');
  if (fileInput) {
    fileInput.addEventListener('change', event => {
      const file = event.target.files[0];
      const reader = new FileReader();
      reader.onload = function (e) {
        if (photoPreview) {
          photoPreview.src = e.target.result;
        }
        if (previewContainer) {
          previewContainer.classList.add('form__item--uploaded');
          previewContainer.style.display = 'block';
          previewContainer.style.position = 'unset';
        }
        if (inputFileContainer) {
          inputFileContainer.classList.add('hidden');
        }
      };
      reader.readAsDataURL(file);
    });
  }

  // Проверка на наличие изображения при нажатии на кнопку "добавить лот"
  submitButton.addEventListener('click', event => {
    if (fileInput && !fileInput.value) {
      photoLabel.style.display = 'block';
    }
  });
});

/***/ }),

/***/ "./resources/js/modules/viewed_lots.js":
/*!*********************************************!*\
  !*** ./resources/js/modules/viewed_lots.js ***!
  \*********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', function () {
  const lotIdMetaTag = document.querySelector('meta[name="lot-id"]');
  if (lotIdMetaTag) {
    const lotId = parseInt(lotIdMetaTag.getAttribute('content'));
    if (!isNaN(lotId)) {
      addViewedLot(lotId);
    }
  }

  // Обновление списка просмотренных лотов
  updateViewedLots();
});
function addViewedLot(lotId) {
  let viewedLots = JSON.parse(localStorage.getItem('viewed_lots')) || [];
  if (!viewedLots.includes(lotId)) {
    viewedLots.push(lotId);
    localStorage.setItem('viewed_lots', JSON.stringify(viewedLots));
  }
}
function updateViewedLots() {
  const viewedLots = JSON.parse(localStorage.getItem('viewed_lots')) || [];
  document.cookie = `viewed_lots=${JSON.stringify(viewedLots)}; path=/`;
}

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



/******/ })()
;
//# sourceMappingURL=app.js.map