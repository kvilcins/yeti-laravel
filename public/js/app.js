/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/modules/filters.js":
/*!*****************************************!*\
  !*** ./resources/js/modules/filters.js ***!
  \*****************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
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

/***/ }),

/***/ "./resources/js/modules/fixed-scroll.js":
/*!**********************************************!*\
  !*** ./resources/js/modules/fixed-scroll.js ***!
  \**********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('header');
  let lastScroll = 0;
  window.addEventListener('scroll', () => {
    const currentScroll = window.scrollY;
    if (currentScroll > 100 && currentScroll > lastScroll) {
      header.classList.add('header--fixed');
    } else if (currentScroll < 100) {
      header.classList.remove('header--fixed');
    }
    lastScroll = currentScroll;
  });
});

/***/ }),

/***/ "./resources/js/modules/form-validation.js":
/*!*************************************************!*\
  !*** ./resources/js/modules/form-validation.js ***!
  \*************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', () => {
  class ModalNotification {
    constructor() {
      this.modal = document.getElementById('modal');
      this.icon = document.getElementById('modalIcon');
      this.message = document.getElementById('modalMessage');
      this.closeBtn = document.getElementById('modalClose');
      this.init();
      this.checkLaravelFlashMessages();
      this.initConfirmModal();
    }
    init = () => {
      this.closeBtn?.addEventListener('click', () => this.hide());
      this.modal?.addEventListener('click', e => {
        if (e.target === this.modal) this.hide();
      });
      document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && this.modal?.classList.contains('modal--show')) {
          this.hide();
        }
      });
    };
    initConfirmModal = () => {
      this.confirmModal = document.getElementById('confirmModal');
      this.confirmTitle = document.getElementById('confirmTitle');
      this.confirmMessage = document.getElementById('confirmMessage');
      this.confirmOk = document.getElementById('confirmOk');
      this.confirmCancel = document.getElementById('confirmCancel');
      if (!this.confirmModal) return;
      this.confirmCancel.addEventListener('click', () => this.hideConfirm());
      this.confirmModal.addEventListener('click', e => {
        if (e.target === this.confirmModal) this.hideConfirm();
      });
    };
    checkLaravelFlashMessages = () => {
      const successMessage = document.querySelector('meta[name="flash-success"]')?.getAttribute('content');
      const errorMessage = document.querySelector('meta[name="flash-error"]')?.getAttribute('content');
      if (successMessage) this.success(successMessage);
      if (errorMessage) this.error(errorMessage);
    };
    show = (message, type = 'success') => {
      if (!this.modal) return;
      this.message.textContent = message;
      this.icon.className = `modal__icon modal__icon--${type}`;
      this.icon.textContent = type === 'success' ? '✓' : '✗';
      this.modal.classList.add('modal--show');
      setTimeout(() => this.hide(), 5000);
    };
    confirm = (title, message, onConfirm, onCancel = null) => {
      if (!this.confirmModal) return;
      this.confirmTitle.textContent = title;
      this.confirmMessage.textContent = message;
      this.confirmModal.classList.add('modal--show');
      const handleConfirm = () => {
        this.hideConfirm();
        this.confirmOk.removeEventListener('click', handleConfirm);
        if (onConfirm) onConfirm();
      };
      const handleCancel = () => {
        this.hideConfirm();
        this.confirmOk.removeEventListener('click', handleConfirm);
        if (onCancel) onCancel();
      };
      this.confirmOk.addEventListener('click', handleConfirm);
      document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && this.confirmModal.classList.contains('modal--show')) {
          handleCancel();
        }
      }, {
        once: true
      });
    };
    hide = () => this.modal?.classList.remove('modal--show');
    hideConfirm = () => this.confirmModal?.classList.remove('modal--show');
    success = message => this.show(message, 'success');
    error = message => this.show(message, 'error');
  }
  window.modalNotification = new ModalNotification();
  const profileForm = document.querySelector('.profile__form');
  const profileNavBtns = document.querySelectorAll('.profile__nav-btn');
  const profilePanels = document.querySelectorAll('.profile__panel');
  if (profileNavBtns.length > 0) {
    profileNavBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const tabName = btn.getAttribute('data-tab');
        profileNavBtns.forEach(b => b.classList.remove('profile__nav-btn--active'));
        profilePanels.forEach(p => p.classList.remove('profile__panel--active'));
        btn.classList.add('profile__nav-btn--active');
        const targetPanel = document.getElementById(tabName + '-tab');
        if (targetPanel) {
          targetPanel.classList.add('profile__panel--active');
        }
      });
    });
  }
  const deleteButtons = document.querySelectorAll('form[onsubmit*="confirm"]');
  deleteButtons.forEach(form => {
    form.removeAttribute('onsubmit');
    form.addEventListener('submit', e => {
      e.preventDefault();
      window.modalNotification.confirm('Delete Lot', 'Are you sure you want to delete this lot? This action cannot be undone.', () => {
        form.submit();
      });
    });
  });
  if (!profileForm) return;
  const submitButton = profileForm.querySelector('[type="submit"]');
  const fields = {
    name: document.getElementById('name'),
    contactDetails: document.getElementById('message'),
    password: document.getElementById('password'),
    passwordConfirm: document.getElementById('password_confirmation'),
    currentPassword: document.getElementById('current_password'),
    avatar: document.getElementById('avatar')
  };
  const originalData = {
    name: fields.name?.value.trim() || '',
    contactDetails: fields.contactDetails?.value || ''
  };
  const showHideFields = () => {
    const hasNewPassword = fields.password?.value.length > 0;
    const currentPasswordGroup = document.getElementById('currentPasswordGroup');
    const confirmPasswordGroup = document.getElementById('password_confirmationGroup');
    if (currentPasswordGroup) {
      currentPasswordGroup.style.display = hasNewPassword ? 'block' : 'none';
    }
    if (confirmPasswordGroup) {
      confirmPasswordGroup.style.display = hasNewPassword ? 'block' : 'none';
    }
  };
  const checkForChanges = () => {
    const hasChanges = fields.name?.value.trim() !== originalData.name || fields.contactDetails?.value !== originalData.contactDetails || fields.password?.value.length > 0 || fields.avatar?.files[0] || avatarMarkedForDeletion;
    submitButton.disabled = !hasChanges;
    submitButton.classList.toggle('profile__submit-btn--disabled', !hasChanges);
    showHideFields();
  };
  const validatePasswords = () => {
    const password = fields.password?.value || '';
    const passwordConfirm = fields.passwordConfirm?.value || '';
    let hasErrors = false;
    const passwordError = document.getElementById('passwordError');
    const passwordConfirmError = document.getElementById('password_confirmationError');
    const passwordGroup = document.getElementById('passwordGroup');
    const passwordConfirmGroup = document.getElementById('password_confirmationGroup');
    if (passwordError) passwordError.textContent = '';
    if (passwordConfirmError) passwordConfirmError.textContent = '';
    if (passwordGroup) passwordGroup.classList.remove('profile__field--invalid');
    if (passwordConfirmGroup) passwordConfirmGroup.classList.remove('profile__field--invalid');
    if (password && !passwordConfirm) {
      if (passwordConfirmError) passwordConfirmError.textContent = 'Please confirm your password.';
      if (passwordConfirmGroup) passwordConfirmGroup.classList.add('profile__field--invalid');
      hasErrors = true;
    }
    if (password && passwordConfirm && password !== passwordConfirm) {
      if (passwordConfirmError) passwordConfirmError.textContent = 'The password confirmation does not match.';
      if (passwordConfirmGroup) passwordConfirmGroup.classList.add('profile__field--invalid');
      hasErrors = true;
    }
    return !hasErrors;
  };
  const getDefaultAvatarUrl = () => {
    const baseUrl = window.location.origin;
    return `${baseUrl}/img/default-avatar.jpg`;
  };
  let avatarMarkedForDeletion = false;
  const handleAvatarDelete = () => {
    const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');
    if (deleteAvatarBtn) {
      deleteAvatarBtn.removeEventListener('click', handleDeleteClick);
      deleteAvatarBtn.addEventListener('click', handleDeleteClick);
    }
    const handleDeleteClick = e => {
      e.preventDefault();
      e.stopPropagation();
      window.modalNotification.confirm('Delete Avatar', 'Are you sure you want to delete your avatar? Changes will be saved when you submit the form.', () => {
        avatarMarkedForDeletion = true;
        const avatarPreview = document.getElementById('avatarPreview');
        if (avatarPreview) {
          avatarPreview.src = getDefaultAvatarUrl();
          avatarPreview.style.opacity = '0.5';
          avatarPreview.style.filter = 'grayscale(100%)';
        }
        deleteAvatarBtn.style.display = 'none';
        const restoreBtn = createRestoreButton();
        deleteAvatarBtn.parentNode.appendChild(restoreBtn);
        if (fields.avatar) {
          fields.avatar.value = '';
        }
        checkForChanges();
      });
    };
    const createRestoreButton = () => {
      let restoreBtn = document.getElementById('restoreAvatarBtn');
      if (restoreBtn) {
        restoreBtn.remove();
      }
      restoreBtn = document.createElement('button');
      restoreBtn.type = 'button';
      restoreBtn.id = 'restoreAvatarBtn';
      restoreBtn.className = 'profile__avatar-restore';
      restoreBtn.textContent = 'Restore';
      restoreBtn.title = 'Restore avatar';
      restoreBtn.addEventListener('click', e => {
        e.preventDefault();
        restoreAvatar();
      });
      return restoreBtn;
    };
    const restoreAvatar = () => {
      avatarMarkedForDeletion = false;
      const avatarPreview = document.getElementById('avatarPreview');
      const deleteBtn = document.getElementById('deleteAvatarBtn');
      const restoreBtn = document.getElementById('restoreAvatarBtn');
      if (avatarPreview) {
        avatarPreview.src = avatarPreview.dataset.originalSrc || avatarPreview.src;
        avatarPreview.style.opacity = '1';
        avatarPreview.style.filter = 'none';
      }
      if (deleteBtn) {
        deleteBtn.style.display = 'inline-block';
      }
      if (restoreBtn) {
        restoreBtn.remove();
      }
      checkForChanges();
    };
  };
  const handleAvatarUpload = () => {
    if (fields.avatar) {
      const avatarPreview = document.getElementById('avatarPreview');
      if (avatarPreview && !avatarPreview.dataset.originalSrc) {
        avatarPreview.dataset.originalSrc = avatarPreview.src;
      }
      fields.avatar.addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) {
          if (avatarMarkedForDeletion) {
            const avatarPreview = document.getElementById('avatarPreview');
            if (avatarPreview) {
              avatarPreview.src = getDefaultAvatarUrl();
              avatarPreview.classList.add('profile__avatar-img--deleted');
            }
          } else {
            restoreOriginalAvatar();
          }
          return;
        }
        if (file.size > 5 * 1024 * 1024) {
          window.modalNotification.error('File size must be less than 5MB');
          e.target.value = '';
          return;
        }
        if (!file.type.startsWith('image/')) {
          window.modalNotification.error('Please select a valid image file');
          e.target.value = '';
          return;
        }
        avatarMarkedForDeletion = false;
        const reader = new FileReader();
        reader.onload = event => {
          const avatarPreview = document.getElementById('avatarPreview');
          const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');
          const restoreBtn = document.getElementById('restoreAvatarBtn');
          if (avatarPreview) {
            avatarPreview.src = event.target.result;
            avatarPreview.classList.remove('profile__avatar-img--deleted');
          }
          if (deleteAvatarBtn) {
            deleteAvatarBtn.style.display = 'inline-block';
          }
          if (restoreBtn) {
            restoreBtn.remove();
          }
        };
        reader.readAsDataURL(file);
        checkForChanges();
      });
    }
    const restoreOriginalAvatar = () => {
      const avatarPreview = document.getElementById('avatarPreview');
      const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');
      const restoreBtn = document.getElementById('restoreAvatarBtn');
      if (avatarPreview && avatarPreview.dataset.originalSrc) {
        avatarPreview.src = avatarPreview.dataset.originalSrc;
        avatarPreview.style.opacity = '1';
        avatarPreview.style.filter = 'none';
      }
      if (deleteAvatarBtn) {
        deleteAvatarBtn.style.display = 'inline-block';
      }
      if (restoreBtn) {
        restoreBtn.remove();
      }
      avatarMarkedForDeletion = false;
      checkForChanges();
    };
  };
  Object.values(fields).forEach(field => {
    if (field && field !== fields.avatar) {
      field.addEventListener('input', checkForChanges);
      field.addEventListener('change', checkForChanges);
    }
  });
  fields.password?.addEventListener('blur', validatePasswords);
  fields.passwordConfirm?.addEventListener('blur', validatePasswords);
  fields.passwordConfirm?.addEventListener('input', validatePasswords);
  checkForChanges();
  handleAvatarUpload();
  handleAvatarDelete();
  profileForm.addEventListener('submit', e => {
    e.preventDefault();
    if (submitButton.disabled) return;
    if (!validatePasswords()) return;
    document.querySelectorAll('.profile__error').forEach(error => error.textContent = '');
    document.querySelectorAll('.profile__field--invalid').forEach(item => item.classList.remove('profile__field--invalid'));
    const formData = new FormData(profileForm);
    if (avatarMarkedForDeletion) {
      formData.append('delete_avatar', '1');
    }
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Updating...';
    fetch(profileForm.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken || ''
      }
    }).then(response => {
      if (!response.ok) {
        if (response.status === 422) {
          return response.json().then(data => {
            Object.entries(data.errors).forEach(([field, messages]) => {
              const errorElement = document.getElementById(field + 'Error');
              const groupElement = document.getElementById(field + 'Group');
              if (errorElement) errorElement.textContent = messages[0];
              if (groupElement) groupElement.classList.add('profile__field--invalid');
            });
            throw new Error('Validation failed');
          });
        }
        throw new Error('Network error');
      }
      return response.json();
    }).then(data => {
      if (data.success) {
        window.modalNotification.success(data.message);
        if (data.avatar_url) {
          const avatarPreview = document.getElementById('avatarPreview');
          const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');
          const restoreBtn = document.getElementById('restoreAvatarBtn');
          if (avatarPreview) {
            avatarPreview.src = data.avatar_url;
            avatarPreview.dataset.originalSrc = data.avatar_url;
            avatarPreview.classList.remove('profile__avatar-img--deleted');
          }
          if (deleteAvatarBtn) {
            deleteAvatarBtn.style.display = data.avatar_url.includes('default-avatar') ? 'none' : 'inline-block';
          }
          if (restoreBtn) {
            restoreBtn.remove();
          }
        }
        if (fields.password) fields.password.value = '';
        if (fields.passwordConfirm) fields.passwordConfirm.value = '';
        if (fields.currentPassword) fields.currentPassword.value = '';
        if (fields.avatar) fields.avatar.value = '';
        avatarMarkedForDeletion = false;
        originalData.name = fields.name?.value.trim() || '';
        originalData.contactDetails = fields.contactDetails?.value || '';
        checkForChanges();
      } else {
        window.modalNotification.error(data.message || 'An error occurred');
      }
    }).catch(error => {
      console.error('Error:', error);
      if (error.message !== 'Validation failed') {
        window.modalNotification.error('An error occurred while updating profile');
      }
    }).finally(() => {
      submitButton.disabled = false;
      submitButton.textContent = originalText;
    });
  });
});

/***/ }),

