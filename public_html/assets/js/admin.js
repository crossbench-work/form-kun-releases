(function () {
    'use strict';

    function generateUuidV4() {
        var bytes = new Uint8Array(16);
        if (typeof crypto !== 'undefined' && crypto.getRandomValues) {
            crypto.getRandomValues(bytes);
        } else {
            for (var i = 0; i < 16; i++) {
                bytes[i] = Math.floor(Math.random() * 256);
            }
        }
        bytes[6] = (bytes[6] & 0x0f) | 0x40;
        bytes[8] = (bytes[8] & 0x3f) | 0x80;
        var h = '';
        for (var j = 0; j < 16; j++) {
            h += ('0' + bytes[j].toString(16)).slice(-2);
        }
        return (
            h.slice(0, 8) + '-' + h.slice(8, 12) + '-' + h.slice(12, 16) + '-' +
            h.slice(16, 20) + '-' + h.slice(20, 32)
        );
    }

    function isUuid(s) {
        return /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i.test(String(s || ''));
    }

    var list = document.getElementById('fb-fields');
    if (!list) return;

    var template = document.getElementById('fb-template');
    var regexRowTpl = document.getElementById('fb-regex-row-template');
    var addBtn = document.getElementById('fb-add');

    function extractDataName(name) {
        if (!name) return null;
        var m = name.match(/^fields\[\d+\]\[(.+)\]$/);
        return m ? m[1] : null;
    }

    function reindex() {
        var items = list.querySelectorAll('.fb-field');
        items.forEach(function (li, i) {
            li.querySelectorAll('input, textarea, select').forEach(function (el) {
                if (el.classList.contains('fb-regex-input')) {
                    return;
                }
                var name = el.getAttribute('data-name') || extractDataName(el.getAttribute('name'));
                if (!name) return;
                el.setAttribute('name', 'fields[' + i + '][' + name + ']');
            });
            li.querySelectorAll('.fb-regex-input').forEach(function (el, j) {
                el.setAttribute('name', 'fields[' + i + '][regex_patterns][' + j + ']');
            });
        });
    }

    function bindRegexRow(row) {
        var preset = row.querySelector('.fb-regex-preset');
        var input = row.querySelector('.fb-regex-input');
        if (preset && input) {
            preset.addEventListener('change', function () {
                if (preset.value !== '') {
                    input.value = preset.value;
                }
            });
        }
    }

    function bindRegexBlock(li) {
        li.querySelectorAll('.fb-regex-row').forEach(bindRegexRow);

        var rlist = li.querySelector('.fb-regex-list');
        var addRx = li.querySelector('.fb-regex-add');

        if (addRx && rlist && regexRowTpl) {
            addRx.addEventListener('click', function () {
                var node = regexRowTpl.content.firstElementChild.cloneNode(true);
                rlist.appendChild(node);
                bindRegexRow(node);
                reindex();
            });
        }

        li.querySelectorAll('.fb-regex-remove').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var rlistInner = li.querySelector('.fb-regex-list');
                var row = btn.closest('.fb-regex-row');
                if (!row || !rlistInner) return;
                var rows = rlistInner.querySelectorAll('.fb-regex-row');
                if (rows.length <= 1) {
                    var inp = row.querySelector('.fb-regex-input');
                    var pres = row.querySelector('.fb-regex-preset');
                    if (inp) inp.value = '';
                    if (pres) pres.selectedIndex = 0;
                    return;
                }
                row.parentNode.removeChild(row);
                reindex();
            });
        });
    }

    function bindFieldKeyAuto(li) {
        var cb = li.querySelector('.fb-field-key-auto');
        var input = li.querySelector('.fb-field-key-input');
        if (!cb || !input) return;

        function sync() {
            if (cb.checked) {
                input.readOnly = true;
                if (!input.value || !isUuid(input.value)) {
                    input.value = generateUuidV4();
                }
            } else {
                input.readOnly = false;
            }
        }

        cb.addEventListener('change', function () {
            if (cb.checked) {
                input.value = generateUuidV4();
            }
            sync();
        });
        sync();
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

        bindFieldKeyAuto(li);
        bindRegexBlock(li);
    }

    list.querySelectorAll('.fb-field').forEach(bind);

    if (addBtn && template) {
        addBtn.addEventListener('click', function () {
            var clone = template.content.firstElementChild.cloneNode(true);
            var fkInput = clone.querySelector('.fb-field-key-input');
            var fkCb = clone.querySelector('.fb-field-key-auto');
            if (fkInput && fkCb) {
                fkCb.checked = true;
                fkInput.readOnly = true;
                fkInput.value = generateUuidV4();
            }
            list.appendChild(clone);
            bind(clone);
            reindex();
        });
    }

    reindex();

    var slugInput = document.getElementById('slug');
    var slugPreview = document.getElementById('slug-preview');
    if (slugInput && slugPreview) {
        slugInput.addEventListener('input', function () {
            slugPreview.textContent = slugInput.value || 'your-slug';
        });
    }
})();
