(function () {
  'use strict';

  function selectGateway(provider) {
    document.querySelectorAll('[data-provider]').forEach(function (el) {
      el.classList.toggle('is-selected', el.dataset.provider === provider);
    });
  }

  document.addEventListener('click', function (event) {
    var target = event.target.closest('[data-provider]');
    if (!target) return;
    event.preventDefault();
    selectGateway(target.dataset.provider);
  });
})();
