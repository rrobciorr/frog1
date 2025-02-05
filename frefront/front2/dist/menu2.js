let body = document.querySelector("body");
let dark = document.querySelector("#dark");
dark.onclick = function () {
  if (dark.checked == true) {
    body.classList.add("darkmode");
  } else {
    body.classList.remove("darkmode");
  }
};
