/**
 * BizJapan Main Theme Script
 * Optimized scroll header color changer with null-safety and requestAnimationFrame throttling
 */
(function() {
  'use strict';

  var ticking = false;

  function updateHeaderOnScroll() {
    var top = window.scrollY || window.pageYOffset || 0;
    var headers = document.querySelectorAll('header .elementor-element.e-parent');
    var headingTexts = document.querySelectorAll('header .hfe-heading-text');
    var navCurrentTexts = document.querySelectorAll('header .current_page_item, header .current-menu-item');

    var shouldChange = top > 20;

    headers.forEach(function(el) {
      if (el) el.classList.toggle('change-color', shouldChange);
    });

    headingTexts.forEach(function(el) {
      if (el) el.classList.toggle('change-color', shouldChange);
    });

    navCurrentTexts.forEach(function(el) {
      if (el) el.classList.toggle('change-color', shouldChange);
    });

    ticking = false;
  }

  window.addEventListener('scroll', function() {
    if (!ticking) {
      window.requestAnimationFrame(updateHeaderOnScroll);
      ticking = true;
    }
  }, { passive: true });

  // Initial check on DOMContentLoaded
  document.addEventListener('DOMContentLoaded', updateHeaderOnScroll);
})();
