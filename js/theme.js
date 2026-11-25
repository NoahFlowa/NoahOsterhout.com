(function () {
    var key = "theme";
    var root = document.documentElement;
    var button = document.getElementById("theme-toggle");
    var color = document.querySelector('meta[name="theme-color"]');

    function saved() {
        try {
            return localStorage.getItem(key);
        } catch (error) {
            return null;
        }
    }

    function preferred() {
        var stored = saved();
        if (stored === "light" || stored === "dark") {
            return stored;
        }
        return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }

    function apply(theme, persist) {
        root.setAttribute("data-theme", theme);
        if (persist) {
            try {
                localStorage.setItem(key, theme);
            } catch (error) {}
        }
        if (color) {
            color.setAttribute("content", theme === "dark" ? "#1c1b19" : "#f7f4ed");
        }
        if (button) {
            var next = theme === "dark" ? "light" : "dark";
            button.textContent = next === "dark" ? "Dark" : "Light";
            button.setAttribute("aria-pressed", theme === "dark" ? "true" : "false");
            button.setAttribute("aria-label", "Switch to " + next + " theme");
        }
    }

    apply(preferred(), false);

    if (button) {
        button.addEventListener("click", function () {
            apply(root.getAttribute("data-theme") === "dark" ? "light" : "dark", true);
        });
    }

    var printButton = document.getElementById("print-button");
    if (printButton) {
        printButton.addEventListener("click", function () {
            window.print();
        });
    }

    window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", function (event) {
        var stored = saved();
        if (stored !== "light" && stored !== "dark") {
            apply(event.matches ? "dark" : "light", false);
        }
    });
})();
