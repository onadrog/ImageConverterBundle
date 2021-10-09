/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/css/modal.scss":
/*!*******************************!*\
  !*** ./assets/css/modal.scss ***!
  \*******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack:///./assets/css/modal.scss?");

/***/ }),

/***/ "./assets/js/modal.ts":
/*!****************************!*\
  !*** ./assets/js/modal.ts ***!
  \****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _css_modal_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../css/modal.scss */ \"./assets/css/modal.scss\");\n\nclass ImageConverterModal extends HTMLElement {\n    constructor() {\n        super();\n        this.closeEvt = () => {\n            [this.closeBtn, this.saveBtn].forEach((btn) => {\n                btn.addEventListener(\"click\", () => {\n                    this.modal.style.display = \"none\";\n                    this.openModalInput.classList.remove(\"imc-open\");\n                    this.removeAttribute(\"class\");\n                });\n            });\n        };\n        this.cancelEvt = () => {\n            this.cancelBtn.addEventListener(\"click\", () => {\n                this.querySelectorAll(\"input\").forEach((inputs) => {\n                    if (inputs.type === \"text\" || inputs.type === \"file\") {\n                        inputs.value = \"\";\n                    }\n                    else {\n                        inputs.checked = false;\n                    }\n                });\n                this.altInput.removeAttribute(\"disabled\");\n                this.entityInput.removeAttribute(\"value\");\n                this.label.removeAttribute(\"style\");\n            });\n        };\n        this.radioEvt = () => {\n            this.radioInputs.forEach((radio) => {\n                radio.addEventListener(\"change\", (e) => {\n                    const target = e.target;\n                    this.altInput.disabled = true;\n                    this.altInput.value = target.dataset.alt;\n                    this.entityInput.value = target.value;\n                });\n            });\n        };\n        this.fileEvt = () => {\n            const image = new Image();\n            this.fileInput.addEventListener(\"change\", (e) => {\n                const file = e.target.files[0];\n                const styleLabel = this.label.style;\n                const url = URL.createObjectURL(file);\n                image.src = url;\n                image.onload = (e) => {\n                    URL.revokeObjectURL(e.target.src);\n                };\n                styleLabel.backgroundImage = \"url(\" + url + \")\";\n                styleLabel.backgroundPosition = \"center\";\n                styleLabel.backgroundSize = \"cover\";\n                styleLabel.backgroundRepeat = \"no-repeat\";\n                styleLabel.border = \"none\";\n            });\n        };\n        this.openModalInput = this.querySelector(\"#imc-openModal\");\n        this.modal = this.querySelector(\"#imc-modal-fields\");\n        this.closeBtn = this.querySelector(\".imc-close\");\n        this.saveBtn = this.querySelector(\".imc-modal-save\");\n        this.cancelBtn = this.querySelector(\".imc-modal-cancel\");\n        this.radioInputs = this.querySelectorAll(\"input[type='radio']\");\n        this.altInput = this.querySelector(\"input[data-name='alt']\");\n        this.entityInput = this.querySelector(\".entity_value\");\n        this.label = this.querySelector(\".imc-label\");\n        this.fileInput = this.querySelector(\"input[data-name='image_converter']\");\n        this.init();\n    }\n    init() {\n        this.openModalInput.addEventListener(\"click\", () => {\n            this.modal.removeAttribute(\"style\");\n            this.className += \"imc-open\";\n            this.openModalInput.className += \"imc-open\";\n            this.closeEvt();\n            this.cancelEvt();\n            this.radioEvt();\n            this.fileEvt();\n        });\n    }\n}\ncustomElements.define(\"image-converter-modal\", ImageConverterModal);\n\n\n//# sourceURL=webpack:///./assets/js/modal.ts?");

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
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	__webpack_require__("./assets/js/modal.ts");
/******/ 	var __webpack_exports__ = __webpack_require__("./assets/css/modal.scss");
/******/ 	
/******/ })()
;