function set_password_input_eye(id_input) {
    const input = document.getElementById(id_input);
    if (!input) return;
    const cords = get_absolute_cord_password_input(input);
    const absoluteX = cords[0];
    const absoluteY = cords[1];
    document.body.insertAdjacentHTML(
        "beforeend",
        `<img data-id-password-input="${id_input}" onclick="change_password_input_eye(this)" class="absolute opacity-100 z-2 -translate-y-1/2 cursor-pointer" style="top:${absoluteY}px;left:${absoluteX}px;" src="${APP_URL}/icons/eye_icon.svg" />`
    );
    window.addEventListener("resize", function () {
        const eye = document.querySelector(
            `img[data-id-password-input="${id_input}"]`
        );
        const input = document.getElementById(id_input);
        if (!eye || !input) return;
        const cords = get_absolute_cord_password_input(input);
        const absoluteX = cords[0];
        const absoluteY = cords[1];
        eye.style.top = `${absoluteY}px`;
        eye.style.left = `${absoluteX}px`;
    });
}

function get_absolute_cord_password_input(input) {
    const input_rect = input.getBoundingClientRect();
    const scrollLeft =
        window.pageXOffset || document.documentElement.scrollLeft;
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const absoluteX = input_rect.left + scrollLeft + input_rect.width - 34;
    const absoluteY = input_rect.top + scrollTop + input_rect.height / 2;
    return [absoluteX, absoluteY];
}

function change_password_input_eye(eye) {
    if (!eye) return;
    const id_password_input = eye.dataset.idPasswordInput;
    if (!id_password_input) return;
    const password_input = document.getElementById(id_password_input);
    if (!password_input) return;
    if (password_input.type === "password") {
        password_input.type = "text";
        eye.classList.remove("opacity-100");
        eye.classList.add("opacity-50");
    } else if (password_input.type === "text") {
        password_input.type = "password";
        eye.classList.remove("opacity-50");
        eye.classList.add("opacity-100");
    }
}
