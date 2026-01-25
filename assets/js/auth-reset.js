(function () {
  document.querySelectorAll('.toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var sel = btn.getAttribute('data-toggle');
      var input = document.querySelector(sel);
      if (!input) return;
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.innerHTML = show
        ? '<i class="mdi mdi-eye-off-outline"></i>'
        : '<i class="mdi mdi-eye-outline"></i>';
      btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
  });
})();
