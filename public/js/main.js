// MultiTech POS - small site scripts (no libraries needed)
document.addEventListener('DOMContentLoaded', function () {
    var header = document.querySelector('[data-header]');
    var toggle = document.querySelector('.nav-toggle');

    // Shadow under the sticky header once the page is scrolled
    function onScroll() {
        if (header) {
            header.classList.toggle('is-scrolled', window.scrollY > 10);
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // Drawer menu (tablet & phone)
    function setMenu(open) {
        document.body.classList.toggle('nav-open', open);
        if (toggle) {
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
    }

    if (toggle) {
        toggle.addEventListener('click', function () {
            setMenu(!document.body.classList.contains('nav-open'));
        });
    }

    document.querySelectorAll('.nav-close, [data-nav-close]').forEach(function (el) {
        el.addEventListener('click', function () { setMenu(false); });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            setMenu(false);
            document.querySelectorAll('.menu > li.is-open').forEach(function (li) { li.classList.remove('is-open'); });
        }
    });

    // Chevron buttons open / close a submenu (one at a time)
    document.querySelectorAll('.submenu-toggle').forEach(function (button) {
        button.addEventListener('click', function () {
            var item = button.parentElement;
            var open = !item.classList.contains('is-open');
            document.querySelectorAll('.menu > li.is-open').forEach(function (li) { li.classList.remove('is-open'); });
            item.classList.toggle('is-open', open);
            button.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    });

    // Close an open desktop submenu when clicking elsewhere
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.menu > li')) {
            document.querySelectorAll('.menu > li.is-open').forEach(function (li) { li.classList.remove('is-open'); });
        }
    });

    // Client logo slider: moves one logo every 5 seconds, pauses on hover
    document.querySelectorAll('[data-slider]').forEach(function (slider) {
        var track = slider.querySelector('.logo-slider__track');
        var items = track.children;
        var index = 0;
        var paused = false;

        function visibleCount() {
            return Math.round(slider.clientWidth / items[0].offsetWidth) || 1;
        }

        function next() {
            if (paused) {
                return;
            }
            var max = items.length - visibleCount();
            index = index >= max ? 0 : index + 1;
            track.style.transform = 'translateX(' + (-index * items[0].offsetWidth) + 'px)';
        }

        slider.addEventListener('mouseenter', function () { paused = true; });
        slider.addEventListener('mouseleave', function () { paused = false; });
        setInterval(next, 5000);
    });

    // Image carousel (one slide at a time, arrows + autoplay)
    document.querySelectorAll('[data-carousel]').forEach(function (carousel) {
        var track = carousel.querySelector('.slider__track');
        var count = track.children.length;
        var index = 0;

        function go(to) {
            index = (to + count) % count;
            track.style.transform = 'translateX(' + (-index * 100) + '%)';
        }

        carousel.querySelector('.slider__prev').addEventListener('click', function () { go(index - 1); });
        carousel.querySelector('.slider__next').addEventListener('click', function () { go(index + 1); });
        setInterval(function () { go(index + 1); }, 5000);
    });

    // Tabs
    document.querySelectorAll('[data-tabs]').forEach(function (tabs) {
        var buttons = tabs.querySelectorAll('.tabs__tab');
        var panes = tabs.querySelectorAll('.tabs__pane');
        buttons.forEach(function (button, i) {
            button.addEventListener('click', function () {
                buttons.forEach(function (b) { b.classList.remove('is-active'); });
                panes.forEach(function (p) { p.classList.remove('is-active'); });
                button.classList.add('is-active');
                panes[i].classList.add('is-active');
            });
        });
    });
});
