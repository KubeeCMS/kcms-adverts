/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/dev/css/_ning_frontend_manager.css":
/*!***************************************************!*\
  !*** ./assets/dev/css/_ning_frontend_manager.css ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/dev/css/_ning_frontend_manager.css?");

/***/ }),

/***/ "./assets/dev/js/_ning_frontend_manager.js":
/*!*************************************************!*\
  !*** ./assets/dev/js/_ning_frontend_manager.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("\n\n//# sourceURL=webpack:///./assets/dev/js/_ning_frontend_manager.js?");

/***/ }),

/***/ "./assets/dev/js/index_frontend_manager.js":
/*!*************************************************!*\
  !*** ./assets/dev/js/index_frontend_manager.js ***!
  \*************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _css_ning_frontend_manager_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../css/_ning_frontend_manager.css */ \"./assets/dev/css/_ning_frontend_manager.css\");\n/* harmony import */ var _css_ning_frontend_manager_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_ning_frontend_manager_css__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _sticky_kit_min_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./sticky-kit.min.js */ \"./assets/dev/js/sticky-kit.min.js\");\n/* harmony import */ var _sticky_kit_min_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_sticky_kit_min_js__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _ning_frontend_manager_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./_ning_frontend_manager.js */ \"./assets/dev/js/_ning_frontend_manager.js\");\n/* harmony import */ var _ning_frontend_manager_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_ning_frontend_manager_js__WEBPACK_IMPORTED_MODULE_2__);\n\n\n\n\n//# sourceURL=webpack:///./assets/dev/js/index_frontend_manager.js?");

/***/ }),

