(function () {
    'use strict';

    (function initFormEditTabs() {
        var root = document.querySelector('.form-edit-tabs');
        if (!root) return;
        var tabs = root.querySelectorAll('.tablist [role="tab"]');
        var panels = root.querySelectorAll('.tab-panel');
        if (!tabs.length || !panels.length) return;

        function activate(panelId) {
            tabs.forEach(function (tab) {
                var pid = tab.getAttribute('aria-controls');
                var sel = pid === panelId;
                tab.setAttribute('aria-selected', sel ? 'true' : 'false');
                tab.tabIndex = sel ? 0 : -1;
            });
            panels.forEach(function (p) {
                p.hidden = p.id !== panelId;
            });
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activate(tab.getAttribute('aria-controls'));
            });
        });
    })();

    var FB_REGEX_CUSTOM = '__custom__';

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

    function syncRegexRowValue(row) {
        var sel = row.querySelector('.fb-regex-kind');
        var hidden = row.querySelector('.fb-regex-value');
        var wrap = row.querySelector('.fb-regex-custom-wrap');
        var customInp = row.querySelector('.fb-regex-custom');
        if (!sel || !hidden) return;
        if (sel.value === FB_REGEX_CUSTOM) {
            if (wrap) wrap.style.display = '';
            hidden.value = customInp ? customInp.value.trim() : '';
        } else {
            if (wrap) wrap.style.display = 'none';
            hidden.value = sel.value;
        }
    }

    function syncAllRegexRows() {
        list.querySelectorAll('.fb-regex-row').forEach(syncRegexRowValue);
    }

    function reindex() {
        var items = list.querySelectorAll('.fb-field');
        items.forEach(function (li, i) {
            li.querySelectorAll('input, textarea, select').forEach(function (el) {
                if (el.classList.contains('fb-regex-custom') ||
                    el.classList.contains('fb-regex-kind') ||
                    el.classList.contains('fb-regex-value')) {
                    return;
                }
                var name = el.getAttribute('data-name') || extractDataName(el.getAttribute('name'));
                if (!name) return;
                el.setAttribute('name', 'fields[' + i + '][' + name + ']');
            });
            li.querySelectorAll('.fb-regex-value').forEach(function (el, j) {
                el.setAttribute('name', 'fields[' + i + '][regex_patterns][' + j + ']');
            });
        });
        syncAllRegexRows();
    }

    function bindRegexRow(row) {
        var sel = row.querySelector('.fb-regex-kind');
        var customInp = row.querySelector('.fb-regex-custom');
        if (sel) {
            sel.addEventListener('change', function () {
                syncRegexRowValue(row);
            });
        }
        if (customInp) {
            customInp.addEventListener('input', function () {
                syncRegexRowValue(row);
            });
        }
        syncRegexRowValue(row);
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
                    var sel = row.querySelector('.fb-regex-kind');
                    var customInp = row.querySelector('.fb-regex-custom');
                    var wrap = row.querySelector('.fb-regex-custom-wrap');
                    if (sel) sel.selectedIndex = 0;
                    if (customInp) customInp.value = '';
                    if (wrap) wrap.style.display = 'none';
                    syncRegexRowValue(row);
                    return;
                }
                row.parentNode.removeChild(row);
                reindex();
            });
        });
    }

    function bindFieldKeyLock(li) {
        var btn = li.querySelector('.fb-field-key-lock');
        var input = li.querySelector('.fb-field-key-input');
        if (!btn || !input) return;
        var icClosed = btn.querySelector('.fb-icon-lock-closed');
        var icOpen = btn.querySelector('.fb-icon-lock-open');

        function syncIcons() {
            var locked = input.readOnly;
            if (icClosed) icClosed.style.display = locked ? '' : 'none';
            if (icOpen) icOpen.style.display = locked ? 'none' : '';
            btn.setAttribute('aria-pressed', locked ? 'true' : 'false');
            btn.setAttribute('aria-label', locked ? 'キーを編集' : 'キーをロック');
            btn.setAttribute('title', locked ? '編集する' : 'ロックする');
            input.classList.toggle('is-locked', locked);
        }

        btn.addEventListener('click', function () {
            input.readOnly = !input.readOnly;
            syncIcons();
        });
        syncIcons();
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

        bindFieldKeyLock(li);
        bindRegexBlock(li);
    }

    list.querySelectorAll('.fb-field').forEach(bind);

    var mainForm = list.closest('form');
    if (mainForm) {
        mainForm.addEventListener('submit', function () {
            syncAllRegexRows();
        });
    }

    if (addBtn && template) {
        addBtn.addEventListener('click', function () {
            var clone = template.content.firstElementChild.cloneNode(true);
            var fkInput = clone.querySelector('.fb-field-key-input');
            if (fkInput) {
                fkInput.value = generateUuidV4();
                fkInput.readOnly = true;
                fkInput.classList.add('is-locked');
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
