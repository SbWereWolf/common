// declaration
function docReady(fn) {
    if (document.readyState === "complete" || document.readyState === "interactive") {
        setTimeout(fn, 100);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}

// applying
docReady(function () {
    // some code here
});