/***/ "./assets/dev/js/sticky-kit.min.js":
/*!*****************************************!*\
  !*** ./assets/dev/js/sticky-kit.min.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/*\n Sticky-kit v1.1.3 | MIT | Leaf Corcoran 2015 | http://leafo.net\n*/\n(function () {\n  var c, f;\n  c = window.jQuery;\n  f = c(window);\n\n  c.fn.stick_in_parent = function (b) {\n    var A, w, J, n, B, K, p, q, L, k, E, t;\n    null == b && (b = {});\n    t = b.sticky_class;\n    B = b.inner_scrolling;\n    E = b.recalc_every;\n    k = b.parent;\n    q = b.offset_top;\n    p = b.spacer;\n    w = b.bottoming;\n    null == q && (q = 0);\n    null == k && (k = void 0);\n    null == B && (B = !0);\n    null == t && (t = \"is_stuck\");\n    A = c(document);\n    null == w && (w = !0);\n\n    L = function L(a) {\n      var b;\n      return window.getComputedStyle ? (a = window.getComputedStyle(a[0]), b = parseFloat(a.getPropertyValue(\"width\")) + parseFloat(a.getPropertyValue(\"margin-left\")) + parseFloat(a.getPropertyValue(\"margin-right\")), \"border-box\" !== a.getPropertyValue(\"box-sizing\") && (b += parseFloat(a.getPropertyValue(\"border-left-width\")) + parseFloat(a.getPropertyValue(\"border-right-width\")) + parseFloat(a.getPropertyValue(\"padding-left\")) + parseFloat(a.getPropertyValue(\"padding-right\"))), b) : a.outerWidth(!0);\n    };\n\n    J = function J(a, b, n, C, F, u, r, G) {\n      var v, _H, m, D, I, d, g, x, y, z, h, l;\n\n      if (!a.data(\"sticky_kit\")) {\n        a.data(\"sticky_kit\", !0);\n        I = A.height();\n        g = a.parent();\n        null != k && (g = g.closest(k));\n        if (!g.length) throw \"failed to find stick parent\";\n        v = m = !1;\n        (h = null != p ? p && a.closest(p) : c(\"<div />\")) && h.css(\"position\", a.css(\"position\"));\n\n        x = function x() {\n          var d, f, e;\n          if (!G && (I = A.height(), d = parseInt(g.css(\"border-top-width\"), 10), f = parseInt(g.css(\"padding-top\"), 10), b = parseInt(g.css(\"padding-bottom\"), 10), n = g.offset().top + d + f, C = g.height(), m && (v = m = !1, null == p && (a.insertAfter(h), h.detach()), a.css({\n            position: \"\",\n            top: \"\",\n            width: \"\",\n            bottom: \"\"\n          }).removeClass(t), e = !0), F = a.offset().top - (parseInt(a.css(\"margin-top\"), 10) || 0) - q, u = a.outerHeight(!0), r = a.css(\"float\"), h && h.css({\n            width: L(a),\n            height: u,\n            display: a.css(\"display\"),\n            \"vertical-align\": a.css(\"vertical-align\"),\n            \"float\": r\n          }), e)) return l();\n        };\n\n        x();\n        if (u !== C) return D = void 0, d = q, z = E, l = function l() {\n          var c, l, e, k;\n          if (!G && (e = !1, null != z && (--z, 0 >= z && (z = E, x(), e = !0)), e || A.height() === I || x(), e = f.scrollTop(), null != D && (l = e - D), D = e, m ? (w && (k = e + u + d > C + n, v && !k && (v = !1, a.css({\n            position: \"fixed\",\n            bottom: \"\",\n            top: d\n          }).trigger(\"sticky_kit:unbottom\"))), e < F && (m = !1, d = q, null == p && (\"left\" !== r && \"right\" !== r || a.insertAfter(h), h.detach()), c = {\n            position: \"\",\n            width: \"\",\n            top: \"\"\n          }, a.css(c).removeClass(t).trigger(\"sticky_kit:unstick\")), B && (c = f.height(), u + q > c && !v && (d -= l, d = Math.max(c - u, d), d = Math.min(q, d), m && a.css({\n            top: d + \"px\"\n          })))) : e > F && (m = !0, c = {\n            position: \"fixed\",\n            top: d\n          }, c.width = \"border-box\" === a.css(\"box-sizing\") ? a.outerWidth() + \"px\" : a.width() + \"px\", a.css(c).addClass(t), null == p && (a.after(h), \"left\" !== r && \"right\" !== r || h.append(a)), a.trigger(\"sticky_kit:stick\")), m && w && (null == k && (k = e + u + d > C + n), !v && k))) return v = !0, \"static\" === g.css(\"position\") && g.css({\n            position: \"relative\"\n          }), a.css({\n            position: \"absolute\",\n            bottom: b,\n            top: \"auto\"\n          }).trigger(\"sticky_kit:bottom\");\n        }, y = function y() {\n          x();\n          return l();\n        }, _H = function H() {\n          G = !0;\n          f.off(\"touchmove\", l);\n          f.off(\"scroll\", l);\n          f.off(\"resize\", y);\n          c(document.body).off(\"sticky_kit:recalc\", y);\n          a.off(\"sticky_kit:detach\", _H);\n          a.removeData(\"sticky_kit\");\n          a.css({\n            position: \"\",\n            bottom: \"\",\n            top: \"\",\n            width: \"\"\n          });\n          g.position(\"position\", \"\");\n          if (m) return null == p && (\"left\" !== r && \"right\" !== r || a.insertAfter(h), h.remove()), a.removeClass(t);\n        }, f.on(\"touchmove\", l), f.on(\"scroll\", l), f.on(\"resize\", y), c(document.body).on(\"sticky_kit:recalc\", y), a.on(\"sticky_kit:detach\", _H), setTimeout(l, 0);\n      }\n    };\n\n    n = 0;\n\n    for (K = this.length; n < K; n++) {\n      b = this[n], J(c(b));\n    }\n\n    return this;\n  };\n}).call(this);\n\n//# sourceURL=webpack:///./assets/dev/js/sticky-kit.min.js?");

/***/ }),

/***/ 2:
/*!*******************************************************!*\
  !*** multi ./assets/dev/js/index_frontend_manager.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! ./assets/dev/js/index_frontend_manager.js */\"./assets/dev/js/index_frontend_manager.js\");\n\n\n//# sourceURL=webpack:///multi_./assets/dev/js/index_frontend_manager.js?");

/***/ })

/******/ });