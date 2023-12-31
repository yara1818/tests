function showElement(btn, show) {
    btn.addEventListener("click", function() {
        if (show.style.display === 'none') {
            show.style.display = "block";
        } else {
            show.style.display = 'none';
        }
    });
}  

if (document.querySelector(".create-quiz__button_create-quiz") != null && document.querySelector(".create-quiz__form") != null) {
    const BUTTON_CREATE_QUIZ = document.querySelector(".create-quiz__button_create-quiz");
    const FORM_QUIZ = document.querySelector(".create-quiz__form");

    BUTTON_CREATE_QUIZ.addEventListener("click", showElement(BUTTON_CREATE_QUIZ, FORM_QUIZ));
} 

let questionCounter = 0;


document.querySelector(".form__button_add-question").addEventListener("click", function() {

    const questionTemplate = `
    <div class="questions__question">
    <div class="question__container">
    <div class="form__item">
    <span>
      Вопрос ${questionCounter}:
    </span>
    <input class="form__input" type="text" name="question[${questionCounter}]" required>
  </div>
  <div class="form__item">
    <span>
      Вариант ответа 1:
    </span>
    <input class="form__input" type="text" name="answer[${questionCounter}][]" required>
  </div>
  <div class="form__item">
    <span>
      Вариант ответа 2:
    </span>
    <input class="form__input" type="text" name="answer[${questionCounter}][]" required>
  </div>
  <div class="form__item">
    <span>
      Вариант ответа 3:
    </span>
    <input class="form__input" type="text" name="answer[${questionCounter}][]" required>
  </div>
  <div class="form__item">
    <span>
      Правильный вариант:
    </span> 
    <select class="form__input form__input_select" name="correct_option[${questionCounter}][]" required>
      <option class="form__input" value="1">Вариант 1</option>
      <option class="form__input" value="2">Вариант 2</option>
      <option class="form__input" value="3">Вариант 3</option>
    </select>
  </div>
    </div>
  </div>
    `;

    const parentDiv = document.querySelector(".form__questions");
    const questionDiv = document.createElement('div');
    questionDiv.classList.add('forms__question');
    questionDiv.innerHTML = questionTemplate;    
    parentDiv.appendChild(questionDiv);
    questionCounter++;

});

