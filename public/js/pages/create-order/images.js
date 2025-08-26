const MAX_COUNT_IMAGES_FOR_ORDER = 10;
const MAX_COUNT_DOWNLOAD_IMAGES = 5;
const images_input_file = document.getElementById("images_input_file");
const images_container = document.getElementById("images_container");
const DIRECTION_LEFT = 1;
const DIRECTION_RIGHT = 2;

images_input_file.onchange = function () {
    let files = Array.from(images_input_file.files).slice(
        0,
        MAX_COUNT_DOWNLOAD_IMAGES
    );
    images_input_file.value = "";
    if (files.length == 0) {
        return;
    }
    if (getCurrentCountImages() >= MAX_COUNT_IMAGES_FOR_ORDER) {
        alert(
            `Максимальное количество фотографий - ${MAX_COUNT_IMAGES_FOR_ORDER}.`
        );
        return;
    }
    const data = new FormData();
    for (let i = 0; i < files.length; i++) {
        data.append("images[]", files[i]);
    }
    $.ajax({
        url: `${APP_URL}/orders/download/image`,
        type: "POST",
        data: data,
        withCredentials: true,
        processData: false,
        contentType: false,
        success: (data) => {
            if (data.length > 0) {
                const count_images_can_add =
                    data.length > getFreePlacesImages()
                        ? data.length - getFreePlacesImages()
                        : data.length;
                for (let i = 0; i < count_images_can_add; i++) {
                    const image = data[i];
                    images_container.insertAdjacentHTML(
                        "afterbegin",
                        `
                            <div class="many_images_item">
                                <input type="hidden" name="images[]" value="${image.path}">
                                <img src="${APP_URL}/storage/${image.path}" alt="">
                                <div class="many_images_item_action">
                                    <div onclick="delete_image(this)" class="many_images_item_action_btn">
                                        <img src="${APP_URL}/icons/delete.svg" alt="">
                                    </div>
                                    <div class="flex items-end gap-2">
                                        <div onclick="replace_image(this, ${DIRECTION_LEFT})" class="many_images_item_action_btn !bg-black">
                                            <img src="${APP_URL}/icons/arrow_left.svg" alt="">
                                        </div>
                                        <div onclick="replace_image(this, ${DIRECTION_RIGHT})" class="many_images_item_action_btn !bg-black rotate-180">
                                            <img src="${APP_URL}/icons/arrow_left.svg" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `
                    );
                }
            }
        },
        error: (err) => {
            console.log(err);
        },
    });
};

function getCurrentCountImages() {
    return images_container.querySelectorAll('input[name="images[]"]').length;
}
function getFreePlacesImages() {
    return MAX_COUNT_IMAGES_FOR_ORDER - getCurrentCountImages();
}

function delete_image(delete_button) {
    delete_button.parentNode.parentNode.remove();
}

function replace_image(navigate_button, direction) {
    const image_item = navigate_button.parentNode.parentNode.parentNode;
    const clone_image_item = image_item.cloneNode(true);
    if (direction == DIRECTION_LEFT) {
        const sibling = image_item.previousElementSibling;
        if (!sibling) return;
        sibling.insertAdjacentElement("beforebegin", clone_image_item);
    } else if (direction == DIRECTION_RIGHT) {
        const sibling = image_item.nextElementSibling;
        if (!sibling) return;
        sibling.insertAdjacentElement("afterend", clone_image_item);
    }
    image_item.remove();
}
