// Reinitialize template components after Livewire navigation
(function () {
    function reinit() {
        try {
            // Recreate or update TemplateCustomizer so it binds to the new DOM
            if (typeof TemplateCustomizer !== "undefined") {
                try {
                    // If a previous instance exists, try to update it instead of destroying
                    if (
                        window.templateCustomizer &&
                        document.getElementById("template-customizer")
                    ) {
                        try {
                            window.templateCustomizer.update &&
                                window.templateCustomizer.update();
                        } catch (e) {}
                    } else {
                        // Destroy any stale instance reference
                        try {
                            window.templateCustomizer &&
                                window.templateCustomizer.destroy &&
                                window.templateCustomizer.destroy();
                        } catch (e) {}

                        // Only instantiate when the template HTML factory/strings are available
                        window.templateName =
                            document.documentElement.getAttribute(
                                "data-template",
                            ) ||
                            window.templateName ||
                            "";

                        try {
                            window.templateCustomizer = new TemplateCustomizer({
                                displayCustomizer: true,
                                lang:
                                    localStorage.getItem(
                                        "templateCustomizer-" +
                                            window.templateName +
                                            "--Lang",
                                    ) || "en",
                                defaultPrimaryColor: "#2092EC",
                                defaultContentLayout: "wide",
                                controls: [
                                    "color",
                                    "theme",
                                    "skins",
                                    "semiDark",
                                    "layoutCollapsed",
                                    "layoutNavbarOptions",
                                    "headerType",
                                    "contentLayout",
                                    "rtl",
                                ],
                            });
                        } catch (e) {
                            // If instantiation fails (missing HTML/template), skip and log
                            console.warn(
                                "TemplateCustomizer instantiation skipped:",
                                e && e.message ? e.message : e,
                            );
                        }
                    }
                } catch (e) {}
            }

            // Re-initialize the sidebar Menu instance (destroyed by Livewire DOM swap)
            const layoutMenuEl = document.getElementById("layout-menu");
            if (layoutMenuEl && typeof Menu !== "undefined") {
                try {
                    if (window.Helpers.mainMenu) {
                        try {
                            window.Helpers.mainMenu.destroy();
                        } catch (e) {}
                    }
                    const isHorizontal =
                        layoutMenuEl.classList.contains("menu-horizontal");
                    const tplName =
                        document.documentElement.getAttribute(
                            "data-template",
                        ) ||
                        window.templateName ||
                        "";
                    const showDropdown =
                        localStorage.getItem(
                            "templateCustomizer-" +
                                tplName +
                                "--ShowDropdownOnHover",
                        ) !== null
                            ? localStorage.getItem(
                                  "templateCustomizer-" +
                                      tplName +
                                      "--ShowDropdownOnHover",
                              ) === "true"
                            : true;
                    const menu = new Menu(layoutMenuEl, {
                        orientation: isHorizontal ? "horizontal" : "vertical",
                        closeChildren: !!isHorizontal,
                        showDropdownOnHover: showDropdown,
                    });
                    window.Helpers.scrollToActive &&
                        window.Helpers.scrollToActive(false);
                    window.Helpers.mainMenu = menu;
                } catch (e) {
                    console.warn("Menu reinit failed:", e && e.message);
                }
            }

            // Re-bind menu toggle buttons
            document
                .querySelectorAll(".layout-menu-toggle")
                .forEach(function (item) {
                    // Clone to remove old listeners, then re-attach
                    const clone = item.cloneNode(true);
                    item.parentNode &&
                        item.parentNode.replaceChild(clone, item);
                    clone.addEventListener("click", function (event) {
                        event.preventDefault();
                        window.Helpers &&
                            window.Helpers.toggleCollapsed &&
                            window.Helpers.toggleCollapsed();
                    });
                });

            // Re-run helper initializers that attach to DOM elements
            if (window.Helpers) {
                window.Helpers.initCustomOptionCheck &&
                    window.Helpers.initCustomOptionCheck();
                window.Helpers.initPasswordToggle &&
                    window.Helpers.initPasswordToggle();
                window.Helpers.initSpeechToText &&
                    window.Helpers.initSpeechToText();
                window.Helpers.initNavbarDropdownScrollbar &&
                    window.Helpers.initNavbarDropdownScrollbar();
                window.Helpers.syncCustomOptions &&
                    window.Helpers.syncCustomOptions();
            }

            // Re-init bootstrap tooltips
            if (typeof bootstrap !== "undefined") {
                const tooltipTriggerList = [].slice.call(
                    document.querySelectorAll('[data-bs-toggle="tooltip"]'),
                );
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Re-init Waves if present
            if (typeof Waves !== "undefined") {
                try {
                    Waves.init();
                } catch (e) {}
            }

            // Re-apply collapsed state from localStorage if enabled
            try {
                if (window.config && config.enableMenuLocalStorage) {
                    const name =
                        window.templateName ||
                        document.documentElement.getAttribute(
                            "data-template",
                        ) ||
                        "";
                    const val = localStorage.getItem(
                        "templateCustomizer-" + name + "--LayoutCollapsed",
                    );
                    if (typeof window.Helpers !== "undefined" && val !== null) {
                        window.Helpers.setCollapsed(val === "true", false);
                    }
                }
            } catch (e) {}
        } catch (err) {
            console.error("livewire-reinit error", err);
        }
    }

    document.addEventListener("livewire:navigated", reinit);
    document.addEventListener("livewire:navigate-end", reinit);
    // Also run on initial load in case scripts were executed before this file
    if (
        document.readyState === "complete" ||
        document.readyState === "interactive"
    ) {
        setTimeout(reinit, 50);
    } else {
        document.addEventListener("DOMContentLoaded", reinit);
    }
})();
