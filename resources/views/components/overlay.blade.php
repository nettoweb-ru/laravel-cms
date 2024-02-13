<div class="layer overlay" id="js-overlay">
    <div class="table table-overlay">
        <div class="cell table-overlay">
            <div class="overlay-hold">
                <div class="block popup" id="js-overlay-popup"></div>
                <div class="block info message" id="js-overlay-message">
                    <div class="block-info text-msg">
                        <span class="text js-text"></span>
                    </div>
                    <div class="block-info buttons">
                        <x-cms::form.button bg="icons.done" class="btn-form btn-normal js-btn-close" type="button">{{ __('cms::main.action_ok') }}</x-cms::form.button>
                    </div>
                </div>
                <div class="block info prompt" id="js-overlay-prompt">
                    <div class="block-info text-msg">
                        <span class="text js-text"></span>
                    </div>
                    <div class="block-info text-input">
                        <label>
                            <x-cms::form.string name="js_prompt" iclass="js-overlay-prompt-value" />
                        </label>
                    </div>
                    <div class="block-info buttons">
                        <x-cms::form.button bg="icons.done" class="btn-form btn-normal js-btn-confirm" type="button">{{ __('cms::main.action_confirm') }}</x-cms::form.button>
                        <x-cms::form.button bg="icons.unavailable" class="btn-form btn-normal js-btn-close" type="button">{{ __('cms::main.action_cancel') }}</x-cms::form.button>
                    </div>
                </div>
                <div class="block info confirm" id="js-overlay-confirm">
                    <div class="block-info text-msg">
                        <span class="text js-text"></span>
                    </div>
                    <div class="block-info buttons">
                        <x-cms::form.button bg="icons.done" class="btn-form btn-normal js-btn-confirm" type="button">{{ __('cms::main.action_confirm') }}</x-cms::form.button>
                        <x-cms::form.button bg="icons.unavailable" class="btn-form btn-normal js-btn-close" type="button">{{ __('cms::main.action_cancel') }}</x-cms::form.button>
                    </div>
                </div>
                <div class="block info confirm" id="js-overlay-confirm-delete">
                    <div class="block-info text-msg">
                        <span class="text js-text">{{ __('cms::main.confirmation_delete') }}</span>
                    </div>
                    <div class="block-info buttons">
                        <x-cms::form.button bg="icons.remove" class="btn-form btn-warning js-btn-confirm" type="button">{{ __('cms::main.action_delete') }}</x-cms::form.button>
                        <x-cms::form.button bg="icons.unavailable" class="btn-form btn-normal js-btn-close" type="button">{{ __('cms::main.action_cancel') }}</x-cms::form.button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="layer animation" id="js-overlay-animation">
    <div class="table table-overlay">
        <div class="cell table-overlay">
            <div class="overlay-hold">
                <div class="loading">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</div>
