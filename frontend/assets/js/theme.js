/**
 * UEP LES System — Theme persistence helper
 * Included on every page to apply saved theme before CSS renders (prevents flash).
 * Also wires up the #themeBtn toggle if present on the page.
 */
(function () {
    var t = localStorage.getItem('ueples_theme') || 'dark';
    document.documentElement.dataset.theme = t;
})();

document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('themeBtn');
    if (!btn) return;
    var cur = localStorage.getItem('ueples_theme') || 'dark';
    btn.textContent = cur === 'dark' ? '🌙' : '☀️';
    btn.addEventListener('click', function () {
        var next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
        document.documentElement.dataset.theme = next;
        localStorage.setItem('ueples_theme', next);
        btn.textContent = next === 'dark' ? '🌙' : '☀️';
    });
});
