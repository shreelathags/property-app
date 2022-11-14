/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************!*\
  !*** ./resources/js/agent/view.js ***!
  \************************************/
$(document).ready(function ($) {
  $('#searchProperty').click(function () {
    text = $('#searchInput').val();
    pathArray = window.location.pathname.split('/');
    agentId = pathArray[3];
    $.get("/api/properties/search", {
      text: text,
      agentId: agentId
    }, function (response) {
      $('#propertyList').html(response);
    });
  });

  //Search cleared
  $('#searchInput').click(function () {
    setTimeout(function () {
      if ($('#searchInput').val() == "") {
        $('#searchProperty').prop('disabled', true);
      }
    }, 1);
  });
  $('#searchInput').keyup(function () {
    if ($(this).val().length >= 3) {
      $('#searchProperty').prop('disabled', false);
    } else {
      $('#searchProperty').prop('disabled', true);
    }
  });
});
/******/ })()
;