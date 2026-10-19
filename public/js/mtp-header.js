(function () {
    var header = document.querySelector(".mtp-header");
    if (!header) {
        return;
    }

    var nav = header.querySelector(".mtp-nav");
    var toggle = header.querySelector(".mtp-nav-toggle");
    var mobile = window.matchMedia("(max-width: 1320px)");

    function closeNav() {
        if (!nav || !toggle) {
            return;
        }
        nav.classList.remove("open");
        toggle.setAttribute("aria-expanded", "false");
        toggle.setAttribute("aria-label", "Open menu");
    }

    if (nav && toggle) {
        toggle.addEventListener("click", function () {
            var open = nav.classList.toggle("open");
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
            toggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
        });

        document.addEventListener("click", function (event) {
            if (!nav.classList.contains("open")) {
                return;
            }
            if (header.contains(event.target)) {
                return;
            }
            closeNav();
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                closeNav();
                header.querySelectorAll(".mtp-has-sub.open").forEach(function (node) {
                    node.classList.remove("open");
                });
            }
        });

        window.addEventListener("resize", function () {
            if (!mobile.matches) {
                closeNav();
            }
        });
    }

    header.querySelectorAll(".mtp-has-sub > a").forEach(function (link) {
        link.addEventListener("click", function (event) {
            if (!mobile.matches) {
                return;
            }
            event.preventDefault();
            var parent = link.parentElement;
            var willOpen = !parent.classList.contains("open");
            header.querySelectorAll(".mtp-has-sub.open").forEach(function (node) {
                if (node !== parent) {
                    node.classList.remove("open");
                }
            });
            parent.classList.toggle("open", willOpen);
        });
    });
})();
