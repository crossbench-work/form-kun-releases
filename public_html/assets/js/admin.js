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
    var FB_REGEX_CHECKBOX_LIMIT = '__checkbox_limit__';

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
        var li = row.closest('.fb-field');
        var checkboxLimitEnabled = li ? li.querySelector('.fb-checkbox-limit-enabled') : null;
        var checkboxLimitSettings = li ? li.querySelector('.fb-checkbox-limit-settings') : null;
        var checkboxMinInput = li ? li.querySelector('[data-name="checkbox_min_selections"], [name$="[checkbox_min_selections]"]') : null;
        var checkboxMaxInput = li ? li.querySelector('[data-name="checkbox_max_selections"], [name$="[checkbox_max_selections]"]') : null;
        if (!sel || !hidden) return;
        if (sel.value === FB_REGEX_CHECKBOX_LIMIT) {
            if (wrap) wrap.style.display = 'none';
            hidden.value = '';
            if (checkboxLimitEnabled) checkboxLimitEnabled.value = '1';
            if (checkboxLimitSettings) checkboxLimitSettings.style.display = '';
        } else if (sel.value === FB_REGEX_CUSTOM) {
            if (wrap) wrap.style.display = '';
            hidden.value = customInp ? customInp.value.trim() : '';
            if (checkboxLimitEnabled) checkboxLimitEnabled.value = '0';
            if (checkboxLimitSettings) checkboxLimitSettings.style.display = 'none';
            if (checkboxMinInput) checkboxMinInput.value = '';
            if (checkboxMaxInput) checkboxMaxInput.value = '';
        } else {
            if (wrap) wrap.style.display = 'none';
            hidden.value = sel.value;
            if (checkboxLimitEnabled) checkboxLimitEnabled.value = '0';
            if (checkboxLimitSettings) checkboxLimitSettings.style.display = 'none';
            if (checkboxMinInput) checkboxMinInput.value = '';
            if (checkboxMaxInput) checkboxMaxInput.value = '';
        }
    }

    function syncAllRegexRows() {
        list.querySelectorAll('.fb-regex-row').forEach(syncRegexRowValue);
    }

    function syncRegexOptionsForType(select, typeValue) {
        if (!select) return;
        Array.prototype.forEach.call(select.options, function (option) {
            var allow = true;
            if (typeValue === 'checkbox') {
                allow = option.value === FB_REGEX_CUSTOM || option.value === FB_REGEX_CHECKBOX_LIMIT;
            }
            option.hidden = !allow;
            option.disabled = !allow;
        });
        if (typeValue === 'checkbox') {
            if (select.value !== FB_REGEX_CUSTOM && select.value !== FB_REGEX_CHECKBOX_LIMIT) {
                select.value = FB_REGEX_CUSTOM;
            }
        } else if (select.value === FB_REGEX_CHECKBOX_LIMIT) {
            select.value = '';
        }
    }

    function updateFieldSummary(li) {
        var labelInp = li.querySelector('.fb-input-label');
        var typeSel = li.querySelector('.fb-type');
        var requiredToggle = li.querySelector('.fb-required-toggle');
        var regexKind = li.querySelector('.fb-regex-kind');
        var regexCustom = li.querySelector('.fb-regex-custom');
        var labelEl = li.querySelector('.fb-summary-label');
        var typeEl = li.querySelector('.fb-summary-type');
        var requiredEl = li.querySelector('.fb-summary-required');
        var regexEl = li.querySelector('.fb-summary-regex-text');
        if (!labelEl || !typeEl || !requiredEl || !regexEl) return;
        labelEl.textContent = (labelInp && labelInp.value.trim()) ? labelInp.value.trim() : '（無題）';
        var opt = typeSel && typeSel.selectedOptions && typeSel.selectedOptions[0];
        var typeValue = typeSel ? typeSel.value : '';
        typeEl.textContent = opt ? opt.textContent.trim() : '—';
        var isRequired = !!(requiredToggle && requiredToggle.checked);
        requiredEl.textContent = isRequired ? '必須' : '任意';
        if (!isRequired || !regexKind || regexKind.value === '' || typeValue === 'radio' || typeValue === 'select') {
            regexEl.textContent = 'なし';
        } else if (regexKind.value === FB_REGEX_CHECKBOX_LIMIT) {
            regexEl.textContent = '選択数';
        } else if (regexKind.value === FB_REGEX_CUSTOM) {
            if (!regexCustom || !regexCustom.value.trim()) {
                regexEl.textContent = 'なし';
                return;
            }
            regexEl.textContent = 'カスタム';
        } else {
            var regexOpt = regexKind.selectedOptions && regexKind.selectedOptions[0];
            regexEl.textContent = regexOpt ? regexOpt.textContent.trim() : 'なし';
        }
    }

    function bindAccordion(li) {
        var header = li.querySelector('.fb-field-header');
        if (!header) return;

        header.addEventListener('click', function (e) {
            if (e.target.closest('.fb-header-actions') || e.target.closest('.fb-header-delete')) {
                return;
            }
            li.classList.toggle('is-collapsed');
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

    function bindRegexRow(row, li) {
        var sel = row.querySelector('.fb-regex-kind');
        var customInp = row.querySelector('.fb-regex-custom');
        if (sel) {
            sel.addEventListener('change', function () {
                syncRegexRowValue(row);
                if (li) updateFieldSummary(li);
            });
        }
        if (customInp) {
            customInp.addEventListener('input', function () {
                syncRegexRowValue(row);
                if (li) updateFieldSummary(li);
            });
        }
        syncRegexRowValue(row);
    }

    function bindRegexBlock(li) {
        var row = li.querySelector('.fb-regex-row');
        if (row) {
            bindRegexRow(row, li);
        }
    }

    function syncChoiceEditor(box) {
        var source = box.querySelector('.fb-options-source');
        if (!source) return;
        var values = [];
        box.querySelectorAll('.fb-choice-input').forEach(function (input) {
            var value = input.value.trim();
            if (value !== '') {
                values.push(value);
            }
        });
        source.value = values.join('\n');
    }

    function moveChoiceItem(item, direction) {
        var sibling = direction === 'up' ? item.previousElementSibling : item.nextElementSibling;
        if (!sibling) return;
        var list = item.parentNode;
        if (direction === 'up') {
            list.insertBefore(item, sibling);
        } else {
            list.insertBefore(sibling, item);
        }
    }

    function createChoiceItem(value) {
        var row = document.createElement('div');
        row.className = 'fb-row fb-choiceItem';
        row.setAttribute('data-choice-item', '');
        row.innerHTML =
            '<div>' +
                '<input type="text" class="fb-choice-input" placeholder="選択肢">' +
            '</div>' +
            '<div class="fb-actions">' +
                '<button type="button" class="btn is-sm fb-choice-up">↑</button>' +
                '<button type="button" class="btn is-sm fb-choice-down">↓</button>' +
                '<button type="button" class="btn is-sm is-danger fb-choice-remove">削除</button>' +
            '</div>';
        var input = row.querySelector('.fb-choice-input');
        if (input) {
            input.value = value || '';
        }
        return row;
    }

    function bindChoiceItem(item, box) {
        var input = item.querySelector('.fb-choice-input');
        var up = item.querySelector('.fb-choice-up');
        var down = item.querySelector('.fb-choice-down');
        var remove = item.querySelector('.fb-choice-remove');
        if (input) {
            input.addEventListener('input', function () {
                syncChoiceEditor(box);
            });
            input.addEventListener('change', function () {
                syncChoiceEditor(box);
            });
        }
        if (up) {
            up.addEventListener('click', function () {
                moveChoiceItem(item, 'up');
                syncChoiceEditor(box);
            });
        }
        if (down) {
            down.addEventListener('click', function () {
                moveChoiceItem(item, 'down');
                syncChoiceEditor(box);
            });
        }
        if (remove) {
            remove.addEventListener('click', function () {
                item.parentNode.removeChild(item);
                syncChoiceEditor(box);
            });
        }
    }

    function bindChoiceEditor(li) {
        li.querySelectorAll('[data-choice-editor]').forEach(function (box) {
            var list = box.querySelector('.fb-choiceList');
            var add = box.querySelector('.fb-choice-add');
            if (!list || !add) return;
            list.querySelectorAll('[data-choice-item]').forEach(function (item) {
                bindChoiceItem(item, box);
            });
            add.addEventListener('click', function () {
                var item = createChoiceItem('');
                list.appendChild(item);
                bindChoiceItem(item, box);
                syncChoiceEditor(box);
                var input = item.querySelector('.fb-choice-input');
                if (input) input.focus();
            });
            syncChoiceEditor(box);
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
        var requiredToggle = li.querySelector('.fb-required-toggle');
        var optionsBox = li.querySelector('.fb-options');
        var textPairSettings = li.querySelector('.fb-text-pair-settings');
        var textPairPlaceholders = li.querySelector('.fb-text-pair-placeholders');
        var maxLengthWrap = li.querySelector('.fb-max-length-wrap');
        var maxLengthInput = maxLengthWrap ? maxLengthWrap.querySelector('input') : null;
        var regexWrap = li.querySelector('.fb-regex-wrap');
        var regexKind = regexWrap ? regexWrap.querySelector('.fb-regex-kind') : null;
        var placeholderWrap = li.querySelector('.fb-placeholder-wrap');
        var placeholderInput = placeholderWrap ? placeholderWrap.querySelector('input') : null;
        var checkboxLimitEnabled = li.querySelector('.fb-checkbox-limit-enabled');
        var checkboxLimitSettings = li.querySelector('.fb-checkbox-limit-settings');
        var checkboxMinInput = li.querySelector('[data-name="checkbox_min_selections"], [name$="[checkbox_min_selections]"]');
        var checkboxMaxInput = li.querySelector('[data-name="checkbox_max_selections"], [name$="[checkbox_max_selections]"]');
        function clearRegexSettings() {
            if (!regexWrap) return;
            var regexCustom = regexWrap.querySelector('.fb-regex-custom');
            var regexValue = regexWrap.querySelector('.fb-regex-value');
            if (regexKind) regexKind.value = '';
            if (regexCustom) regexCustom.value = '';
            if (regexValue) regexValue.value = '';
            var regexRow = regexWrap.querySelector('.fb-regex-row');
            if (regexRow) {
                syncRegexRowValue(regexRow);
            }
        }
        function clearCheckboxLimitSettings() {
            if (checkboxLimitEnabled) checkboxLimitEnabled.value = '0';
            if (checkboxLimitSettings) checkboxLimitSettings.style.display = 'none';
            if (checkboxMinInput) checkboxMinInput.value = '';
            if (checkboxMaxInput) checkboxMaxInput.value = '';
        }
        function syncTypeSpecificUi() {
            if (!typeSelect) return;
            var v = typeSelect.value;
            var hideMaxLength = (v === 'radio' || v === 'checkbox' || v === 'select');
            if (optionsBox) {
                optionsBox.style.display = (v === 'radio' || v === 'checkbox' || v === 'select') ? '' : 'none';
            }
            if (maxLengthWrap) {
                maxLengthWrap.style.display = hideMaxLength ? 'none' : '';
                if (hideMaxLength && maxLengthInput) {
                    maxLengthInput.value = '';
                }
            }
            if (regexKind) {
                syncRegexOptionsForType(regexKind, v);
                var regexRow = regexWrap ? regexWrap.querySelector('.fb-regex-row') : null;
                if (regexRow) {
                    syncRegexRowValue(regexRow);
                }
            }
            if (regexWrap) {
                var hideRegex = !requiredToggle || !requiredToggle.checked || v === 'radio' || v === 'select';
                regexWrap.style.display = hideRegex ? 'none' : '';
                if (hideRegex) {
                    clearRegexSettings();
                    clearCheckboxLimitSettings();
                }
            }
            if (v !== 'checkbox') {
                clearCheckboxLimitSettings();
            }
            if (textPairSettings) {
                textPairSettings.style.display = (v === 'text_pair') ? '' : 'none';
            }
            if (textPairPlaceholders) {
                textPairPlaceholders.style.display = (v === 'text_pair') ? '' : 'none';
            }
            if (placeholderWrap) {
                var hidePlaceholder = (v === 'text_pair' || v === 'radio' || v === 'checkbox' || v === 'select');
                placeholderWrap.style.display = hidePlaceholder ? 'none' : '';
                if (hidePlaceholder && placeholderInput) {
                    placeholderInput.value = '';
                }
            }
        }
        if (typeSelect) {
            typeSelect.addEventListener('change', function () {
                syncTypeSpecificUi();
                updateFieldSummary(li);
            });
        }
        if (requiredToggle) {
            requiredToggle.addEventListener('change', function () {
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
        bindChoiceEditor(li);
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
            list.querySelectorAll('[data-choice-editor]').forEach(syncChoiceEditor);
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

    var titleInput = document.getElementById('title');
    var breadcrumbCurrent = document.getElementById('breadcrumb-current');
    var inputScreenTitlePreview = document.getElementById('input-screen-title-preview');
    if (titleInput && breadcrumbCurrent) {
        titleInput.addEventListener('input', function () {
            var nextTitle = titleInput.value.trim() || '（無題）';
            breadcrumbCurrent.textContent = nextTitle;
            if (inputScreenTitlePreview) {
                inputScreenTitlePreview.textContent = nextTitle;
            }
        });
    }

    var slugInput = document.getElementById('slug');
    var slugPreview = document.getElementById('slug-preview');
    var slugLink = document.getElementById('slug-link');
    if (slugInput && slugPreview) {
        slugInput.addEventListener('input', function () {
            var slugValue = slugInput.value || 'your-slug';
            slugPreview.textContent = slugValue;
            if (slugLink) {
                var base = slugLink.getAttribute('data-base-url');
                if (!base) {
                    base = slugLink.href.replace(/\/[^/]*$/, '/');
                    slugLink.setAttribute('data-base-url', base);
                }
                slugLink.href = base + slugValue;
            }
        });
    }
})();
