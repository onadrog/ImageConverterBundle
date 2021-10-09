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

/***/ "./assets/js/image_converter.ts":
/*!**************************************!*\
  !*** ./assets/js/image_converter.ts ***!
  \**************************************/
/***/ (function() {

eval("const input = (document.querySelector(\"input[data-name='image_converter']\")), altInput = document.querySelector(\"input[data-name='alt']\"), reader = new FileReader(), formData = new FormData(), canvas = document.createElement(\"canvas\"), ctx = canvas.getContext(\"2d\"), image = new Image(), newcanvas = document.createElement(\"canvas\"), newctx = newcanvas.getContext(\"2d\"), newImg = new Image(), newinput = document.createElement(\"input\");\ninput.addEventListener(\"change\", (e) => {\n    const form = document.querySelector(\"form\");\n    const innerForm = document.getElementById(form.name);\n    const target = e.target;\n    const file = target.files[0];\n    const fileName = file.name;\n    const quality = parseInt(input.dataset.qual, 10) / 100;\n    image.height = 200;\n    reader.onload = (e) => {\n        image.src = e.target.result;\n    };\n    image.onload = () => {\n        canvas.width = image.width;\n        canvas.height = image.height;\n    };\n    reader.readAsDataURL(file);\n    target.parentElement.append(image);\n    const url = URL.createObjectURL(file);\n    newImg.src = url;\n    newImg.onload = () => {\n        URL.revokeObjectURL(url);\n        newcanvas.width = newImg.width;\n        newcanvas.height = newImg.height;\n        newctx.drawImage(newImg, 0, 0);\n        newinput.type = \"file\";\n        newinput.name = `${form.name}[${input.dataset.prop}][original_file]`;\n        newinput.style.display = \"none\";\n        let container2 = new DataTransfer();\n        container2.items.add(file);\n        newinput.files = container2.files;\n        innerForm.append(newinput);\n        newcanvas.toBlob((blob) => {\n            const newfile = new File([blob], fileName, {\n                type: \"image/webp\",\n                lastModified: new Date().getTime(),\n            });\n            let container = new DataTransfer();\n            container.items.add(newfile);\n            input.files = container.files;\n        }, \"image/webp\", quality);\n    };\n});\nconst convertfile = (fileName, quality, input) => {\n    const dataURI = newcanvas.toDataURL(\"image/webp\", quality);\n    const blob = new Blob([dataURI]);\n    const newfile = new File([blob], fileName, {\n        type: \"image/webp\",\n        lastModified: new Date().getTime(),\n    });\n    let container = new DataTransfer();\n    container.items.add(newfile);\n    input.files = container.files;\n};\n\n\n//# sourceURL=webpack:///./assets/js/image_converter.ts?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./assets/js/image_converter.ts"]();
/******/ 	
/******/ })()
;