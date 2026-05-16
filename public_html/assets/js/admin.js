(function () {
    'use strict';

    var TOAST_MS = 4500;
    var TOAST_LEAVE_MS = 280;

    window.formkunShowAdminToast = function (message, type) {
        type = type || 'success';
        var stack = document.getElementById('admin-toast-stack');
        if (!stack || !message) return;
        var el = document.createElement('div');
        el.className = 'c-adminToast01 is-' + type;
        el.setAttribute('role', 'status');
        el.textContent = message;
        stack.appendChild(el);
        window.requestAnimationFrame(function () {
            el.classList.add('is-visible');
        });
        window.setTimeout(function () {
            el.classList.remove('is-visible');
            el.classList.add('is-leaving');
            window.setTimeout(function () {
                if (el.parentNode) el.parentNode.removeChild(el);
            }, TOAST_LEAVE_MS);
        }, TOAST_MS);
    };

    (function initAdminFlashToasts() {
        var stack = document.getElementById('admin-toast-stack');
        if (!stack) return;
        stack.querySelectorAll('[data-admin-autotoast]').forEach(function (el) {
            window.requestAnimationFrame(function () {
                el.classList.add('is-visible');
            });
            window.setTimeout(function () {
                el.classList.remove('is-visible');
                el.classList.add('is-leaving');
                window.setTimeout(function () {
                    if (el.parentNode) el.parentNode.removeChild(el);
                }, TOAST_LEAVE_MS);
            }, TOAST_MS);
        });
    })();

    (function initCopyTags() {
        var tags = document.querySelectorAll('[data-copy-text]');
        if (!tags.length) return;

        function fallbackCopy(text) {
            var input = document.createElement('textarea');
            input.value = text;
            input.setAttribute('readonly', 'readonly');
            input.style.position = 'absolute';
            input.style.left = '-9999px';
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
        }

        function showCopyToast(text) {
            if (typeof window.formkunShowAdminToast === 'function') {
                var shortText = text.length > 48 ? text.slice(0, 45) + '…' : text;
                window.formkunShowAdminToast('「' + shortText + '」をコピーしました', 'success');
            }
        }

        tags.forEach(function (tag) {
            tag.addEventListener('click', function () {
                var text = tag.getAttribute('data-copy-text') || '';
                if (!text) return;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(function () {
                        showCopyToast(text);
                    }).catch(function () {
                        fallbackCopy(text);
                        showCopyToast(text);
                    });
                    return;
                }
                fallbackCopy(text);
                showCopyToast(text);
            });
        });
    })();

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

    (function initSettingsMaxFormsModal() {
        var modal = document.querySelector('[data-module="c-settingsFormLimitModal01"]');
        var openBtn = document.querySelector('[data-target="settings-max-forms-open"]');
        if (!modal || !openBtn) return;

        function openModal() {
            modal.hidden = false;
            document.body.classList.add('is-settings-max-forms-modal-open');
            var input = modal.querySelector('#max_forms_modal');
            if (input) {
                input.focus();
                if (typeof input.select === 'function') {
                    input.select();
                }
            }
        }

        function closeModal() {
            modal.hidden = true;
            document.body.classList.remove('is-settings-max-forms-modal-open');
            openBtn.focus();
        }

        openBtn.addEventListener('click', function () {
            openModal();
        });

        modal.querySelectorAll('[data-target="settings-max-forms-close"]').forEach(function (el) {
            el.addEventListener('click', function () {
                closeModal();
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.hidden) {
                closeModal();
            }
        });
    })();

    var list = document.querySelector('[data-target="m-formBuilder01__fields"]');
    if (!list) return;

    var builderRoot = list.closest('[data-module="m-formBuilder01"]');
    var template = document.querySelector('[data-target="m-formBuilder01__fieldTemplate"]');
    var addBtn = builderRoot ? builderRoot.querySelector('[data-target="m-formBuilder01__add"]') : document.querySelector('[data-target="m-formBuilder01__add"]');
    var typeModal = document.querySelector('[data-target="m-formBuilder01__typeModal"]');
    var descriptionTemplate = document.querySelector('[data-target="m-formBuilder01__descriptionTemplate"]');
    var descriptionEditors = document.querySelectorAll('[data-description-editor]');
    var mainForm = list.closest('form');
    var dirtyActions = mainForm ? mainForm.querySelector('[data-target="p-formEdit01__dirtyActions"]') : null;
    var dirtyCancel = mainForm ? mainForm.querySelector('[data-target="p-formEdit01__dirtyCancel"]') : null;
    var initialFormSnapshot = '';
    var dirtyStateRaf = 0;

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

    function setFieldType(li, typeValue, typeLabel) {
        if (!li) return;
        var typeInput = li.querySelector('[data-target="c-formEditor__type"]');
        var typeLabelEl = li.querySelector('[data-target="c-formEditor__typeLabel"]');
        var summaryTypeEl = li.querySelector('[data-target="c-formEditor__summaryType"]');
        if (typeInput) {
            typeInput.value = typeValue || 'text_multi';
            typeInput.setAttribute('data-type-label', typeLabel || '');
        }
        if (typeLabelEl) {
            typeLabelEl.textContent = typeLabel || '';
        }
        if (summaryTypeEl) {
            summaryTypeEl.textContent = typeLabel || '';
        }
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

    function syncInputModuleState(module) {
        if (!module) return;
        var control = module.querySelector('input, textarea, select');
        if (!control) return;
        var value = typeof control.value === 'string' ? control.value.trim() : '';
        var isEmpty = value === '';
        module.classList.toggle('is-empty', isEmpty);
        module.classList.toggle('is-filled', !isEmpty);
    }

    function syncInputModuleStates(root) {
        (root || document).querySelectorAll('[data-module="m-input01"]').forEach(function (module) {
            syncInputModuleState(module);
        });
    }

    function serializeFormSnapshot(form) {
        if (!form) return '';
        var pairs = [];
        new FormData(form).forEach(function (value, key) {
            pairs.push(encodeURIComponent(key) + '=' + encodeURIComponent(typeof value === 'string' ? value : String(value)));
        });
        return pairs.join('&');
    }

    function updateDirtyState() {
        if (!mainForm || !dirtyActions) return;
        var isDirty = serializeFormSnapshot(mainForm) !== initialFormSnapshot;
        dirtyActions.hidden = !isDirty;
        document.body.classList.toggle('is-form-edit-dirty', isDirty);
    }

    function queueDirtyState() {
        if (!mainForm || !dirtyActions || dirtyStateRaf) return;
        dirtyStateRaf = window.requestAnimationFrame(function () {
            dirtyStateRaf = 0;
            updateDirtyState();
        });
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

    function updateDescriptionSummary(block) {
        if (!block) return;
        var labelInput = block.querySelector('[data-target="c-descriptionEditor__labelInput"]');
        var positionSelect = block.querySelector('.fb-description-position');
        var summaryLabel = block.querySelector('[data-target="c-descriptionEditor__summaryLabel"]');
        var summaryPosition = block.querySelector('[data-target="c-descriptionEditor__summaryPosition"]');
        if (summaryLabel) {
            var value = labelInput ? labelInput.value.trim() : '';
            summaryLabel.textContent = value;
            summaryLabel.hidden = !value;
        }
        if (summaryPosition && positionSelect) {
            var selected = positionSelect.selectedOptions && positionSelect.selectedOptions[0];
            summaryPosition.textContent = selected ? selected.textContent.trim() : '先頭';
        }
    }

    /** 項目設定・説明文の .c-formEditor__item 共通: 公開非表示なら is-editor-item-hidden + 本文 inert（c-formEditorList01 の SCSS と対。disabled は付けない）。 */
    function syncFormEditorItemPublicHidden(item) {
        if (!item || !item.classList.contains('c-formEditor__item')) return;
        var visibilityToggle = item.querySelector(
            '.c-formEditorSummary01__visibility [data-name="visible"], .c-descriptionEditorSummary01__visibility [data-name="visible"]'
        );
        var body = item.querySelector('.c-formEditor__itemBody');
        if (!visibilityToggle || !body) return;
        var off = !visibilityToggle.checked;
        item.classList.toggle('is-editor-item-hidden', off);
        if ('inert' in HTMLElement.prototype) {
            body.inert = off;
        }
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
            var idInput = block.querySelector('[data-name="id"]');
            var blockId = idInput ? (idInput.value || (prefix.replace(/_blocks$/, '') + '-' + i)) : (prefix.replace(/_blocks$/, '') + '-' + i);
            var labelInput = block.querySelector('[data-target="c-descriptionEditor__labelInput"]');
            var labelLabel = labelInput ? labelInput.closest('[data-module="m-inputSet01"]').querySelector('.block.is-title label') : null;
            var positionSelect = block.querySelector('.fb-description-position');
            var positionLabel = positionSelect ? positionSelect.closest('[data-module="m-inputSet01"]').querySelector('.block.is-title label') : null;
            var textarea = block.querySelector('[data-target="c-htmlInputEditor01__textarea"]');
            var textareaLabel = textarea ? textarea.closest('[data-module="m-inputSet01"]').querySelector('.block.is-title label') : null;
            if (labelInput) {
                labelInput.id = blockId + '-label';
                if (labelLabel) labelLabel.setAttribute('for', labelInput.id);
            }
            if (positionSelect) {
                positionSelect.id = blockId + '-position';
                if (positionLabel) positionLabel.setAttribute('for', positionSelect.id);
            }
            if (textarea) {
                textarea.id = blockId + '-html';
                if (textareaLabel) textareaLabel.setAttribute('for', textarea.id);
            }
            updateDescriptionSummary(block);
            syncFormEditorItemPublicHidden(block);
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

    function refreshDescriptionSortable(editor) {
        var descriptionList = getDescriptionList(editor);
        if (!descriptionList || descriptionList.dataset.fbSortable !== '1' || typeof jQuery === 'undefined') return;
        try {
            jQuery(descriptionList).sortable('refresh');
        } catch (e1) {}
    }

    function initDescriptionSortable(editor) {
        var descriptionList = getDescriptionList(editor);
        if (!descriptionList || typeof jQuery === 'undefined' || !jQuery.fn.sortable) return;
        if (descriptionList.dataset.fbSortable === '1') {
            refreshDescriptionSortable(editor);
            return;
        }
        jQuery(descriptionList).sortable({
            handle: '[data-target="c-descriptionEditor__dragHandle"]',
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
                reindexDescriptionBlocks(editor);
            }
        });
        descriptionList.dataset.fbSortable = '1';
    }

    function bindDescriptionBlock(editor, block) {
        var remove = block.querySelector('[data-target="fb-description-remove"]');
        var select = block.querySelector('.fb-description-position');
        var labelInput = block.querySelector('[data-target="c-descriptionEditor__labelInput"]');
        var visibilityToggle = block.querySelector('[data-name="visible"]');
        var header = block.querySelector('.c-formEditor__itemHeader');
        bindHtmlToggleEditor(block.querySelector('[data-html-toggle-editor]'));
        updateDescriptionSummary(block);
        if (header && header.getAttribute('data-accordion-bound') !== '1') {
            header.addEventListener('click', function (e) {
                if (e.target.closest('.area.is-move') || e.target.closest('.area.is-delete') || e.target.closest('.c-descriptionEditorSummary01__visibility')) {
                    return;
                }
                block.classList.toggle('is-collapsed');
            });
            header.setAttribute('data-accordion-bound', '1');
        }
        if (remove) {
            remove.addEventListener('click', function () {
                block.parentNode.removeChild(block);
                reindexDescriptionBlocks(editor);
                refreshDescriptionPositionOptions(editor);
                refreshDescriptionSortable(editor);
            });
        }
        if (select) {
            select.addEventListener('change', function () {
                select.setAttribute('data-value', select.value);
            });
        }
        if (labelInput) {
            labelInput.addEventListener('input', function () {
                updateDescriptionSummary(block);
            });
        }
        if (visibilityToggle) {
            visibilityToggle.addEventListener('change', function () {
                syncFormEditorItemPublicHidden(block);
                var label = visibilityToggle.closest('.c-descriptionEditorSummary01__visibility');
                if (label) {
                    label.title = visibilityToggle.checked ? '表示中' : '非表示';
                }
            });
        }
        syncFormEditorItemPublicHidden(block);
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
                allow = option.value === '' || option.value === FB_REGEX_CUSTOM || option.value === FB_REGEX_CHECKBOX_LIMIT;
            } else if (typeValue === 'radio' || typeValue === 'select') {
                allow = option.value === '';
            }
            option.hidden = !allow;
            option.disabled = !allow;
        });
        if (typeValue === 'checkbox') {
            if (select.value !== '' && select.value !== FB_REGEX_CUSTOM && select.value !== FB_REGEX_CHECKBOX_LIMIT) {
                select.value = '';
            }
        } else if (typeValue === 'radio' || typeValue === 'select') {
            select.value = '';
        } else if (select.value === FB_REGEX_CHECKBOX_LIMIT) {
            select.value = '';
        }
    }

    function updateFieldSummary(li) {
        var labelInp = li.querySelector('[data-target="c-formEditor__labelInput"]');
        var typeSel = li.querySelector('[data-target="c-formEditor__type"]');
        var requiredToggle = li.querySelector('[data-target="c-formEditor__required"]');
        var visibilityToggle = li.querySelector('[data-name="visible"]');
        var regexKind = li.querySelector('[data-target="c-formEditor__regexKind"]');
        var regexCustom = li.querySelector('[data-target="c-formEditor__regexCustomInput"]');
        var labelEl = li.querySelector('[data-target="c-formEditor__summaryLabel"]');
        var typeEl = li.querySelector('[data-target="c-formEditor__summaryType"]');
        var requiredEl = li.querySelector('[data-target="c-formEditor__summaryRequired"]');
        var regexWrapEl = li.querySelector('[data-target="c-formEditor__summaryRegexWrap"]');
        var regexEl = li.querySelector('[data-target="c-formEditor__summaryRegex"]');
        var visibilityLabel = li.querySelector('.c-formEditorSummary01__visibility');
        if (!labelEl) return;
        labelEl.textContent = (labelInp && labelInp.value.trim()) ? labelInp.value.trim() : '（無題）';
        if (visibilityLabel && visibilityToggle) {
            visibilityLabel.title = visibilityToggle.checked ? '表示中' : '非表示';
        }
        if (!typeEl || !requiredEl || !regexEl || !regexWrapEl) return;
        var typeValue = typeSel ? typeSel.value : '';
        var typeLabel = typeSel ? (typeSel.getAttribute('data-type-label') || '') : '';
        typeEl.textContent = typeLabel || '—';
        var isRequired = !!(requiredToggle && requiredToggle.checked);
        requiredEl.textContent = '必須';
        requiredEl.hidden = !isRequired;
        var regexText = 'なし';
        if (!isRequired || !regexKind || regexKind.value === '' || typeValue === 'radio' || typeValue === 'select') {
            regexText = 'なし';
        } else if (regexKind.value === FB_REGEX_CHECKBOX_LIMIT) {
            regexText = '選択数';
        } else if (regexKind.value === FB_REGEX_CUSTOM) {
            if (!regexCustom || !regexCustom.value.trim()) {
                regexText = 'なし';
                regexEl.textContent = regexText;
                regexWrapEl.hidden = !isRequired || regexText === 'なし';
                return;
            }
            regexText = 'カスタム';
        } else {
            var regexOpt = regexKind.selectedOptions && regexKind.selectedOptions[0];
            regexText = regexOpt ? regexOpt.textContent.trim() : 'なし';
        }
        regexEl.textContent = regexText;
        regexWrapEl.hidden = !isRequired || regexText === 'なし';
    }

    function bindAccordion(li) {
        var header = li.querySelector('.c-formEditor__itemHeader');
        if (!header) return;

        header.addEventListener('click', function (e) {
            if (e.target.closest('.area.is-move') || e.target.closest('.area.is-delete') || e.target.closest('.c-formEditorSummary01__visibility')) {
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
            syncFormEditorItemPublicHidden(li);
        });
        syncAllRegexRows();
        refreshAllDescriptionPositionOptions();
        queueDirtyState();
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

    function refreshChoiceSortable(box) {
        var list = box.querySelector('[data-target="c-formEditor__choiceListContents"]');
        if (!list || list.dataset.fbSortable !== '1' || typeof jQuery === 'undefined') return;
        try {
            jQuery(list).sortable('refresh');
        } catch (e1) {}
    }

    function initChoiceSortable(box) {
        var list = box.querySelector('[data-target="c-formEditor__choiceListContents"]');
        if (!list || typeof jQuery === 'undefined' || !jQuery.fn.sortable) return;
        if (list.dataset.fbSortable === '1') {
            refreshChoiceSortable(box);
            return;
        }
        jQuery(list).sortable({
            handle: '[data-target="c-formEditor__choiceDragHandle"]',
            axis: 'y',
            items: '> .area.is-contents:not([data-target="c-formEditor__choiceControls"])',
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
                syncChoiceEditor(box);
            }
        });
        list.dataset.fbSortable = '1';
    }

    function createChoiceItem(value) {
        var area = document.createElement('div');
        area.className = 'area is-contents';
        var row = document.createElement('div');
        row.className = 'c-formEditor__choiceItem';
        row.setAttribute('data-choice-item', '');
        row.innerHTML =
            '<div class="wrapper">' +
                '<div class="block is-contents">' +
                    '<div class="area is-move">' +
                        '<div class="box is-handle ui-sortable-handle" title="ドラッグして並べ替え">' +
                            '<p data-module="m-dragHandle01" data-target="c-formEditor__choiceDragHandle">' +
                        '<svg width="14" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor"><circle cx="9" cy="6" r="2"/><circle cx="15" cy="6" r="2"/><circle cx="9" cy="12" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="9" cy="18" r="2"/><circle cx="15" cy="18" r="2"/></svg>' +
                            '</p>' +
                        '</div>' +
                    '</div>' +
                    '<div class="c-formEditor__child__field">' +
                        '<div class="wrapper">' +
                            '<div class="block is-contents">' +
                                '<div class="area is-field"><div data-module="m-input01"><input type="text" data-target="c-formEditor__choiceInput" placeholder="選択肢"></div></div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<button type="button" data-module="m-deleteBtn01" data-target="c-formEditor__choiceRemove" aria-label="削除" title="削除">' +
                        '<svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"></path><path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"></path><path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"></path><path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"></path></svg>' +
                    '</button>' +
                '</div>' +
            '</div>';
        area.appendChild(row);
        var input = row.querySelector('[data-target="c-formEditor__choiceInput"]');
        if (input) {
            input.value = value || '';
        }
        return area;
    }

    function bindChoiceItem(item, box) {
        var input = item.querySelector('[data-target="c-formEditor__choiceInput"]');
        var remove = item.querySelector('[data-target="c-formEditor__choiceRemove"]');
        if (input) {
            input.addEventListener('input', function () {
                syncChoiceEditor(box);
            });
            input.addEventListener('change', function () {
                syncChoiceEditor(box);
            });
        }
        if (remove) {
            remove.addEventListener('click', function () {
                var area = item.parentNode;
                if (!area || !area.parentNode) return;
                area.parentNode.removeChild(area);
                syncChoiceEditor(box);
                refreshChoiceSortable(box);
            });
        }
    }

    function bindChoiceEditor(li) {
        li.querySelectorAll('[data-choice-editor]').forEach(function (box) {
            var list = box.querySelector('[data-target="c-formEditor__choiceListContents"]');
            var add = box.querySelector('[data-target="c-formEditor__choiceAdd"]');
            if (!list || !add) return;
            list.querySelectorAll('[data-choice-item]').forEach(function (item) {
                bindChoiceItem(item, box);
            });
            initChoiceSortable(box);
            add.addEventListener('click', function () {
                var item = createChoiceItem('');
                var controls = box.querySelector('[data-target="c-formEditor__choiceControls"]');
                if (controls) {
                    list.insertBefore(item, controls);
                } else {
                    list.appendChild(item);
                }
                bindChoiceItem(item.querySelector('[data-choice-item]'), box);
                syncChoiceEditor(box);
                refreshChoiceSortable(box);
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
        var area = document.createElement('div');
        area.className = 'area is-contents';
        var item = document.createElement('div');
        item.className = 'c-formEditor__textMultiItem';
        item.setAttribute('data-text-multi-item', '');
        item.innerHTML =
            '<div class="wrapper">' +
                '<div class="block is-contents">' +
                    '<div class="c-formEditor__child__field">' +
                        '<div class="wrapper">' +
                            '<div class="block is-contents">' +
                                '<div class="area is-title"><label data-module="m-labelSmallTitle01">表示</label></div>' +
                                '<div class="area is-field"><div data-module="m-input01"><input type="text" data-target="c-formEditor__textMultiLabelInput"></div></div>' +
                            '</div>' +
                            '<div class="block is-contents">' +
                                '<div class="area is-title"><label data-module="m-labelSmallTitle01">入力例</label></div>' +
                                '<div class="area is-field"><div data-module="m-input01"><input type="text" data-target="c-formEditor__textMultiPlaceholderInput"></div></div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<button type="button" data-module="m-deleteBtn01" data-target="c-formEditor__textMultiRemove" aria-label="削除" title="削除">' +
                        '<svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6"/><path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/></svg>' +
                    '</button>' +
                '</div>' +
            '</div>';
        area.appendChild(item);
        var labelInput = item.querySelector('[data-target="c-formEditor__textMultiLabelInput"]');
        var placeholderInput = item.querySelector('[data-target="c-formEditor__textMultiPlaceholderInput"]');
        if (labelInput) {
            labelInput.value = data && typeof data.label === 'string' ? data.label : '';
        }
        if (placeholderInput) {
            placeholderInput.value = data && typeof data.placeholder === 'string' ? data.placeholder : '';
        }
        return area;
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
                var area = item.parentNode;
                if (!area) return;
                var list = area.parentNode;
                if (!list) return;
                if (list.querySelectorAll('[data-text-multi-item]').length <= 1) {
                    alert('入力欄は1つ以上必要です。');
                    return;
                }
                list.removeChild(area);
                syncTextMultiEditor(box);
            });
        }
    }

    function bindTextMultiEditor(li) {
        li.querySelectorAll('[data-text-multi-editor]').forEach(function (box) {
            var list = box.querySelector('[data-target="c-formEditor__textMultiListContents"]');
            var add = box.querySelector('[data-target="c-formEditor__textMultiAdd"]');
            if (!list || !add) return;
            list.querySelectorAll('[data-text-multi-item]').forEach(function (item) {
                bindTextMultiItem(item, box);
            });
            add.addEventListener('click', function () {
                var item = createTextMultiItem();
                var controls = box.querySelector('[data-target="c-formEditor__textMultiControls"]');
                if (controls) {
                    list.insertBefore(item, controls);
                } else {
                    list.appendChild(item);
                }
                bindTextMultiItem(item.querySelector('[data-text-multi-item]'), box);
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
        var visibilityToggle = li.querySelector('[data-name="visible"]');
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
                var hideRegex = false;
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
        if (visibilityToggle) {
            visibilityToggle.addEventListener('change', function () {
                syncFormEditorItemPublicHidden(li);
                updateFieldSummary(li);
            });
        }
        var rm = li.querySelector('[data-target="c-formEditor__remove"]');
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
        syncFormEditorItemPublicHidden(li);
        updateFieldSummary(li);
        syncInputModuleStates(li);
    }

    function applyFieldPreset(li, preset) {
        if (!preset) return;
        var labelInput = li.querySelector('[data-name="label"]');
        var typeInput = li.querySelector('[data-name="type"]');
        var requiredToggle = li.querySelector('[data-name="required"]');
        var optionsSource = li.querySelector('[data-name="options"]');
        var choiceList = li.querySelector('[data-target="c-formEditor__choiceListContents"]');

        if (labelInput && typeof preset.label === 'string') {
            labelInput.value = preset.label;
        }
        if (typeInput && typeof preset.type === 'string') {
            setFieldType(li, preset.type, preset.typeLabel || '');
        }
        if (requiredToggle) {
            requiredToggle.checked = !!preset.required;
        }
        if (choiceList && Array.isArray(preset.options)) {
            var controls = li.querySelector('[data-target="c-formEditor__choiceControls"]');
            choiceList.querySelectorAll('[data-choice-item]').forEach(function (item) {
                var area = item.parentNode;
                if (area && area.parentNode === choiceList) {
                    choiceList.removeChild(area);
                }
            });
            preset.options.forEach(function (option) {
                var item = createChoiceItem(option);
                if (controls) {
                    choiceList.insertBefore(item, controls);
                } else {
                    choiceList.appendChild(item);
                }
            });
        }
        if (optionsSource && Array.isArray(preset.options)) {
            optionsSource.value = preset.options.join('\n');
        }
    }

    function createFieldPreset(typeValue, typeLabel) {
        var preset = {
            type: typeValue || 'text_multi',
            typeLabel: typeLabel || '',
            label: '',
            required: false
        };
        if (typeValue === 'radio' || typeValue === 'checkbox' || typeValue === 'select') {
            preset.options = [''];
        }
        return preset;
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

    function openTypeModal() {
        if (!typeModal) return;
        typeModal.hidden = false;
        document.body.classList.add('is-field-type-modal-open');
        var firstOption = typeModal.querySelector('[data-target="m-formBuilder01__typeOption"]');
        if (firstOption) {
            firstOption.focus();
        }
    }

    function closeTypeModal() {
        if (!typeModal) return;
        typeModal.hidden = true;
        document.body.classList.remove('is-field-type-modal-open');
        if (addBtn) {
            addBtn.focus();
        }
    }

    getFieldItems().forEach(bind);
    descriptionEditors.forEach(function (editor) {
        var descriptionList = getDescriptionList(editor);
        var addButton = editor.querySelector('[data-target="m-formBuilder01__add"]');
        if (descriptionList) {
            descriptionList.querySelectorAll('[data-description-block]').forEach(function (block) {
                bindDescriptionBlock(editor, block);
            });
            initDescriptionSortable(editor);
            reindexDescriptionBlocks(editor);
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
                reindexDescriptionBlocks(editor);
                syncInputModuleStates(block);
                refreshDescriptionSortable(editor);
                var textarea = block.querySelector('[data-target="c-htmlInputEditor01__textarea"]');
                if (textarea) textarea.focus();
            });
        }
    });

    if (mainForm) {
        mainForm.addEventListener('submit', function () {
            syncAllRegexRows();
            list.querySelectorAll('[data-choice-editor]').forEach(syncChoiceEditor);
            list.querySelectorAll('[data-text-multi-editor]').forEach(syncTextMultiEditor);
            descriptionEditors.forEach(function (editor) {
                reindexDescriptionBlocks(editor);
            });
            if (dirtyActions) {
                dirtyActions.hidden = true;
            }
            document.body.classList.remove('is-form-edit-dirty');
        });
    }

    if (typeModal) {
        typeModal.querySelectorAll('[data-target="m-formBuilder01__typeModalClose"]').forEach(function (button) {
            button.addEventListener('click', function () {
                closeTypeModal();
            });
        });
        typeModal.querySelectorAll('[data-target="m-formBuilder01__typeOption"]').forEach(function (button) {
            button.addEventListener('click', function () {
                var typeValue = button.getAttribute('data-field-type') || 'text_multi';
                var typeLabel = button.getAttribute('data-field-type-label') || '';
                var clone = appendField(createFieldPreset(typeValue, typeLabel));
                closeTypeModal();
                var labelInput = clone ? clone.querySelector('[data-target="c-formEditor__labelInput"]') : null;
                if (labelInput) {
                    labelInput.focus();
                }
            });
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !typeModal.hidden) {
                closeTypeModal();
            }
        });
    }

    if (addBtn && template) {
        addBtn.addEventListener('click', function () {
            openTypeModal();
        });
    }

    document.addEventListener('input', function (event) {
        var module = event.target.closest ? event.target.closest('[data-module="m-input01"]') : null;
        if (module) {
            syncInputModuleState(module);
        }
        if (mainForm && mainForm.contains(event.target)) {
            queueDirtyState();
        }
    });

    document.addEventListener('change', function (event) {
        var module = event.target.closest ? event.target.closest('[data-module="m-input01"]') : null;
        if (module) {
            syncInputModuleState(module);
        }
        if (mainForm && mainForm.contains(event.target)) {
            queueDirtyState();
        }
    });

    reindex();
    descriptionEditors.forEach(function (editor) {
        reindexDescriptionBlocks(editor);
        refreshDescriptionPositionOptions(editor);
    });
    syncInputModuleStates(document);
    if (mainForm && dirtyActions) {
        initialFormSnapshot = serializeFormSnapshot(mainForm);
        updateDirtyState();
        if (dirtyCancel) {
            dirtyCancel.addEventListener('click', function () {
                window.location.reload();
            });
        }
        new MutationObserver(function () {
            queueDirtyState();
        }).observe(mainForm, { childList: true, subtree: true });
    }

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
