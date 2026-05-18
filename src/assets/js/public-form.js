(function () {
  'use strict';
  var base = typeof window.FORMKUN_BASE === 'string' ? String(window.FORMKUN_BASE).replace(/\/?$/, '') : '';

  document.addEventListener('click', function (e) {
    var btn = e.target && e.target.closest ? e.target.closest('.js-zip-lookup') : null;
    if (!btn) {
      return;
    }
    var wrap = btn.closest ? btn.closest('[data-form-address]') : null;
    if (!wrap) {
      return;
    }
    e.preventDefault();
    var p1 = wrap.querySelector('.input-postal-part1');
    var p2 = wrap.querySelector('.input-postal-part2');
    var prefSel = wrap.querySelector('.input-prefecture');
    var cityInp = wrap.querySelector('.input-city');
    if (!p1 || !p2 || !prefSel || !cityInp) {
      return;
    }
    var zip = ((p1.value || '') + (p2.value || '')).replace(/\D/g, '');
    if (zip.length !== 7) {
      window.alert('郵便番号は7桁の数字で入力してください。');
      return;
    }
    var url = base + '/api/postal/lookup?zip=' + encodeURIComponent(zip);
    fetch(url, { credentials: 'same-origin' })
      .then(function (r) {
        if (!r.ok) {
          throw new Error('http');
        }
        return r.json();
      })
      .then(function (data) {
        if (data && data.ok && (data.prefecture || data.city)) {
          if (data.prefecture) {
            prefSel.value = data.prefecture;
          }
          cityInp.value = data.city || '';
        } else {
          window.alert((data && data.message) || '該当する住所が見つかりません。');
        }
      })
      .catch(function () {
        window.alert('住所検索に失敗しました。');
      });
  });
})();
