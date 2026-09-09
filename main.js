/**
 * BizJapan Main Theme Script
 * Optimized scroll header color changer with null-safety and requestAnimationFrame throttling
 */
(function() {
  'use strict';

  var ticking = false;

  function updateHeaderOnScroll() {
    var isHome = document.body.classList.contains('home');
    var top = window.scrollY || window.pageYOffset || 0;
    var headers = document.querySelectorAll('header .elementor-element.e-parent');
    var headingTexts = document.querySelectorAll('header .hfe-heading-text');
    var navCurrentTexts = document.querySelectorAll('header .current_page_item, header .current-menu-item');

    // On non-home pages, always keep solid white header; on home page, change after scrolling 20px
    var shouldChange = !isHome || (top > 20);

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
