/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/modal.ts":
/*!****************************!*\
  !*** ./assets/js/modal.ts ***!
  \****************************/
/***/ (function() {

eval("class ImageConverterModal extends HTMLElement {\n    constructor() {\n        super();\n        this.closeEvt = () => {\n            [this.closeBtn, this.saveBtn].forEach((btn) => {\n                btn.addEventListener(\"click\", () => {\n                    this.modal.style.display = \"none\";\n                    this.openModalInput.classList.remove(\"imc-open\");\n                    this.removeAttribute(\"class\");\n                });\n            });\n        };\n        this.cancelEvt = () => {\n            this.cancelBtn.addEventListener(\"click\", () => {\n                this.querySelectorAll(\"input\").forEach((inputs) => {\n                    if (inputs.type === \"text\" || inputs.type === \"file\") {\n                        inputs.value = \"\";\n                    }\n                    else {\n                        inputs.checked = false;\n                    }\n                });\n                this.altInput.removeAttribute(\"disabled\");\n                this.entityInput.removeAttribute(\"value\");\n            });\n        };\n        this.radioEvnt = () => {\n            this.radioInputs.forEach((radio) => {\n                radio.addEventListener(\"change\", (e) => {\n                    const target = e.target;\n                    this.altInput.disabled = true;\n                    this.altInput.value = target.dataset.alt;\n                    this.entityInput.value = target.value;\n                });\n            });\n        };\n        this.openModalInput = this.querySelector(\"#imc-openModal\");\n        this.modal = this.querySelector(\"#imc-modal-fields\");\n        this.closeBtn = this.querySelector(\".imc-close\");\n        this.saveBtn = this.querySelector(\".imc-modal-save\");\n        this.cancelBtn = this.querySelector(\".imc-modal-cancel\");\n        this.radioInputs = this.querySelectorAll(\"input[type='radio']\");\n        this.altInput = this.querySelector(\"input[data-name='alt']\");\n        this.entityInput = this.querySelector(\".entity_value\");\n        this.init();\n    }\n    init() {\n        this.openModalInput.addEventListener(\"click\", () => {\n            this.modal.removeAttribute(\"style\");\n            this.className += \"imc-open\";\n            this.openModalInput.className += \"imc-open\";\n            this.closeEvt();\n            this.cancelEvt();\n            this.radioEvnt();\n        });\n    }\n}\ncustomElements.define(\"image-converter-modal\", ImageConverterModal);\n\n\n//# sourceURL=webpack:///./assets/js/modal.ts?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./assets/js/modal.ts"]();
/******/ 	
/******/ })()
;