/***/ "./resources/js/modules/image-preview.js":
/*!***********************************************!*\
  !*** ./resources/js/modules/image-preview.js ***!
  \***********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', () => {
  class ImageUploadHandler {
    constructor(inputSelector) {
      this.input = document.querySelector(inputSelector);
      if (!this.input) return;
      const formItem = this.input.closest('.form__item, .profile__field');
      if (!formItem) return;
      this.preview = formItem.querySelector('.form__preview, .profile__avatar');
      this.previewImg = this.preview?.querySelector('.form__preview-img, .profile__avatar-img');
      this.deleteBtn = this.preview?.querySelector('.form__delete-btn, .profile__avatar-delete');
      this.isProfileMode = this.input.closest('.profile__form');
      this.isLotEditMode = this.input.closest('.form--add-lot');
      this.defaultAvatarUrl = '/img/default-avatar.jpg';
      this.init();
    }
    init() {
      if (!this.input || !this.preview || !this.previewImg) return;
      this.input.addEventListener('change', this.handleFileSelect.bind(this));
      if (this.deleteBtn) {
        this.deleteBtn.addEventListener('click', this.handleDelete.bind(this));
        if (this.isLotEditMode && this.preview?.classList.contains('form__preview--visible')) {
          this.deleteBtn.style.display = 'flex';
        }
      }
    }
    handleFileSelect(e) {
      const file = e.target.files[0];
      if (!file) {
        this.clearPreview();
        return;
      }
      if (!this.validateFile(file)) {
        e.target.value = '';
        return;
      }
      this.displayPreview(file);
      if (this.isLotEditMode) {
        const deleteImageInput = document.getElementById('delete_image');
        if (deleteImageInput) {
          deleteImageInput.value = '0';
        }
      }
    }
    validateFile(file) {
      const maxSize = this.isProfileMode ? 5 * 1024 * 1024 : 2 * 1024 * 1024;
      if (file.size > maxSize) {
        const sizeMB = maxSize / (1024 * 1024);
        this.showError(`File size must be less than ${sizeMB}MB`);
        return false;
      }
      if (!file.type.startsWith('image/')) {
        this.showError('Please select a valid image file');
        return false;
      }
      return true;
    }
    displayPreview(file) {
      const reader = new FileReader();
      reader.onload = event => {
        if (this.previewImg) {
          this.previewImg.src = event.target.result;
        }
        if (this.isProfileMode) {
          this.preview?.classList.remove('profile__avatar--hidden');
          this.deleteBtn?.classList.remove('profile__avatar-delete--hidden');
        } else {
          this.preview?.classList.add('form__preview--visible');
          this.createOrShowDeleteBtn();
        }
      };
      reader.readAsDataURL(file);
    }
    createOrShowDeleteBtn() {
      if (!this.deleteBtn) {
        this.deleteBtn = document.createElement('button');
        this.deleteBtn.type = 'button';
        this.deleteBtn.className = 'form__delete-btn';
        this.deleteBtn.innerHTML = '×';
        this.deleteBtn.title = 'Delete image';
        this.deleteBtn.addEventListener('click', this.handleDelete.bind(this));
        this.preview.style.position = 'relative';
        this.preview.appendChild(this.deleteBtn);
      }
      if (this.deleteBtn) {
        this.deleteBtn.style.display = 'flex';
      }
    }
    handleDelete(e) {
      e.preventDefault();
      if (this.isProfileMode) {
        this.handleProfileDelete();
      } else if (this.isLotEditMode) {
        this.handleLotEditDelete();
      } else {
        this.handleFormDelete();
      }
    }
    handleProfileDelete() {
      if (window.modalNotification) {
        window.modalNotification.confirm('Delete Avatar', 'Are you sure you want to delete your avatar?', () => {
          this.setDefaultAvatar();
          this.input.value = '';
        });
      } else {
        if (confirm('Are you sure you want to delete your avatar?')) {
          this.setDefaultAvatar();
          this.input.value = '';
        }
      }
    }
    handleLotEditDelete() {
      const confirmMessage = 'Are you sure you want to delete this image?';
      if (window.modalNotification) {
        window.modalNotification.confirm('Delete Image', confirmMessage, () => {
          this.removeLotImage();
        });
      } else {
        if (confirm(confirmMessage)) {
          this.removeLotImage();
        }
      }
    }
    removeLotImage() {
      const deleteImageInput = document.getElementById('delete_image');
      if (deleteImageInput) {
        deleteImageInput.value = '1';
      }
      this.clearPreview();
      this.input.value = '';
    }
    handleFormDelete() {
      this.clearPreview();
      this.input.value = '';
    }
    setDefaultAvatar() {
      if (this.previewImg) {
        this.previewImg.src = this.defaultAvatarUrl;
      }
      this.deleteBtn?.classList.add('profile__avatar-delete--hidden');
    }
    clearPreview() {
      if (this.isProfileMode) {
        this.preview?.classList.add('profile__avatar--hidden');
        this.deleteBtn?.classList.add('profile__avatar-delete--hidden');
        if (this.previewImg) {
          this.previewImg.src = '';
        }
      } else {
        this.preview?.classList.remove('form__preview--visible');
        if (this.deleteBtn) {
          this.deleteBtn.style.display = 'none';
        }
        if (this.previewImg) {
          this.previewImg.src = '';
        }
      }
    }
    showError(message) {
      if (window.modalNotification) {
        window.modalNotification.error(message);
      } else {
        alert(message);
      }
    }
  }
  const avatarInput = document.querySelector('#avatar');
  const lotInput = document.querySelector('#lot_img');
  if (avatarInput) {
    new ImageUploadHandler('#avatar');
  }
  if (lotInput) {
    new ImageUploadHandler('#lot_img');
  }
});

/***/ }),

