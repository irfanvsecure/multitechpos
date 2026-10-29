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

    // Conversion tracking: GTM gets dataLayer events, plain GA4 gets gtag events
    function track(name, params) {
        params = params || {};
        params.page_path = location.pathname;
        if (typeof window.gtag === 'function' && !window.google_tag_manager) {
            window.gtag('event', name, params);
        } else {
            window.dataLayer = window.dataLayer || [];
            params.event = name;
            window.dataLayer.push(params);
        }
    }

    document.addEventListener('click', function (event) {
        var link = event.target.closest('a[href]');
        if (!link) {
            return;
        }
        var href = link.getAttribute('href');
        var where = link.closest('header, footer, .mobile-cta, .whatsapp-float, section');
        var area = where ? (where.className.split(' ')[0] || where.tagName.toLowerCase()) : 'page';
        if (href.indexOf('tel:') === 0) {
            track('click_call', { link_location: area });
        } else if (href.indexOf('wa.me') !== -1) {
            track('click_whatsapp', { link_location: area });
        } else if (/\/contact\/?$|#hero-demo$/.test(href)) {
            track('click_demo', { link_location: area, link_text: link.textContent.trim() });
        }
    });

    document.querySelectorAll('form[action$="/request-demo"]').forEach(function (form) {
        form.addEventListener('submit', function () {
            track('form_submit', { form_location: form.closest('section, aside') ? form.closest('section, aside').className.split(' ')[0] : 'page' });
        });
    });

    // Small storage helpers (storage can be blocked in private windows)
    function stored(area, key, value) {
        try {
            if (value === undefined) {
                return window[area].getItem(key);
            }
            window[area].setItem(key, value);
        } catch (e) {}
        return null;
    }

    // The page the visitor read before the contact page goes with the request
    var pageTopic = (function () {
        var h1 = document.querySelector('#content h1');
        return (h1 ? h1.textContent : document.title).replace(/\s+/g, ' ').trim();
    })();
    var onContact = /\/contact\/?$/.test(location.pathname);
    if (!onContact && !document.querySelector('.message-page')) {
        stored('sessionStorage', 'mtp_from', pageTopic + ' (' + location.pathname + ')');
    }

    document.querySelectorAll('form[action$="/request-demo"]').forEach(function (form) {
        var button = form.querySelector('button[type="submit"]');

        // Plain "Send" / "Submit" buttons say what the visitor gets
        if (button && /^(send|submit)$/i.test(button.textContent.trim())) {
            button.innerHTML = 'Get My Free Demo <i class="fas fa-arrow-right"></i>';
        }
        if (button && form.classList.contains('form') && !form.querySelector('.form__note')) {
            var note = document.createElement('p');
            note.className = 'form__note';
            note.innerHTML = '<i class="fas fa-lock"></i> Free &middot; No obligation &middot; We reply by phone or WhatsApp';
            button.after(note);
        }

        // Catch half-typed phone numbers before the form is sent
        var phone = form.querySelector('input[name="phone"]');
        if (phone) {
            phone.addEventListener('input', function () {
                var digits = phone.value.replace(/\D/g, '').length;
                phone.setCustomValidity(phone.value && digits < 7 ? 'Please enter your full phone number, e.g. +971 50 123 4567' : '');
            });
        }

        form.addEventListener('submit', function () {
            var message = form.querySelector('textarea[name="message"]');
            var from = stored('sessionStorage', 'mtp_from');
            var notes = stored('sessionStorage', 'mtp_notes');
            if (message && onContact && (from || notes)) {
                message.value = (message.value ? message.value + '\n\n' : '') +
                    (from ? 'Was reading: ' + from : '') + (notes ? '\n' + notes : '');
            }
            stored('localStorage', 'mtp_lead', Date.now());
            // one click, one lead
            if (button) {
                setTimeout(function () {
                    button.disabled = true;
                    button.classList.add('is-sending');
                    button.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Sending...';
                }, 0);
            }
        });
    });

    // Coming back with the browser's back button: buttons work again
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            document.querySelectorAll('button.is-sending').forEach(function (b) {
                b.disabled = false;
                b.classList.remove('is-sending');
                b.innerHTML = 'Send Again <i class="fas fa-arrow-right"></i>';
            });
        }
    });

    // "Call me back" card: opens 2.5 seconds after every page loads.
    // Not for people who already sent a form.
    var callback = document.getElementById('callback');
    if (callback && Date.now() - (+stored('localStorage', 'mtp_lead') || 0) > 30 * 864e5) {
        var cbForm = callback.querySelector('form');
        var cbMessage = cbForm.querySelector('input[name="message"]');
        var cbShown = false;
        if (pageTopic.length <= 42 && !document.querySelector('.home-hero')) {
            callback.querySelector('[data-callback-topic]').textContent = pageTopic;
        }
        cbForm.querySelector('input[name="service"]').value = pageTopic.slice(0, 95);
        cbMessage.value = 'Call back request from the page: ' + pageTopic + ' (' + location.pathname + ')';

        var showCallback = function () {
            // someone typing in another form: try again a little later
            var active = document.activeElement;
            if (active && active.closest('form') && !active.closest('#callback')) {
                setTimeout(showCallback, 2000);
                return;
            }
            var notes = stored('sessionStorage', 'mtp_notes');
            if (notes) {
                cbMessage.value += ' | ' + notes;
            }
            cbShown = true;
            callback.hidden = false;
            requestAnimationFrame(function () { callback.classList.add('is-open'); });
            document.body.classList.add('has-callback');
            track('callback_shown', { trigger: 'timer' });
        };
        var hideCallback = function () {
            callback.classList.remove('is-open');
            document.body.classList.remove('has-callback');
            setTimeout(function () { callback.hidden = true; }, 350);
        };
        setTimeout(showCallback, 2500);
        callback.querySelector('[data-callback-close]').addEventListener('click', hideCallback);
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && cbShown) {
                hideCallback();
            }
        });
    }

    var main = document.getElementById('content');
    var heroForm = document.getElementById('hero-demo');

    // Inner pages: demo strip mid-page, reading progress, reveal / tilt hooks
    if (main && !document.querySelector('.home-hero')) {
        var template = document.getElementById('inline-cta');

        // Long landing pages: strip after the third content section
        var sections = main.querySelectorAll(':scope > section.section');
        if (template && document.querySelector('.page-hero--form') && sections.length >= 5) {
            var band = document.createElement('section');
            band.className = 'inline-cta-band';
            band.innerHTML = '<div class="wrap"></div>';
            band.firstChild.appendChild(template.content.cloneNode(true));
            sections[2].after(band);
        }

        // Blog posts: strip in the middle of the article + reading progress bar
        var headings = document.querySelectorAll('.post__content > h2');
        if (template && headings.length >= 4) {
            headings[Math.floor(headings.length / 2)].before(template.content.cloneNode(true));
        }

        var article = document.querySelector('.page-post .post');
        if (article) {
            var bar = document.createElement('div');
            bar.className = 'read-progress';
            bar.innerHTML = '<span></span>';
            document.body.appendChild(bar);
            var fill = bar.firstChild;
            var onRead = function () {
                var box = article.getBoundingClientRect();
                var done = Math.min(Math.max(-box.top / (box.height - innerHeight), 0), 1);
                fill.style.transform = 'scaleX(' + done + ')';
            };
            window.addEventListener('scroll', onRead, { passive: true });
            onRead();
        }

        main.querySelectorAll('.section .tag-wrap, .section .title, .section .card, .section .card-dark, .section .img-frame, .section .faq, .post-card, .post__header, .inline-cta').forEach(function (el) {
            if (el.matches('.title, .tag-wrap') && el.closest('.card, .card-dark')) {
                return;
            }
            el.setAttribute('data-reveal', '');
        });

        // Tilt only small cards (not big boxes or forms)
        main.querySelectorAll('.section .card, .section .card-dark').forEach(function (card) {
            if (!card.querySelector('form') && card.offsetWidth < 560) {
                card.setAttribute('data-tilt', '');
            }
        });
    }

    // ---- Interactive sections (partials/cro/*) ---------------------------------

    // The "mid" widget waits in a <template>; put it after the steps section or the 2nd section
    var croMid = document.getElementById('cro-mid');
    if (croMid && main) {
        var homeSteps = document.querySelector('.home-section .steps');
        var anchor = document.querySelector('.svc-steps') ||
            (homeSteps && homeSteps.closest('section')) ||
            main.querySelectorAll(':scope > section.section')[1];
        if (anchor) {
            anchor.after(croMid.content.cloneNode(true));
        }
    }

    var aed = function (n) { return Math.round(n).toLocaleString('en-US'); };

    // What the visitor worked out goes with their demo request and WhatsApp message
    var heroMessage = heroForm ? heroForm.querySelector('input[name="message"]') : null;
    var heroMessageBase = heroMessage ? heroMessage.value : '';
    function remember(summary) {
        if (heroMessage) {
            heroMessage.value = heroMessageBase + ' | ' + summary.replace(/\n/g, ' · ');
        }
        stored('sessionStorage', 'mtp_notes', summary.replace(/\n/g, ' · '));
    }
    function waLink(widget, text) {
        return widget.getAttribute('data-wa') + encodeURIComponent(text);
    }

    // Problem finder
    document.querySelectorAll('[data-cro="finder"]').forEach(function (finder) {
        var boxes = finder.querySelectorAll('input[type="checkbox"]');
        var list = finder.querySelector('.cro-finder__fixes');
        var found = finder.querySelector('.cro-finder__found');
        var empty = finder.querySelector('.cro-finder__empty');

        function update() {
            var picked = Array.prototype.filter.call(boxes, function (b) { return b.checked; });
            found.hidden = !picked.length;
            empty.hidden = !!picked.length;
            finder.querySelector('[data-count-out]').textContent = picked.length;
            list.innerHTML = '';
            picked.forEach(function (b) {
                var li = document.createElement('li');
                li.innerHTML = '<span class="cro-finder__problem"></span><i class="fas fa-arrow-right"></i><a></a>';
                li.firstChild.textContent = b.value;
                li.lastChild.textContent = b.getAttribute('data-fix');
                li.lastChild.href = b.getAttribute('data-link');
                list.appendChild(li);
            });
            var text = 'Hi MultiTech, these are the problems in my business:\n' + picked.map(function (b) { return '- ' + b.value; }).join('\n') + '\nCan you show me how MultiTech POS fixes them?';
            finder.querySelector('[data-wa-out]').href = waLink(finder, text);
            if (picked.length) {
                remember('Problems: ' + picked.map(function (b) { return b.value; }).join(', '));
            }
        }
        boxes.forEach(function (b) { b.addEventListener('change', update); });
        finder.querySelector('[data-clear]').addEventListener('click', function () {
            boxes.forEach(function (b) { b.checked = false; });
            update();
        });
        update();
    });

    // Setup builder
    document.querySelectorAll('[data-cro="builder"]').forEach(function (builder) {
        var panels = builder.querySelectorAll('.cro-builder__panel');
        var steps = builder.querySelectorAll('.cro-builder__steps li');
        var next = builder.querySelector('[data-next]');
        var back = builder.querySelector('[data-back]');
        var step = 0;

        function value(name) {
            var el = builder.querySelector('input[name="' + name + '"]:checked');
            return el ? el.value : '';
        }

        function summary() {
            var modules = Array.prototype.map.call(builder.querySelectorAll('input[name="b_modules"]:checked'), function (m) { return m.value; });
            var rows = [['Business', value('b_type')], ['Branches', value('b_branches')], ['Counters', value('b_counters')], ['Modules', modules.join(', ') || 'Just the POS']];
            var dl = builder.querySelector('.cro-builder__summary');
            dl.innerHTML = '';
            rows.forEach(function (r) {
                var dt = document.createElement('dt');
                var dd = document.createElement('dd');
                dt.textContent = r[0];
                dd.textContent = r[1];
                dl.appendChild(dt);
                dl.appendChild(dd);
            });
            var text = 'Hi MultiTech, I would like a price for this setup:\n' + rows.map(function (r) { return r[0] + ': ' + r[1]; }).join('\n');
            builder.querySelector('[data-wa-out]').href = waLink(builder, text);
            // the setup travels with the contact form in this step
            var form = builder.querySelector('.cro-builder__form');
            form.querySelector('input[name="service"]').value = 'Setup builder: ' + rows[0][1];
            form.querySelector('input[name="message"]').value = 'Price request from the setup builder\n' + rows.map(function (r) { return r[0] + ': ' + r[1]; }).join('\n');
            remember('Setup: ' + rows.map(function (r) { return r[0] + ' ' + r[1]; }).join('; '));
            track('setup_builder_done', { business: rows[0][1] });
        }

        function go(to) {
            step = to;
            panels.forEach(function (p, i) { p.hidden = i !== step; });
            steps.forEach(function (s, i) {
                s.classList.toggle('is-active', i === step);
                s.classList.toggle('is-done', i < step);
            });
            back.hidden = step === 0;
            next.hidden = step === panels.length - 1;
            if (step === panels.length - 1) {
                summary();
            }
        }

        next.addEventListener('click', function () { go(Math.min(step + 1, panels.length - 1)); });
        // back from a failed submit: reopen the contact step so the errors are visible
        if (builder.querySelector('.cro-builder__form .form__errors') && /setup builder/i.test(builder.getAttribute('data-old') || '')) {
            go(panels.length - 1);
        }
        back.addEventListener('click', function () { go(Math.max(step - 1, 0)); });
        steps.forEach(function (s, i) {
            s.addEventListener('click', function () {
                if (i <= step + 1) {
                    go(i);
                }
            });
        });
    });

    // Loss calculator
    var formulas = {
        percent_of_value: function (v) { return v[0] * v[1] / 100; },
        percent_of_monthly: function (v) { return v[0] * v[1] / 100 * 12; },
        noshow: function (v) { return v[0] * v[1] / 100 * v[2] * 52; },
        hours: function (v) { return v[0] * v[1] * v[2] * 48; },
    };
    document.querySelectorAll('[data-cro="calculator"]').forEach(function (calc) {
        var sliders = calc.querySelectorAll('input[type="range"]');
        var formula = formulas[calc.getAttribute('data-formula')];
        var totalEl = calc.querySelector('[data-total]');
        var shown = 0;
        var animation;

        function show(total) {
            cancelAnimationFrame(animation);
            var from = shown;
            var start = null;
            function frame(time) {
                start = start || time;
                var p = Math.min((time - start) / 500, 1);
                shown = from + (total - from) * (1 - Math.pow(1 - p, 3));
                totalEl.textContent = aed(shown);
                if (p < 1) {
                    animation = requestAnimationFrame(frame);
                }
            }
            animation = requestAnimationFrame(frame);
        }

        function update() {
            var values = Array.prototype.map.call(sliders, function (s) {
                var out = s.parentElement.querySelector('output');
                out.textContent = out.getAttribute('data-prefix') + Number(s.value).toLocaleString('en-US') + out.getAttribute('data-suffix');
                // coloured track up to the thumb
                s.style.setProperty('--fill', ((s.value - s.min) / (s.max - s.min) * 100) + '%');
                return Number(s.value);
            });
            var total = formula(values);
            show(total);
            calc.querySelector('[data-month]').textContent = aed(total / 12);
            var lines = Array.prototype.map.call(sliders, function (s) {
                return s.getAttribute('data-label') + ': ' + s.parentElement.querySelector('output').textContent;
            });
            var text = 'Hi MultiTech, I used your calculator:\n' + lines.join('\n') + '\nResult: about AED ' + aed(total) + ' a year. How can MultiTech POS help me cut this?';
            calc.querySelector('[data-wa-out]').href = waLink(calc, text);
            remember('Calculator: ' + lines.join(', ') + ' = AED ' + aed(total) + '/year');
        }
        sliders.forEach(function (s) { s.addEventListener('input', update); });
        update();
    });

    // 30-second check
    document.querySelectorAll('[data-cro="quiz"]').forEach(function (quiz) {
        var questions = quiz.querySelectorAll('.cro-quiz__q');
        var dashes = quiz.querySelectorAll('.cro-quiz__progress span');
        var result = quiz.querySelector('.cro-quiz__result');
        var place = quiz.getAttribute('data-place');
        var answers = [];

        function show(i) {
            questions.forEach(function (q, n) { q.hidden = n !== i; });
            dashes.forEach(function (d, n) { d.classList.toggle('is-done', n < i); d.classList.toggle('is-active', n === i); });
            result.hidden = i < questions.length;
            quiz.querySelector('.cro-quiz__progress').hidden = i >= questions.length;
        }

        function finish() {
            var score = answers.filter(Boolean).length;
            var total = questions.length;
            var verdicts = score <= 1
                ? ['Your ' + place + ' is in good shape', 'You have the basics covered. A short demo shows what you could add as you grow, like loyalty, more branches or online sales.']
                : score <= 2
                    ? ['A few things are holding you back', 'Some daily jobs still depend on manual work. That is usually where time and money slip away first.']
                    : ['Your ' + place + ' has outgrown its system', 'Most of what slows you down today is exactly what a modern POS takes care of. It is worth seeing it working on your own products.'];
            quiz.querySelector('[data-score]').textContent = score;
            quiz.querySelector('.cro-quiz__ring').style.setProperty('--score', score / total);
            quiz.querySelector('[data-verdict]').textContent = verdicts[0];
            quiz.querySelector('[data-advice]').textContent = verdicts[1];
            var issues = Array.prototype.filter.call(questions, function (q, n) { return answers[n]; }).map(function (q) { return '- ' + q.querySelector('.cro-quiz__text').textContent; });
            var text = 'Hi MultiTech, I scored ' + score + '/' + total + ' on your 30-second check.' + (issues.length ? '\nThese apply to us:\n' + issues.join('\n') : '') + '\nCan you show me a demo?';
            quiz.querySelector('[data-wa-out]').href = waLink(quiz, text);
            remember('30-second check: ' + score + '/' + total);
            track('quiz_done', { score: score });
            show(questions.length);
        }

        quiz.querySelectorAll('[data-answer]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var i = Number(btn.closest('.cro-quiz__q').getAttribute('data-q'));
                answers[i] = btn.getAttribute('data-answer') === '1';
                if (i + 1 < questions.length) {
                    show(i + 1);
                } else {
                    finish();
                }
            });
        });
        quiz.querySelector('[data-restart]').addEventListener('click', function () {
            answers = [];
            show(0);
        });
        show(0);
    });

    // Day timeline: arrow buttons scroll the track
    document.querySelectorAll('[data-cro="day"]').forEach(function (day) {
        var track = day.querySelector('.cro-day__track');
        var by = function (dir) { return function () { track.scrollBy({ left: dir * track.clientWidth * .8, behavior: 'smooth' }); }; };
        day.querySelector('[data-day-prev]').addEventListener('click', by(-1));
        day.querySelector('[data-day-next]').addEventListener('click', by(1));
    });

    // Demo buttons jump to the banner form (when the page has one) and focus it
    document.addEventListener('click', function (event) {
        var link = event.target.closest('[data-focus-form], a.btn[href$="/contact/"]');
        if (!link || !heroForm) {
            return;
        }
        if (!link.hasAttribute('data-focus-form') && !/demo|quote/i.test(link.textContent)) {
            return;
        }
        var href = link.getAttribute('href');
        var target = href.charAt(0) === '#' ? document.querySelector(href) : heroForm;
        if (!target) {
            return;
        }
        event.preventDefault();
        // "Get a demo for my business" also picks that business in the form
        var business = link.getAttribute('data-business');
        var select = target.querySelector('select[name="service"]');
        if (business && select) {
            select.value = business;
        }
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        var input = target.querySelector('input[name="name"]');
        if (input) {
            setTimeout(function () { input.focus({ preventScroll: true }); }, 450);
        }
        target.classList.remove('is-pulse');
        void target.offsetWidth;
        target.classList.add('is-pulse');
    });

    // Business picker (home page): tabs with arrow-key support
    document.querySelectorAll('[data-biz-picker]').forEach(function (picker) {
        var tabs = Array.prototype.slice.call(picker.querySelectorAll('[role="tab"]'));

        function select(tab, focus) {
            tabs.forEach(function (t) {
                var on = t === tab;
                t.setAttribute('aria-selected', on ? 'true' : 'false');
                t.tabIndex = on ? 0 : -1;
                document.getElementById(t.getAttribute('aria-controls')).hidden = !on;
            });
            if (focus) {
                tab.focus();
            }
            track('select_business', { business: tab.textContent.trim() });
        }

        tabs.forEach(function (tab, i) {
            tab.addEventListener('click', function () { select(tab, false); });
            tab.addEventListener('keydown', function (event) {
                var step = { ArrowRight: 1, ArrowDown: 1, ArrowLeft: -1, ArrowUp: -1 }[event.key];
                if (step) {
                    event.preventDefault();
                    select(tabs[(i + step + tabs.length) % tabs.length], true);
                }
            });
        });
    });

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Count numbers up (500+, 15+ ...) once they come into view
    function countUp(el) {
        var end = parseInt(el.getAttribute('data-count'), 10);
        var suffix = el.getAttribute('data-suffix') || '';
        var start = null;
        function frame(time) {
            start = start || time;
            var progress = Math.min((time - start) / 1400, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.round(end * eased) + suffix;
            if (progress < 1) {
                requestAnimationFrame(frame);
            }
        }
        requestAnimationFrame(frame);
    }

    // Fade / slide items in as they scroll into view (siblings one after another)
    var revealItems = document.querySelectorAll('[data-reveal]');
    if ('IntersectionObserver' in window && !reduceMotion && revealItems.length) {
        document.documentElement.classList.add('js-reveal');
        revealItems.forEach(function (el) {
            // cards in the same grid come in one after another
            var group = el.parentElement ? (el.parentElement.closest('.cols, .post-grid, .solution-grid, .why-grid, .industry-grid, .steps, .plans, .reviews') || el.parentElement) : null;
            var siblings = group ? group.querySelectorAll('[data-reveal]') : [];
            var position = Array.prototype.indexOf.call(siblings, el);
            el.style.setProperty('--reveal-delay', Math.min(position, 8) * 80 + 'ms');
        });
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }
                entry.target.classList.add('is-visible');
                entry.target.querySelectorAll('[data-count]').forEach(countUp);
                observer.unobserve(entry.target);
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: .12 });
        revealItems.forEach(function (el) { observer.observe(el); });
    }

    // Soft 3D tilt that follows the mouse (desktop only)
    if (!reduceMotion && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        document.querySelectorAll('[data-tilt]').forEach(function (card) {
            var max = card.classList.contains('hero-form') ? 4 : 7;
            card.addEventListener('mousemove', function (event) {
                var box = card.getBoundingClientRect();
                var x = (event.clientX - box.left) / box.width - .5;
                var y = (event.clientY - box.top) / box.height - .5;
                card.style.setProperty('--tilt-x', (-y * max).toFixed(2) + 'deg');
                card.style.setProperty('--tilt-y', (x * max).toFixed(2) + 'deg');
                card.style.setProperty('--glow-x', ((x + .5) * 100).toFixed(1) + '%');
                card.style.setProperty('--glow-y', ((y + .5) * 100).toFixed(1) + '%');
                card.classList.add('is-tilting');
            });
            card.addEventListener('mouseleave', function () {
                card.style.setProperty('--tilt-x', '0deg');
                card.style.setProperty('--tilt-y', '0deg');
                card.classList.remove('is-tilting');
            });
        });
    }
});
