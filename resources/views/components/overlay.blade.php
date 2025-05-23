<div class="layer overlay" id="js-overlay">
    <div class="table table-overlay">
        <div class="cell cell-overlay">
            <div class="overlay-hold">
                <div class="overlay-block popup" id="js-overlay-popup"></div>
                <div class="overlay-block info message" id="js-overlay-message">
                    <div class="overlay-block-item text-msg">
                        <span class="text js-text"></span>
                    </div>
                    <div class="overlay-block-item buttons">
                        <x-cms::form.button bg="icons.done" class="btn-form btn-normal js-btn-close" type="button">{{ __('main.action_ok') }}</x-cms::form.button>
                    </div>
                </div>
                <div class="overlay-block info prompt" id="js-overlay-prompt">
                    <div class="overlay-block-item text-msg">
                        <span class="text js-text"></span>
                    </div>
                    <div class="overlay-block-item text-input">
                        <x-cms::form.string name="js_prompt" />
                    </div>
                    <div class="overlay-block-item buttons">
                        <x-cms::form.button bg="icons.done" class="btn-form btn-normal js-btn-confirm" type="button">{{ __('main.action_confirm') }}</x-cms::form.button>
                        <x-cms::form.button bg="icons.unavailable" class="btn-form btn-normal js-btn-close" type="button">{{ __('main.action_cancel') }}</x-cms::form.button>
                    </div>
                </div>
                <div class="overlay-block info confirm" id="js-overlay-confirm">
                    <div class="overlay-block-item text-msg">
                        <span class="text js-text"></span>
                    </div>
                    <div class="overlay-block-item buttons">
                        <x-cms::form.button bg="icons.done" class="btn-form btn-normal js-btn-confirm" type="button">{{ __('main.action_confirm') }}</x-cms::form.button>
                        <x-cms::form.button bg="icons.unavailable" class="btn-form btn-normal js-btn-close" type="button">{{ __('main.action_cancel') }}</x-cms::form.button>
                    </div>
                </div>
                <div class="overlay-block info confirm" id="js-overlay-confirm-delete">
                    <div class="overlay-block-item text-msg">
                        <span class="text js-text">{{ __('main.confirmation_delete') }}</span>
                    </div>
                    <div class="overlay-block-item buttons">
                        <x-cms::form.button bg="icons.remove" class="btn-form btn-warning js-btn-confirm" type="button">{{ __('main.action_delete') }}</x-cms::form.button>
                        <x-cms::form.button bg="icons.unavailable" class="btn-form btn-normal js-btn-close" type="button">{{ __('main.action_cancel') }}</x-cms::form.button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="layer animation" id="js-overlay-animation">
    <div class="table table-overlay">
        <div class="cell cell-overlay">
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