/***/ "./resources/js/modules/menu-dropdown.js":
/*!***********************************************!*\
  !*** ./resources/js/modules/menu-dropdown.js ***!
  \***********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', () => {
  const userImage = document.querySelector('.user-menu__avatar');
  const dropdown = document.querySelector('.user-menu__dropdown');
  userImage.addEventListener('click', e => {
    dropdown.classList.toggle('hidden');
  });
  document.addEventListener('click', e => {
    if (!userImage.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add('hidden');
    }
  });
});

/***/ }),

/***/ "./resources/js/modules/mobile-menu.js":
/*!*********************************************!*\
  !*** ./resources/js/modules/mobile-menu.js ***!
  \*********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', () => {
  const burger = document.getElementById('burger-toggle');
  const mobileMenu = document.getElementById('mobile-menu');
  const closeBtn = document.getElementById('mobile-menu-close');
  const openMenu = () => {
    mobileMenu.classList.remove('hidden');
    setTimeout(() => mobileMenu.classList.add('open'), 10);
    burger.classList.remove('burger-behind');
  };
  const closeMenu = () => {
    mobileMenu.classList.remove('open');
    burger.classList.add('burger-behind');
    setTimeout(() => {
      mobileMenu.classList.add('hidden');
      burger.classList.remove('burger-behind');
    }, 300);
  };
  burger?.addEventListener('click', openMenu);
  closeBtn?.addEventListener('click', closeMenu);
  document.addEventListener('click', e => {
    if (mobileMenu.classList.contains('open') && !mobileMenu.contains(e.target) && !burger.contains(e.target)) {
      closeMenu();
    }
  });
});

/***/ }),

