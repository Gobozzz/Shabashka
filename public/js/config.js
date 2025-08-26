const APP_URL = "http://127.0.0.1:8000";

function get_csrf() {
    return $('meta[name="csrf-token"]').attr("content");
}

function getRandomNumberInRange(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function sanitilezeHTML(text) {
    const tempDiv = document.createElement("div");
    tempDiv.textContent = text;
    return escapeHTML(tempDiv.innerHTML);
}

function escapeHTML(str) {
    if (typeof str !== "string") {
        return str;
    }
    return str
        .replace(/&/g, "&amp;") // Экранирование &
        .replace(/</g, "&lt;") // Экранирование <
        .replace(/>/g, "&gt;") // Экранирование >
        .replace(/"/g, "&quot;") // Экранирование "
        .replace(/'/g, "&#39;") // Экранирование '
        .replace(/`/g, "&#96;"); // Экранирование `
}

$.ajaxSetup({
    headers: { "X-CSRF-TOKEN": get_csrf() },
});
