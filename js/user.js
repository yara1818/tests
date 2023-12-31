function show(btn, element) {
    btn.addEventListener("click", function() {
        element.style.display = "block";
    });
}

const BUTTON_USER_EXIT = document.querySelector(".button_exit");
const USER_EXIT = document.querySelector(".modal-window_user-exit");

show(BUTTON_USER_EXIT, USER_EXIT);


const BUTTON_MODAL_YES = document.querySelector(".modal-window__button_yes");
const BUTTON_MODAL_NO = document.querySelector(".modal-window__button_no");
const CURRENT_PATH = window.location.pathname;

BUTTON_MODAL_NO.addEventListener("click", function() {
    USER_EXIT.style.display = "none";
});


BUTTON_MODAL_YES.addEventListener("click", function() {
    window.location = `${CURRENT_PATH}?logout`;
});