/***/ "./resources/js/modules/modals.js":
/*!****************************************!*\
  !*** ./resources/js/modules/modals.js ***!
  \****************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', () => {
  class ModalNotification {
    constructor() {
      this.modal = document.getElementById('modal');
      this.icon = document.getElementById('modalIcon');
      this.message = document.getElementById('modalMessage');
      this.closeBtn = document.getElementById('modalClose');
      this.init();
      this.checkLaravelFlashMessages();
    }
    init = () => {
      this.closeBtn?.addEventListener('click', () => this.hide());
      this.modal?.addEventListener('click', e => {
        if (e.target === this.modal) {
          this.hide();
        }
      });
      document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && this.modal?.classList.contains('show')) {
          this.hide();
        }
      });
    };
    checkLaravelFlashMessages = () => {
      const successMessage = document.querySelector('meta[name="flash-success"]')?.getAttribute('content');
      const errorMessage = document.querySelector('meta[name="flash-error"]')?.getAttribute('content');
      if (successMessage) {
        this.success(successMessage);
      }
      if (errorMessage) {
        this.error(errorMessage);
      }
    };
    show = (message, type = 'success') => {
      if (!this.modal) return;
      this.message.textContent = message;
      this.icon.className = `modal__icon ${type}`;
      this.icon.textContent = type === 'success' ? '✓' : '✗';
      this.modal.classList.add('show');
      setTimeout(() => {
        this.hide();
      }, 5000);
    };
    hide = () => {
      if (this.modal) {
        this.modal.classList.remove('show');
      }
    };
    success = message => {
      this.show(message, 'success');
    };
    error = message => {
      this.show(message, 'error');
    };
  }
  window.modalNotification = new ModalNotification();
  const handleAvatarPreview = () => {
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatarPreview');
    if (avatarInput && avatarPreview) {
      avatarInput.addEventListener('change', e => {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = e => {
            avatarPreview.src = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      });
    }
  };
  handleAvatarPreview();
});

/***/ }),

