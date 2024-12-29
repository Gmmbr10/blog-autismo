const passwords = document.querySelectorAll(".password");

for (let i = 0; i < passwords.length; i++) {
  const eye = passwords[i].querySelector("#eye-password");
  const password = passwords[i].querySelector("#password");

  eye.onclick = function () {
    if (password.type === "password") {
      password.type = "text";
      eye.classList.toggle("primary");
    } else {
      password.type = "password";
      eye.classList.toggle("primary");
    }
  };
}
