(function () {
    try {
        var theme = localStorage.getItem("theme");
        if (theme !== "light" && theme !== "dark") {
            theme = matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        }
        document.documentElement.setAttribute("data-theme", theme);
        var color = document.querySelector('meta[name="theme-color"]');
        if (color) {
            color.setAttribute("content", theme === "dark" ? "#1c1b19" : "#f7f4ed");
        }
    } catch (error) {}
})();