/***/ "./resources/js/modules/scroll-to-top.js":
/*!***********************************************!*\
  !*** ./resources/js/modules/scroll-to-top.js ***!
  \***********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', () => {
  const scrollBtn = document.getElementById('scroll-to-top');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
      scrollBtn.classList.add('visible');
    } else {
      scrollBtn.classList.remove('visible');
    }
  });
  scrollBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
});

/***/ }),

/***/ "./resources/js/modules/search-suggestions.js":
/*!****************************************************!*\
  !*** ./resources/js/modules/search-suggestions.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
const initSearchSuggestions = () => {
  const searchInput = document.getElementById('search-input');
  const suggestionsContainer = document.getElementById('search-suggestions');
  const searchButton = document.getElementById('search-button');
  if (!searchInput || !suggestionsContainer || !searchButton) return;
  const performSearch = query => {
    if (query.length > 0) {
      window.location.href = `/search?search=${encodeURIComponent(query)}`;
    }
  };
  const hideSuggestions = () => {
    suggestionsContainer.innerHTML = '';
    suggestionsContainer.classList.remove('active');
  };
  searchInput.addEventListener('input', event => {
    const query = event.target.value.trim();
    if (query.length > 2) {
      fetch(`/search-suggestions?query=${encodeURIComponent(query)}`).then(response => response.json()).then(data => {
        if (data.length > 0) {
          const suggestions = data.map(item => `<li class="suggestion-item">${item.title}</li>`).join('');
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
      }).catch(error => {
        console.error('Error fetching suggestions:', error);
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
  document.addEventListener('click', event => {
    if (!searchInput.contains(event.target) && !suggestionsContainer.contains(event.target)) {
      hideSuggestions();
    }
  });
};
initSearchSuggestions();

/***/ }),

