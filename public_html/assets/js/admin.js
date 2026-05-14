(function () {
    'use strict';

    (function initFormEditTabs() {
        var root = document.querySelector('[data-module="c-formEditTabs01"]');
        if (!root) return;
        var tabs = root.querySelectorAll('[data-target="c-formEditTabs01__tab"][role="tab"]');
        var panels = root.querySelectorAll('[data-target="c-formEditTabs01__contents"][role="tabpanel"]');
        if (!tabs.length || !panels.length) return;
        var storageKey = 'formkun:active-tab:' + window.location.pathname;

        function readStoredPanelId() {
            try {
                return window.sessionStorage.getItem(storageKey);
            } catch (e) {
                return null;
            }
        }

        function writeStoredPanelId(panelId) {
            try {
                window.sessionStorage.setItem(storageKey, panelId);
            } catch (e) {}
        }

        function activate(panelId) {
            tabs.forEach(function (tab) {
                var pid = tab.getAttribute('data-tab-panel');
                var sel = pid === panelId;
                tab.setAttribute('aria-selected', sel ? 'true' : 'false');
                tab.tabIndex = sel ? 0 : -1;
            });
            panels.forEach(function (p) {
                p.hidden = p.getAttribute('data-tab-panel') !== panelId;
            });
            writeStoredPanelId(panelId);
            if (panelId === 'fields') {
                window.setTimeout(function () {
                    if (typeof window.formkunInitFbSortable === 'function') {
                        window.formkunInitFbSortable();
                    }
                }, 20);
            }
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activate(tab.getAttribute('data-tab-panel'));
            });
            tab.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    activate(tab.getAttribute('data-tab-panel'));
                }
            });
        });

        var initialPanelId = readStoredPanelId();
        if (!initialPanelId) {
            var selectedTab = Array.prototype.find.call(tabs, function (tab) {
                return tab.getAttribute('aria-selected') === 'true';
            });
            initialPanelId = selectedTab ? selectedTab.getAttribute('data-tab-panel') : panels[0].getAttribute('data-tab-panel');
        }
        if (initialPanelId) {
            activate(initialPanelId);
        }
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

    var list = document.querySelector('[data-target="m-formBuilder01__fields"]');
    if (!list) return;

    var builderRoot = list.closest('[data-module="m-formBuilder01"]');
    var template = document.querySelector('[data-target="m-formBuilder01__fieldTemplate"]');
    var addBtn = document.querySelector('[data-target="m-formBuilder01__add"]');
    var descriptionTemplate = document.querySelector('[data-target="m-formBuilder01__descriptionTemplate"]');
    var descriptionEditors = document.querySelectorAll('[data-description-editor]');

    function getFieldItems() {
        return builderRoot ? builderRoot.querySelectorAll('.c-formEditor__item[data-field-item="1"]') : list.querySelectorAll('.c-formEditor__item[data-field-item="1"]');
    }

    function extractDataName(name) {
        if (!name) return null;
        var m = name.match(/^fields\[\d+\]\[(.+)\]$/);
        return m ? m[1] : null;
    }

    function extractDescriptionDataName(name, prefix) {
        if (!name) return null;
        var m = name.match(new RegExp('^' + prefix.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\[\\d+\\]\\[(.+)\\]$'));
        return m ? m[1] : null;
    }

    function getDescriptionPositionOptions(editor) {
        var mode = editor.getAttribute('data-position-mode') || 'simple';
        var options = [{ value: '0', label: '先頭' }];
        if (mode === 'input') {
            options.push({ value: String(getFieldItems().length + 1), label: '末尾' });
        } else {
            options.push({ value: '1', label: '末尾' });
        }
        return options;
    }

    function applyDescriptionPositionOptions(editor, select, preferredValue) {
        if (!select) return;
        var options = getDescriptionPositionOptions(editor);
        var nextValue = preferredValue;
        if (nextValue === undefined || nextValue === null || nextValue === '') {
            nextValue = select.value || select.getAttribute('data-value') || '0';
        }
        select.innerHTML = '';
        options.forEach(function (option) {
            var el = document.createElement('option');
            el.value = option.value;
            el.textContent = option.label;
            select.appendChild(el);
        });
        var available = options.some(function (option) { return option.value === String(nextValue); });
        select.value = available ? String(nextValue) : options[options.length - 1].value;
        select.setAttribute('data-value', select.value);
    }

    function getDescriptionList(editor) {
        return editor ? editor.querySelector('[data-description-list]') : null;
    }

    function syncHtmlToggleEditor(editor) {
        if (!editor) return;
        var toggle = editor.querySelector('[data-target="c-htmlInputEditor01__toggle"]');
        var textarea = editor.querySelector('[data-target="c-htmlInputEditor01__textarea"]');
        var isHtmlMode = !!(toggle && toggle.checked);
        editor.classList.toggle('is-html-mode', isHtmlMode);
        if (textarea) {
            textarea.spellcheck = !isHtmlMode;
            textarea.placeholder = isHtmlMode ? '<p>説明文を入力</p>' : '説明文を入力';
        }
    }

    function bindHtmlToggleEditor(editor) {
        if (!editor || editor.getAttribute('data-html-toggle-bound') === '1') return;
        var toggle = editor.querySelector('[data-target="c-htmlInputEditor01__toggle"]');
        if (toggle) {
            toggle.addEventListener('change', function () {
                syncHtmlToggleEditor(editor);
            });
        }
        editor.setAttribute('data-html-toggle-bound', '1');
        syncHtmlToggleEditor(editor);
    }

    function sortDescriptionBlocks(editor) {
        var descriptionList = getDescriptionList(editor);
        if (!descriptionList) return;
        var blocks = Array.prototype.slice.call(descriptionList.querySelectorAll('[data-description-block]'));
        blocks
            .sort(function (a, b) {
                var aSelect = a.querySelector('.fb-description-position');
                var bSelect = b.querySelector('.fb-description-position');
                var aPos = aSelect ? parseInt(aSelect.value || '0', 10) : 0;
                var bPos = bSelect ? parseInt(bSelect.value || '0', 10) : 0;
                return aPos - bPos;
            })
            .forEach(function (block) {
                descriptionList.appendChild(block);
            });
    }

    function reindexDescriptionBlocks(editor) {
        var descriptionList = getDescriptionList(editor);
        if (!descriptionList) return;
        var prefix = editor.getAttribute('data-name-prefix') || 'description_blocks';
        descriptionList.querySelectorAll('[data-description-block]').forEach(function (block, i) {
            block.setAttribute('data-index', String(i));
            block.querySelectorAll('input, textarea, select').forEach(function (el) {
                var name = el.getAttribute('data-name') || extractDescriptionDataName(el.getAttribute('name'), prefix);
                if (!name) return;
                el.setAttribute('name', prefix + '[' + i + '][' + name + ']');
            });
        });
    }

    function refreshDescriptionPositionOptions(editor) {
        var descriptionList = getDescriptionList(editor);
        if (!descriptionList) return;
        descriptionList.querySelectorAll('.fb-description-position').forEach(function (select) {
            applyDescriptionPositionOptions(editor, select);
        });
    }

    function refreshAllDescriptionPositionOptions() {
        descriptionEditors.forEach(function (editor) {
            refreshDescriptionPositionOptions(editor);
        });
    }

    function bindDescriptionBlock(editor, block) {
        var remove = block.querySelector('.fb-description-remove');
        var select = block.querySelector('.fb-description-position');
        bindHtmlToggleEditor(block.querySelector('[data-html-toggle-editor]'));
        if (remove) {
            remove.addEventListener('click', function () {
                block.parentNode.removeChild(block);
                reindexDescriptionBlocks(editor);
                refreshDescriptionPositionOptions(editor);
            });
        }
        if (select) {
            select.addEventListener('change', function () {
                select.setAttribute('data-value', select.value);
                sortDescriptionBlocks(editor);
                reindexDescriptionBlocks(editor);
            });
        }
    }

    function createDescriptionBlock(editor, position) {
        if (!descriptionTemplate) return null;
        var block = descriptionTemplate.content.firstElementChild.cloneNode(true);
        var idInput = block.querySelector('[data-name="id"]');
        var select = block.querySelector('.fb-description-position');
        var prefix = editor.getAttribute('data-name-prefix') || 'description_blocks';
        if (idInput) {
            idInput.value = prefix.replace(/_blocks$/, '') + '-' + generateUuidV4();
        }
        if (select) {
            select.setAttribute('data-value', String(position || 0));
        }
        bindDescriptionBlock(editor, block);
        return block;
    }

    function syncRegexRowValue(row) {
        var sel = row.querySelector('[data-target="c-formEditor__regexKind"]');
        var hidden = row.querySelector('[data-target="c-formEditor__regexValue"]');
        var wrap = row.querySelector('.c-formEditor__regexCustom');
        var customInp = row.querySelector('[data-target="c-formEditor__regexCustomInput"]');
        var li = row.closest('.c-formEditor__item');
        var checkboxLimitEnabled = li ? li.querySelector('[data-target="c-formEditor__checkboxLimitEnabled"]') : null;
        var checkboxLimitSettings = li ? li.querySelector('.c-formEditor__checkboxLimit') : null;
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
        getFieldItems().forEach(function (field) {
            field.querySelectorAll('.c-formEditor__regexRow').forEach(syncRegexRowValue);
        });
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
        var labelInp = li.querySelector('[data-target="c-formEditor__labelInput"]');
        var typeSel = li.querySelector('[data-target="c-formEditor__type"]');
        var requiredToggle = li.querySelector('[data-target="c-formEditor__required"]');
        var regexKind = li.querySelector('[data-target="c-formEditor__regexKind"]');
        var regexCustom = li.querySelector('[data-target="c-formEditor__regexCustomInput"]');
        var labelEl = li.querySelector('[data-target="c-formEditor__summaryLabel"]');
        var typeEl = li.querySelector('[data-target="c-formEditor__summaryType"]');
        var requiredEl = li.querySelector('[data-target="c-formEditor__summaryRequired"]');
        var regexEl = li.querySelector('[data-target="c-formEditor__summaryRegex"]');
        if (!labelEl) return;
        labelEl.textContent = (labelInp && labelInp.value.trim()) ? labelInp.value.trim() : '（無題）';
        if (!typeEl || !requiredEl || !regexEl) return;
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
        var header = li.querySelector('.c-formEditor__itemHeader');
        if (!header) return;

        header.addEventListener('click', function (e) {
            if (e.target.closest('.area.is-move') || e.target.closest('.area.is-delete')) {
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
            handle: '[data-target="c-formEditor__dragHandle"]',
            axis: 'y',
            items: '> .c-formEditor__item',
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
        var items = getFieldItems();
        items.forEach(function (li, i) {
            li.querySelectorAll('input, textarea, select').forEach(function (el) {
                if (el.matches('[data-target="c-formEditor__regexCustomInput"]') ||
                    el.matches('[data-target="c-formEditor__regexKind"]') ||
                    el.matches('[data-target="c-formEditor__regexValue"]')) {
                    return;
                }
                var name = el.getAttribute('data-name') || extractDataName(el.getAttribute('name'));
                if (!name) return;
                el.setAttribute('name', 'fields[' + i + '][' + name + ']');
            });
            var rv = li.querySelector('[data-target="c-formEditor__regexValue"]');
            if (rv) {
                rv.setAttribute('name', 'fields[' + i + '][regex_pattern]');
            }
            var numEl = li.querySelector('[data-target="c-formEditor__summaryIndex"]');
            if (numEl) numEl.textContent = String(i + 1);
            updateFieldSummary(li);
        });
        syncAllRegexRows();
        refreshAllDescriptionPositionOptions();
    }

    function bindRegexRow(row, li) {
        var sel = row.querySelector('[data-target="c-formEditor__regexKind"]');
        var customInp = row.querySelector('[data-target="c-formEditor__regexCustomInput"]');
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
        var row = li.querySelector('.c-formEditor__regexRow');
        if (row) {
            bindRegexRow(row, li);
        }
    }

    function syncChoiceEditor(box) {
        var source = box.querySelector('[data-target="c-formEditor__optionsSource"]');
        if (!source) return;
        var values = [];
        box.querySelectorAll('[data-target="c-formEditor__choiceInput"]').forEach(function (input) {
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
        row.className = 'c-formEditor__field c-formEditor__choiceItem';
        row.setAttribute('data-choice-item', '');
        row.innerHTML =
            '<div>' +
                '<input type="text" data-target="c-formEditor__choiceInput" placeholder="選択肢">' +
            '</div>' +
            '<div class="c-formEditor__actions">' +
                '<button type="button" class="btn is-sm" data-target="c-formEditor__choiceUp">↑</button>' +
                '<button type="button" class="btn is-sm" data-target="c-formEditor__choiceDown">↓</button>' +
                '<button type="button" class="btn is-sm is-danger" data-target="c-formEditor__choiceRemove">削除</button>' +
            '</div>';
        var input = row.querySelector('[data-target="c-formEditor__choiceInput"]');
        if (input) {
            input.value = value || '';
        }
        return row;
    }

    function bindChoiceItem(item, box) {
        var input = item.querySelector('[data-target="c-formEditor__choiceInput"]');
        var up = item.querySelector('[data-target="c-formEditor__choiceUp"]');
        var down = item.querySelector('[data-target="c-formEditor__choiceDown"]');
        var remove = item.querySelector('[data-target="c-formEditor__choiceRemove"]');
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
            var list = box.querySelector('.c-formEditor__choiceList');
            var add = box.querySelector('[data-target="c-formEditor__choiceAdd"]');
            if (!list || !add) return;
            list.querySelectorAll('[data-choice-item]').forEach(function (item) {
                bindChoiceItem(item, box);
            });
            add.addEventListener('click', function () {
                var item = createChoiceItem('');
                list.appendChild(item);
                bindChoiceItem(item, box);
                syncChoiceEditor(box);
                var input = item.querySelector('[data-target="c-formEditor__choiceInput"]');
                if (input) input.focus();
            });
            syncChoiceEditor(box);
        });
    }

    function syncTextMultiEditor(box) {
        var source = box.querySelector('[data-target="c-formEditor__textMultiSource"]');
        if (!source) return;
        var items = [];
        box.querySelectorAll('[data-text-multi-item]').forEach(function (item) {
            var labelInput = item.querySelector('[data-target="c-formEditor__textMultiLabelInput"]');
            var placeholderInput = item.querySelector('[data-target="c-formEditor__textMultiPlaceholderInput"]');
            items.push({
                label: labelInput ? labelInput.value.trim() : '',
                placeholder: placeholderInput ? placeholderInput.value.trim() : ''
            });
        });
        source.value = JSON.stringify(items);
    }

    function createTextMultiItem(data) {
        var item = document.createElement('div');
        item.className = 'c-formEditor__field c-formEditor__textMultiItem';
        item.setAttribute('data-text-multi-item', '');
        item.innerHTML =
            '<div class="wrapper">' +
                '<div class="block is-contents">' +
                    '<div class="block is-title"><label class="label" data-module="m-labelTitle01">表示名</label></div>' +
                    '<div class="block is-field"><div data-module="m-input01"><input type="text" data-target="c-formEditor__textMultiLabelInput"></div></div>' +
                '</div>' +
                '<div class="block is-contents">' +
                    '<div class="block is-title"><label class="label" data-module="m-labelTitle01">プレースホルダ（任意）</label></div>' +
                    '<div class="block is-field"><div data-module="m-input01"><input type="text" data-target="c-formEditor__textMultiPlaceholderInput"></div></div>' +
                '</div>' +
            '</div>' +
            '<div class="c-formEditor__actions">' +
                '<button type="button" class="btn is-sm is-danger" data-target="c-formEditor__textMultiRemove">削除</button>' +
            '</div>';
        var labelInput = item.querySelector('[data-target="c-formEditor__textMultiLabelInput"]');
        var placeholderInput = item.querySelector('[data-target="c-formEditor__textMultiPlaceholderInput"]');
        if (labelInput) {
            labelInput.value = data && typeof data.label === 'string' ? data.label : '';
        }
        if (placeholderInput) {
            placeholderInput.value = data && typeof data.placeholder === 'string' ? data.placeholder : '';
        }
        return item;
    }

    function bindTextMultiItem(item, box) {
        var labelInput = item.querySelector('[data-target="c-formEditor__textMultiLabelInput"]');
        var placeholderInput = item.querySelector('[data-target="c-formEditor__textMultiPlaceholderInput"]');
        var remove = item.querySelector('[data-target="c-formEditor__textMultiRemove"]');

        function sync() {
            syncTextMultiEditor(box);
        }

        if (labelInput) {
            labelInput.addEventListener('input', sync);
            labelInput.addEventListener('change', sync);
        }
        if (placeholderInput) {
            placeholderInput.addEventListener('input', sync);
            placeholderInput.addEventListener('change', sync);
        }
        if (remove) {
            remove.addEventListener('click', function () {
                var list = item.parentNode;
                if (!list) return;
                if (list.querySelectorAll('[data-text-multi-item]').length <= 1) {
                    alert('入力欄は1つ以上必要です。');
                    return;
                }
                list.removeChild(item);
                syncTextMultiEditor(box);
            });
        }
    }

    function bindTextMultiEditor(li) {
        li.querySelectorAll('[data-text-multi-editor]').forEach(function (box) {
            var list = box.querySelector('.c-formEditor__textMultiList');
            var add = box.querySelector('[data-target="c-formEditor__textMultiAdd"]');
            if (!list || !add) return;
            list.querySelectorAll('[data-text-multi-item]').forEach(function (item) {
                bindTextMultiItem(item, box);
            });
            add.addEventListener('click', function () {
                var item = createTextMultiItem();
                list.appendChild(item);
                bindTextMultiItem(item, box);
                syncTextMultiEditor(box);
                var labelInput = item.querySelector('[data-target="c-formEditor__textMultiLabelInput"]');
                if (labelInput) labelInput.focus();
            });
            syncTextMultiEditor(box);
        });
    }

    function bindFieldKeyLock(li) {
        var btn = li.querySelector('[data-target="c-formEditor__fieldKeyLock"]');
        var input = li.querySelector('[data-target="c-formEditor__fieldKeyInput"]');
        if (!btn || !input) return;
        var icClosed = btn.querySelector('[data-target="c-formEditor__fieldKeyLockedIcon"]');
        var icOpen = btn.querySelector('[data-target="c-formEditor__fieldKeyOpenIcon"]');

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
        var typeSelect = li.querySelector('[data-target="c-formEditor__type"]');
        var requiredToggle = li.querySelector('[data-target="c-formEditor__required"]');
        var optionsBox = li.querySelector('[data-target="c-formEditor__options"]');
        var textMultiSettings = li.querySelector('[data-target="c-formEditor__textMultiSettings"]');
        var maxLengthWrap = li.querySelector('[data-target="c-formEditor__minMaxLength"]');
        var maxLengthInput = maxLengthWrap ? maxLengthWrap.querySelector('input[data-name="max_length"]') : null;
        var minLengthInput = maxLengthWrap ? maxLengthWrap.querySelector('input[data-name="min_length"]') : null;
        var regexWrap = li.querySelector('[data-target="c-formEditor__regex"]');
        var regexKind = regexWrap ? regexWrap.querySelector('[data-target="c-formEditor__regexKind"]') : null;
        var placeholderWrap = li.querySelector('[data-target="c-formEditor__placeholder"]');
        var placeholderInput = placeholderWrap ? placeholderWrap.querySelector('input') : null;
        var checkboxLimitEnabled = li.querySelector('[data-target="c-formEditor__checkboxLimitEnabled"]');
        var checkboxLimitSettings = li.querySelector('.c-formEditor__checkboxLimit');
        var checkboxMinInput = li.querySelector('[data-name="checkbox_min_selections"], [name$="[checkbox_min_selections]"]');
        var checkboxMaxInput = li.querySelector('[data-name="checkbox_max_selections"], [name$="[checkbox_max_selections]"]');
        function clearRegexSettings() {
            if (!regexWrap) return;
            var regexCustom = regexWrap.querySelector('[data-target="c-formEditor__regexCustomInput"]');
            var regexValue = regexWrap.querySelector('[data-target="c-formEditor__regexValue"]');
            if (regexKind) regexKind.value = '';
            if (regexCustom) regexCustom.value = '';
            if (regexValue) regexValue.value = '';
            var regexRow = regexWrap.querySelector('.c-formEditor__regexRow');
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
                if (hideMaxLength) {
                    if (maxLengthInput) maxLengthInput.value = '';
                    if (minLengthInput) minLengthInput.value = '';
                }
            }
            if (regexKind) {
                syncRegexOptionsForType(regexKind, v);
                var regexRow = regexWrap ? regexWrap.querySelector('.c-formEditor__regexRow') : null;
                if (regexRow) {
                    syncRegexRowValue(regexRow);
                }
            }
            if (regexWrap) {
                var hideRegex = v === 'radio' || v === 'select';
                regexWrap.style.display = hideRegex ? 'none' : '';
                var isRequiredChecked = requiredToggle && requiredToggle.checked;
                if (regexKind) {
                    regexKind.disabled = !isRequiredChecked || hideRegex;
                }
                var regexCustomInput = regexWrap ? regexWrap.querySelector('[data-target="c-formEditor__regexCustomInput"]') : null;
                if (regexCustomInput) {
                    regexCustomInput.disabled = !isRequiredChecked || hideRegex;
                }
                if (hideRegex) {
                    clearRegexSettings();
                    clearCheckboxLimitSettings();
                }
            }
            if (v !== 'checkbox') {
                clearCheckboxLimitSettings();
            }
            if (textMultiSettings) {
                textMultiSettings.style.display = (v === 'text_multi') ? '' : 'none';
            }
            if (placeholderWrap) {
                var hidePlaceholder = (v === 'text_multi' || v === 'radio' || v === 'checkbox' || v === 'select');
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
        var up = li.querySelector('[data-target="c-formEditor__moveUp"]');
        var dn = li.querySelector('[data-target="c-formEditor__moveDown"]');
        var rm = li.querySelector('[data-target="c-formEditor__remove"]');
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
            if (list.querySelectorAll('.c-formEditor__item').length <= 1) {
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
        bindTextMultiEditor(li);
        syncTypeSpecificUi();

        var labInp = li.querySelector('[data-target="c-formEditor__labelInput"]');
        if (labInp) {
            labInp.addEventListener('input', function () {
                updateFieldSummary(li);
                refreshAllDescriptionPositionOptions();
            });
            labInp.addEventListener('change', function () {
                updateFieldSummary(li);
                refreshAllDescriptionPositionOptions();
            });
        }
        updateFieldSummary(li);
    }

    function applyFieldPreset(li, preset) {
        if (!preset) return;
        var labelInput = li.querySelector('[data-name="label"]');
        var typeSelect = li.querySelector('[data-name="type"]');
        var requiredToggle = li.querySelector('[data-name="required"]');
        var optionsSource = li.querySelector('[data-name="options"]');
        var choiceList = li.querySelector('.c-formEditor__choiceList');

        if (labelInput && typeof preset.label === 'string') {
            labelInput.value = preset.label;
        }
        if (typeSelect && typeof preset.type === 'string') {
            typeSelect.value = preset.type;
        }
        if (requiredToggle) {
            requiredToggle.checked = !!preset.required;
        }
        if (choiceList && Array.isArray(preset.options)) {
            choiceList.innerHTML = '';
            preset.options.forEach(function (option) {
                choiceList.appendChild(createChoiceItem(option));
            });
        }
        if (optionsSource && Array.isArray(preset.options)) {
            optionsSource.value = preset.options.join('\n');
        }
    }

    function appendField(preset) {
        if (!template) return null;
        var clone = template.content.firstElementChild.cloneNode(true);
        var fkInput = clone.querySelector('[data-target="c-formEditor__fieldKeyInput"]');
        if (fkInput) {
            fkInput.value = generateUuidV4();
            fkInput.readOnly = true;
            fkInput.classList.add('is-locked');
        }
        applyFieldPreset(clone, preset);
        list.appendChild(clone);
        bind(clone);
        reindex();
        initFbSortable();
        return clone;
    }

    getFieldItems().forEach(bind);
    descriptionEditors.forEach(function (editor) {
        var descriptionList = getDescriptionList(editor);
        var addButton = editor.querySelector('.fb-description-add');
        if (descriptionList) {
            descriptionList.querySelectorAll('[data-description-block]').forEach(function (block) {
                bindDescriptionBlock(editor, block);
            });
        }
        if (addButton && descriptionTemplate) {
            addButton.addEventListener('click', function () {
                var options = getDescriptionPositionOptions(editor);
                var position = options[options.length - 1].value;
                var block = createDescriptionBlock(editor, position);
                if (!block || !descriptionList) return;
                descriptionList.appendChild(block);
                refreshDescriptionPositionOptions(editor);
                var select = block.querySelector('.fb-description-position');
                if (select) {
                    select.value = String(position);
                    select.setAttribute('data-value', select.value);
                }
                sortDescriptionBlocks(editor);
                reindexDescriptionBlocks(editor);
                var textarea = block.querySelector('[data-target="c-htmlInputEditor01__textarea"]');
                if (textarea) textarea.focus();
            });
        }
    });

    var mainForm = list.closest('form');
    if (mainForm) {
        mainForm.addEventListener('submit', function () {
            syncAllRegexRows();
            list.querySelectorAll('[data-choice-editor]').forEach(syncChoiceEditor);
            list.querySelectorAll('[data-text-multi-editor]').forEach(syncTextMultiEditor);
            descriptionEditors.forEach(function (editor) {
                sortDescriptionBlocks(editor);
                reindexDescriptionBlocks(editor);
            });
        });
    }

    if (addBtn && template) {
        addBtn.addEventListener('click', function () {
            var clone = appendField();
            var labelInput = clone ? clone.querySelector('[data-target="c-formEditor__labelInput"]') : null;
            if (labelInput) {
                labelInput.focus();
            }
        });
    }

    reindex();
    descriptionEditors.forEach(function (editor) {
        reindexDescriptionBlocks(editor);
        refreshDescriptionPositionOptions(editor);
    });

    var titleInput = document.getElementById('title');
    var breadcrumbCurrent = document.querySelector('[data-target="p-formEdit01__breadcrumbCurrent"]');
    if (titleInput && breadcrumbCurrent) {
        titleInput.addEventListener('input', function () {
            var nextTitle = titleInput.value.trim() || '（無題）';
            breadcrumbCurrent.textContent = nextTitle;
        });
    }

    var slugInput = document.getElementById('slug');
    var slugPreview = document.querySelector('[data-target="p-formEdit01__slugPreview"]');
    var slugLink = document.querySelector('[data-target="p-formEdit01__slugLink"]');
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
