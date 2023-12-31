function show(btn, element) {
    btn.addEventListener("click", function() {
        element.style.display = "block";
    });
}

function hide(btn, element) {
    btn.addEventListener("click", function() {
        if (element.style.display != 'none') {
            element.style.display = "none";
        }
    });
}   

if (document.querySelector('.modal-window_error') != null && document.querySelector('.modal-window__button_error-exit') != null) {
    const BUTTON_EXIT_ERROR = document.querySelector('.modal-window__button_error-exit');
    const ERROR = document.querySelector('.modal-window_error');
    hide(BUTTON_EXIT_ERROR, ERROR);
}  

if (document.querySelector('.modal-window_success') != null && document.querySelector('.modal-window__button_success-exit') != null) {
    const BUTTON_EXIT_SUCCESS = document.querySelector('.modal-window__button_success-exit');
    const SUCCESS = document.querySelector('.modal-window_success');
    hide(BUTTON_EXIT_SUCCESS, SUCCESS);
}  

if (document.querySelector('.header__button_test') != null && document.querySelector('.section__tests') != null) {
    HEADER__BUTTON_TEST = document.querySelector(".header__button_test");
    TESTS = document.querySelector(".section__tests");

    HEADER__BUTTON_TEST.addEventListener('click', (e) => {
        TESTS.scrollIntoView({ 
            block: 'nearest',
            behavior: 'smooth',
        });
    })
}

if (document.querySelector('.header__button_user') != null) {
    HEADER__BUTTON_USER = document.querySelector(".header__button_user");
    HEADER__BUTTON_USER.addEventListener('click', (e) => {
        window.location.href = 'user.php';
    })
}