/***/ "./resources/js/modules/tabs.js":
/*!**************************************!*\
  !*** ./resources/js/modules/tabs.js ***!
  \**************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
document.addEventListener('DOMContentLoaded', function () {
  const tabButtons = document.querySelectorAll('.profile__nav-btn');
  const tabPanels = document.querySelectorAll('.profile__panel');
  tabButtons.forEach(button => {
    button.addEventListener('click', function () {
      const targetTab = this.getAttribute('data-tab');
      tabButtons.forEach(btn => btn.classList.remove('profile__nav-btn--active'));
      tabPanels.forEach(panel => panel.classList.remove('profile__panel--active'));
      this.classList.add('profile__nav-btn--active');
      const targetPanel = document.getElementById(targetTab + '-tab');
      if (targetPanel) {
        targetPanel.classList.add('profile__panel--active');
      }
    });
  });
  const passwordInput = document.getElementById('password');
  const currentPasswordGroup = document.getElementById('currentPasswordGroup');
  const confirmPasswordGroup = document.getElementById('password_confirmationGroup');
  if (passwordInput && currentPasswordGroup && confirmPasswordGroup) {
    passwordInput.addEventListener('input', function () {
      if (this.value.length > 0) {
        currentPasswordGroup.classList.remove('profile__field--hidden');
        confirmPasswordGroup.classList.remove('profile__field--hidden');
      } else {
        currentPasswordGroup.classList.add('profile__field--hidden');
        confirmPasswordGroup.classList.add('profile__field--hidden');
        document.getElementById('current_password').value = '';
        document.getElementById('password_confirmation').value = '';
      }
    });
  }
});

/***/ }),

