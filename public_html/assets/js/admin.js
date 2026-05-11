(function () {
    'use strict';

    (function initFormEditTabs() {
        var root = document.querySelector('[data-module="m-formEditTabs01"]');
        if (!root) return;
        var tabs = root.querySelectorAll('[role="tablist"] [role="tab"]');
        var panels = root.querySelectorAll('[role="tabpanel"]');
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
            if (panelId === 'tab-panel-fields') {
                window.setTimeout(function () {
                    if (typeof window.formkunInitFbSortable === 'function') {
                        window.formkunInitFbSortable();
                    }
                }, 20);
            }
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

    function updateFieldSummary(li) {
        var labelInp = li.querySelector('.fb-input-label');
        var typeSel = li.querySelector('.fb-type');
        var labelEl = li.querySelector('.fb-summary-label');
        var typeEl = li.querySelector('.fb-summary-type');
        if (!labelEl || !typeEl) return;
        labelEl.textContent = (labelInp && labelInp.value.trim()) ? labelInp.value.trim() : '（無題）';
        var opt = typeSel && typeSel.selectedOptions && typeSel.selectedOptions[0];
        typeEl.textContent = opt ? opt.textContent.trim() : '—';
    }

    function bindAccordion(li) {
        var toggle = li.querySelector('.fb-field-toggle');
        if (!toggle) return;
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var collapsed = li.classList.toggle('is-collapsed');
            toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        });
    }

    function refreshFbSortable() {
        if (!list || list.dataset.fbSortable !== '1' || typeof jQuery === 'undefined') return;
        try {
            jQuery(list).sortable('refresh');
        } catch (e1) {}
    }

    function initFbSortable() {
        if (!list || typeof jQuery === 'undefined' || !jQuery.fn.sortable) return;
        if (list.dataset.fbSortable === '1') {
            refreshFbSortable();
            return;
        }
        jQuery(list).sortable({
            handle: '.fb-drag-handle',
            axis: 'y',
            items: '> li.fb-field',
            placeholder: 'fb-sortable-placeholder',
            tolerance: 'pointer',
            cursor: 'grabbing',
            opacity: 0.88,
            scroll: true,
            scrollSensitivity: 48,
            scrollSpeed: 28,
            forcePlaceholderSize: true,
            delay: 0,
            distance: 4,
            zIndex: 9999,
            update: function () {
                reindex();
            }
        });
        list.dataset.fbSortable = '1';
    }

    window.formkunInitFbSortable = initFbSortable;

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
            var rv = li.querySelector('.fb-regex-value');
            if (rv) {
                rv.setAttribute('name', 'fields[' + i + '][regex_pattern]');
            }
            var numEl = li.querySelector('.fb-num');
            if (numEl) numEl.textContent = String(i + 1);
            updateFieldSummary(li);
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
        var row = li.querySelector('.fb-regex-row');
        if (row) {
            bindRegexRow(row);
        }
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
        var textPairSettings = li.querySelector('.fb-text-pair-settings');
        var textPairPlaceholders = li.querySelector('.fb-text-pair-placeholders');
        var placeholderWrap = li.querySelector('.fb-placeholder-wrap');
        function syncTypeSpecificUi() {
            if (!typeSelect) return;
            var v = typeSelect.value;
            if (optionsBox) {
                optionsBox.style.display = (v === 'radio' || v === 'checkbox' || v === 'select') ? '' : 'none';
            }
            if (textPairSettings) {
                textPairSettings.style.display = (v === 'text_pair') ? '' : 'none';
            }
            if (textPairPlaceholders) {
                textPairPlaceholders.style.display = (v === 'text_pair') ? '' : 'none';
            }
            if (placeholderWrap) {
                placeholderWrap.style.display = (v === 'text_pair') ? 'none' : '';
            }
        }
        if (typeSelect) {
            typeSelect.addEventListener('change', function () {
                syncTypeSpecificUi();
                updateFieldSummary(li);
            });
        }
        var up = li.querySelector('.fb-up');
        var dn = li.querySelector('.fb-down');
        var rm = li.querySelector('.fb-remove');
        if (up) up.addEventListener('click', function () {
            var prev = li.previousElementSibling;
            if (prev) {
                list.insertBefore(li, prev);
                reindex();
                refreshFbSortable();
            }
        });
        if (dn) dn.addEventListener('click', function () {
            var next = li.nextElementSibling;
            if (next) {
                list.insertBefore(next, li);
                reindex();
                refreshFbSortable();
            }
        });
        if (rm) rm.addEventListener('click', function () {
            if (list.querySelectorAll('.fb-field').length <= 1) {
                alert('項目は1つ以上必要です。');
                return;
            }
            if (confirm('この項目を削除しますか？')) {
                li.parentNode.removeChild(li);
                reindex();
                refreshFbSortable();
            }
        });

        bindAccordion(li);
        bindFieldKeyLock(li);
        bindRegexBlock(li);
        syncTypeSpecificUi();

        var labInp = li.querySelector('.fb-input-label');
        if (labInp) {
            labInp.addEventListener('input', function () { updateFieldSummary(li); });
            labInp.addEventListener('change', function () { updateFieldSummary(li); });
        }
        updateFieldSummary(li);
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
            initFbSortable();
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
