"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
var injectedHTML =

// Dark overlay
"<div lib=\"sweet-overlay\" tabIndex=\"-1\"></div>" +

// Modal
"<div lib=\"sweet-alert\">" +

// Error icon
"<div lib=\"sa-icon sa-error\">\n      <span lib=\"sa-x-mark\">\n        <span lib=\"sa-line sa-left\"></span>\n        <span lib=\"sa-line sa-right\"></span>\n      </span>\n    </div>" +

// Warning icon
"<div lib=\"sa-icon sa-warning\">\n      <span lib=\"sa-body\"></span>\n      <span lib=\"sa-dot\"></span>\n    </div>" +

// Info icon
"<div lib=\"sa-icon sa-info\"></div>" +

// Success icon
"<div lib=\"sa-icon sa-success\">\n      <span lib=\"sa-line sa-tip\"></span>\n      <span lib=\"sa-line sa-long\"></span>\n\n      <div lib=\"sa-placeholder\"></div>\n      <div lib=\"sa-fix\"></div>\n    </div>" + "<div lib=\"sa-icon sa-custom\"></div>" +

// Title, text and input
"<h2>Title</h2>\n    <p>Text</p>\n    <fieldset>\n      <input type=\"text\" tabIndex=\"3\" />\n      <div lib=\"sa-input-error\"></div>\n    </fieldset>" +

// Input errors
"<div lib=\"sa-error-container\">\n      <div lib=\"icon\">!</div>\n      <p>Not valid!</p>\n    </div>" +

// Cancel and confirm buttons
"<div lib=\"sa-button-container\">\n      <button lib=\"cancel\" tabIndex=\"2\">Cancel</button>\n      <div lib=\"sa-confirm-button-container\">\n        <button lib=\"confirm\" tabIndex=\"1\">OK</button>" +

// Loading animation
"<div lib=\"la-ball-fall\">\n          <div></div>\n          <div></div>\n          <div></div>\n        </div>\n      </div>\n    </div>" +

// End of modal
"</div>";

exports["default"] = injectedHTML;
module.exports = exports["default"];