/***/ "./resources/js/modules/viewed-lots.js":
/*!*********************************************!*\
  !*** ./resources/js/modules/viewed-lots.js ***!
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
/* harmony import */ var _modules_viewed_lots_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/viewed-lots.js */ "./resources/js/modules/viewed-lots.js");
/* harmony import */ var _modules_search_suggestions_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/search-suggestions.js */ "./resources/js/modules/search-suggestions.js");
/* harmony import */ var _modules_menu_dropdown_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modules/menu-dropdown.js */ "./resources/js/modules/menu-dropdown.js");
/* harmony import */ var _modules_mobile_menu_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./modules/mobile-menu.js */ "./resources/js/modules/mobile-menu.js");
/* harmony import */ var _modules_fixed_scroll_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./modules/fixed-scroll.js */ "./resources/js/modules/fixed-scroll.js");
/* harmony import */ var _modules_scroll_to_top_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./modules/scroll-to-top.js */ "./resources/js/modules/scroll-to-top.js");
/* harmony import */ var _modules_modals_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./modules/modals.js */ "./resources/js/modules/modals.js");
/* harmony import */ var _modules_tabs_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./modules/tabs.js */ "./resources/js/modules/tabs.js");
/* harmony import */ var _modules_image_preview_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./modules/image-preview.js */ "./resources/js/modules/image-preview.js");
/* harmony import */ var _modules_form_validation_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./modules/form-validation.js */ "./resources/js/modules/form-validation.js");
/* harmony import */ var _modules_filters_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./modules/filters.js */ "./resources/js/modules/filters.js");











/******/ })()
;
//# sourceMappingURL=app.js.map