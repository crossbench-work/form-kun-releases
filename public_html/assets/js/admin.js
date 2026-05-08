(function () {
    'use strict';

    var list = document.getElementById('fb-fields');
    if (!list) return;

    var template = document.getElementById('fb-template');
    var addBtn   = document.getElementById('fb-add');

    function reindex() {
        var items = list.querySelectorAll('.fb-field');
        items.forEach(function (li, i) {
            li.dataset.index = String(i);
            var num = li.querySelector('.fb-num');
            if (num) num.textContent = String(i + 1);

            li.querySelectorAll('input, textarea, select').forEach(function (el) {
                var name = el.getAttribute('data-name') || extractDataName(el.getAttribute('name'));
                if (!name) return;
                el.setAttribute('name', 'fields[' + i + '][' + name + ']');
            });
        });
    }

    function extractDataName(name) {
        if (!name) return null;
        var m = name.match(/^fields\[\d+\]\[(.+)\]$/);
        return m ? m[1] : null;
    }

    function bind(li) {
        var typeSelect = li.querySelector('.fb-type');
        var optionsBox = li.querySelector('.fb-options');
        if (typeSelect && optionsBox) {
            typeSelect.addEventListener('change', function () {
                var v = typeSelect.value;
                optionsBox.style.display = (v === 'radio' || v === 'checkbox' || v === 'select') ? '' : 'none';
            });
        }
        var up = li.querySelector('.fb-up');
        var dn = li.querySelector('.fb-down');
        var rm = li.querySelector('.fb-remove');
        if (up) up.addEventListener('click', function () {
            var prev = li.previousElementSibling;
            if (prev) { list.insertBefore(li, prev); reindex(); }
        });
        if (dn) dn.addEventListener('click', function () {
            var next = li.nextElementSibling;
            if (next) { list.insertBefore(next, li); reindex(); }
        });
        if (rm) rm.addEventListener('click', function () {
            if (list.querySelectorAll('.fb-field').length <= 1) {
                alert('項目は1つ以上必要です。');
                return;
            }
            if (confirm('この項目を削除しますか？')) {
                li.parentNode.removeChild(li);
                reindex();
            }
        });
    }

    list.querySelectorAll('.fb-field').forEach(bind);

    if (addBtn && template) {
        addBtn.addEventListener('click', function () {
            var clone = template.content.firstElementChild.cloneNode(true);
            list.appendChild(clone);
            bind(clone);
            reindex();
        });
    }

    // スラッグプレビュー
    var slugInput = document.getElementById('slug');
    var slugPreview = document.getElementById('slug-preview');
    if (slugInput && slugPreview) {
        slugInput.addEventListener('input', function () {
            slugPreview.textContent = slugInput.value || 'your-slug';
        });
    }
})();
