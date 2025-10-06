// Toggle password visibility
document.addEventListener('click', function(e){
  if (e.target && e.target.classList.contains('toggle-pass')) {
    var id = e.target.getAttribute('data-target');
    var input = document.getElementById(id);
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
  }
});

// Simple client-side validations and card switching
document.addEventListener('DOMContentLoaded', function(){
  var loginForm = document.querySelector('#loginCard form');
  var registerForm = document.querySelector('#registerCard form');
  var showReg = document.getElementById('btnShowRegister');
  var backLogin = document.getElementById('btnBackLogin');
  var loginCard = document.getElementById('loginCard');
  var registerCard = document.getElementById('registerCard');

  if (showReg) {
    showReg.addEventListener('click', function(){
      loginCard.classList.add('hidden');
      registerCard.classList.remove('hidden');
    });
  }

  if (backLogin) {
    backLogin.addEventListener('click', function(){
      registerCard.classList.add('hidden');
      loginCard.classList.remove('hidden');
    });
  }

  function validateNotEmpty(form){
    var inputs = form.querySelectorAll('input[required]');
    var ok = true;
    inputs.forEach(function(el){
      if (!el.value.trim()) {
        el.classList.add('error');
        ok = false;
      } else {
        el.classList.remove('error');
      }
    });
    if (!ok) alert('Por favor completa todos los campos.');
    return ok;
  }

  if (loginForm) {
    loginForm.addEventListener('submit', function(e){
      if (!validateNotEmpty(loginForm)) e.preventDefault();
    });
  }

  if (registerForm) {
    registerForm.addEventListener('submit', function(e){
      if (!validateNotEmpty(registerForm)) e.preventDefault();
    });
  }
});


