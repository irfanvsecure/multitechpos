(function () {
    var header = document.querySelector("header.elementor-location-header");
    if (header) {
        var onScroll = function () {
            header.classList.toggle("is-scrolled", window.scrollY > 8);
        };
        onScroll();
        window.addEventListener("scroll", onScroll, { passive: true });
    }

    var path = window.location.pathname.replace(/\/+$/, "") || "/";
    document.querySelectorAll("#mega-menu-primary a.mega-menu-link").forEach(function (link) {
        var linkPath = link.pathname.replace(/\/+$/, "") || "/";
        if (linkPath !== path) {
            return;
        }
        var item = link.parentElement;
        while (item && item.id !== "mega-menu-primary") {
            if (item.classList && item.classList.contains("mega-menu-item")) {
                item.classList.add("mtp-current");
            }
            item = item.parentElement;
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key !== "Escape") {
            return;
        }
        var toggle = document.querySelector("#mega-menu-wrap-primary .mega-menu-toggle.mega-menu-open button");
        if (toggle) {
            toggle.click();
        }
    });

    document.addEventListener("click", function (event) {
        var toggle = document.querySelector("#mega-menu-wrap-primary .mega-menu-toggle");
        var menu = document.getElementById("mega-menu-primary");
        if (!toggle || !menu || !toggle.classList.contains("mega-menu-open")) {
            return;
        }
        if (toggle.contains(event.target) || menu.contains(event.target)) {
            return;
        }
        var button = toggle.querySelector("button");
        if (button) {
            button.click();
        }
    });

    var menu = document.getElementById("mega-menu-primary");

    function headerHeight() {
        if (!header) {
            return 78;
        }
        return Math.ceil(header.getBoundingClientRect().height);
    }

    function syncMegaPanel() {
        if (!header) {
            return;
        }
        document.documentElement.style.setProperty("--mtp-header-h", headerHeight() + "px");
        var page = header.nextElementSibling;
        var panel = menu
            ? menu.querySelector(":scope > li.mega-menu-megamenu.mega-toggle-on > ul.mega-sub-menu")
            : null;
        if (!page) {
            return;
        }
        if (!panel || window.innerWidth <= 1180) {
            page.style.marginTop = "";
            return;
        }
        page.style.marginTop = Math.ceil(panel.getBoundingClientRect().height) + "px";
    }

    function keepInside(panel, anchor) {
        if (!panel) {
            return;
        }
        panel.style.left = "0px";
        panel.style.right = "auto";
        var rect = panel.getBoundingClientRect();
        var anchorRect = anchor ? anchor.getBoundingClientRect() : rect;
        if (rect.right > window.innerWidth - 12) {
            panel.style.left = "auto";
            panel.style.right = "0px";
        }
        if (rect.left < 8 && anchorRect.right + panel.offsetWidth > window.innerWidth - 12) {
            panel.style.left = "auto";
            panel.style.right = "0px";
        }
    }

    if (menu) {
        menu.querySelectorAll(":scope > li.mega-menu-flyout").forEach(function (item) {
            item.addEventListener("mouseenter", function () {
                var panel = item.querySelector(":scope > ul.mega-sub-menu");
                keepInside(panel, item);
                item.querySelectorAll("li.mega-menu-item-has-children").forEach(function (child) {
                    var nested = child.querySelector(":scope > ul.mega-sub-menu");
                    if (!nested) {
                        return;
                    }
                    nested.style.left = "calc(100% - 6px)";
                    nested.style.right = "auto";
                    var nestedRect = nested.getBoundingClientRect();
                    if (nestedRect.right > window.innerWidth - 8) {
                        nested.style.left = "auto";
                        nested.style.right = "calc(100% - 6px)";
                    }
                });
            });
        });

        new MutationObserver(function () {
            window.requestAnimationFrame(syncMegaPanel);
        }).observe(menu, { attributes: true, subtree: true, attributeFilter: ["class"] });
    }

    syncMegaPanel();
    window.addEventListener("resize", syncMegaPanel);

    document.querySelectorAll(".elementor-counter-number").forEach(function (node) {
        var target = parseInt(node.getAttribute("data-to-value") || "", 10);
        if (!target) {
            return;
        }
        window.setTimeout(function () {
            if ((node.textContent || "").trim() === "0") {
                node.textContent = target.toLocaleString("en-US");
            }
        }, 2600);
    });
})();
