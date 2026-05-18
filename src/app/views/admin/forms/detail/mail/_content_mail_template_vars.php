<?php /** c-formBlock01 直下 .area.is-field の内側: 管理者通知 — 使用可能な変数一覧 */ ?>
    <?php admin_m_input_set01_open(); ?>
    <div class="area is-title">
        <?php admin_m_bold_title01(e('使用可能な変数')); ?>
    </div>
    <div class="area is-field">
        <div class="c-copyTagList01">
            <div class="c-copyTagList01__items">
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{field_key}}"><code>{{field_key}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__all__}}"><code>{{__all__}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__form_title__}}"><code>{{__form_title__}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__submitted_at__}}"><code>{{__submitted_at__}}</code></button>
                <button type="button" class="c-copyTagList01__tag"
                    data-copy-text="{{__ip__}}"><code>{{__ip__}}</code></button>
            </div>
        </div>
    </div>
    <?php admin_m_input_set01_close(); ?>
