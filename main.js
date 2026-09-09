window.addEventListener("scroll", function(event) {
    var top = this.scrollY;
    var headers = document.getElementsByClassName('e-parent');
    var headingTexts = document.getElementsByClassName('hfe-heading-text');
    var navCurrentTexts = document.getElementsByClassName('current_page_item');
    if (top > 20) {
        headers[0].classList.add('change-color');
        headingTexts[0].classList.add('change-color');
        navCurrentTexts[0].classList.add('change-color');
    } else {
        headers[0].classList.remove('change-color');
        headingTexts[0].classList.remove('change-color');
        navCurrentTexts[0].classList.remove('change-color');
    }
}, false);

