let regErrors = [
  "testowy błąd"
],
  logErrors = [];

const regErrorsElem = document.querySelector(".register-form .errors");
const loginErrorsElem = document.querySelector(".login-form .errors");

function updateErrors(elem, errArr) {
  elem.innerHTML = "";
  for (const error of errArr) {
    const err = document.createElement("div");
    err.innerHTML = error;
    regErrorsElem.appendChild(err);
  }
}

const updateRegErrors = () => updateErrors(regErrorsElem, regErrors);
const updateLogErrors = () => updateErrors(logErrorsElem, logErrors);

function checkPassword(input) {}
