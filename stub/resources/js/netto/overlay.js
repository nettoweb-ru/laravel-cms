window.Overlay = {
    objects: {
        overlay: {
            container: null,
            visible: false
        },
        popup: {
            container: null,
            visible: false
        },
        animation: {
            container: null,
            visible: false
        },
        message: {
            container: null,
            visible: false,
            btnClose: null,
            text: ''
        },
        prompt: {
            container: null,
            visible: false,
            text: null,
            input: null,
            btnClose: null,
            btnConfirm: null
        },
        confirm: {
            container: null,
            visible: false,
            text: null,
            btnClose: null,
            btnConfirm: null,
        },
        confirmDelete: {
            container: null,
            visible: false,
            btnClose: null,
            btnConfirm: null,
        }
    },

    hideAll: function() {
        this.hidePopup()
        this.hideAnimation()
        this.hideMessage()
        this.hideConfirm()
        this.hideConfirmDelete();
        this.hidePrompt()
    },

    hideAnimation: function() {
        if (!this.objects.animation.visible) {
            return
        }

        this.objects.animation.container.hide()
        this.objects.animation.visible = false
    },

    hideConfirm: function() {
        if (!this.objects.confirm.visible) {
            return
        }

        this.objects.confirm.btnConfirm.off('click.netto')
        this.objects.confirm.btnClose.off('click.netto')

        this.objects.confirm.text.html('')
        this.objects.confirm.container.hide()
        this.objects.confirm.visible = false
    },

    hideConfirmDelete: function() {
        if (!this.objects.confirmDelete.visible) {
            return
        }

        this.objects.confirmDelete.btnConfirm.off('click.netto')
        this.objects.confirmDelete.btnClose.off('click.netto')

        this.objects.confirmDelete.container.hide()
        this.objects.confirmDelete.visible = false
    },

    hideMessage: function() {
        if (!this.objects.message.visible) {
            return
        }

        this.objects.message.btnClose.off('click.netto')
        this.objects.message.text.html('')
        this.objects.message.container.hide()
        this.objects.message.visible = false
    },

    hideOverlay: function() {
        if (!this.objects.overlay.visible) {
            return
        }

        this.hideAll()

        this.objects.overlay.container.hide()
        this.objects.overlay.visible = false
    },

    hidePopup: function() {
        if (!this.objects.popup.visible) {
            return
        }

        this.objects.popup.container.html('').hide()
        this.objects.popup.visible = false
    },

    hidePrompt: function() {
        if (!this.objects.prompt.visible) {
            return
        }

        this.objects.prompt.btnConfirm.off('click.netto')
        this.objects.prompt.btnClose.off('click.netto')

        this.objects.prompt.input.val('')
        this.objects.prompt.text.html('')

        this.objects.prompt.container.hide()
        this.objects.prompt.visible = false
    },

    init: function() {
        this.initObjects()
        this.initPopupMessages()
        this.initAnimatedLinks()
    },

    initAnimatedLinks: function() {
        let self = this
        $('.js-animated-link').click(function() {
            self.initRedirect()
        })
    },

    initObjects: function() {
        let html = $('html')
        this.objects.animation.container = html.find('#js-overlay-animation')

        this.objects.overlay.container = html.find('#js-overlay')
        this.objects.popup.container = this.objects.overlay.container.find('#js-overlay-popup')

        this.objects.message.container = this.objects.overlay.container.find('#js-overlay-message')
        this.objects.message.btnClose = this.objects.message.container.find('.js-btn-close')
        this.objects.message.text = this.objects.message.container.find('.js-text')

        this.objects.prompt.container = this.objects.overlay.container.find('#js-overlay-prompt')
        this.objects.prompt.text = this.objects.prompt.container.find('.js-text')
        this.objects.prompt.input = this.objects.prompt.container.find('#js_prompt')
        this.objects.prompt.btnClose = this.objects.prompt.container.find('.js-btn-close')
        this.objects.prompt.btnConfirm = this.objects.prompt.container.find('.js-btn-confirm')

        this.objects.confirm.container = this.objects.overlay.container.find('#js-overlay-confirm')
        this.objects.confirm.btnClose = this.objects.confirm.container.find('.js-btn-close')
        this.objects.confirm.btnConfirm = this.objects.confirm.container.find('.js-btn-confirm')
        this.objects.confirm.text = this.objects.confirm.container.find('.js-text')

        this.objects.confirmDelete.container = this.objects.overlay.container.find('#js-overlay-confirm-delete')
        this.objects.confirmDelete.btnClose = this.objects.confirmDelete.container.find('.js-btn-close')
        this.objects.confirmDelete.btnConfirm = this.objects.confirmDelete.container.find('.js-btn-confirm')
    },

    initPopupMessages: function() {
        let message = '';
        $('.js-flash-message').each(function() {
            message += ($(this).html() + '<br />')
            $(this).remove()
        })

        if (message.length) {
            this.showMessage(message)
        }
    },

    initRedirect: function() {
        this.showAnimation()

        let self = this
        $(window).one('unload.netto', function() {
            self.hideOverlay()
        })
    },

    redirect: function(url) {
        this.initRedirect()
        window.location.href = url
    },

    showAnimation: function() {
        if (this.objects.animation.visible) {
            return
        }

        this.objects.animation.container.show()
        this.objects.animation.visible = true

        this.objects.animation.container.attr('tabindex', -1).focus()
    },

    showConfirm: async function(message) {
        this.hideAll()

        let self = this

        this.objects.confirm.text.html(message)

        this.objects.confirm.container.show()
        this.objects.confirm.visible = true

        this.showOverlay()
        this.objects.confirm.container.attr('tabindex', -1).focus()

        return new Promise(function(resolve){
            self.objects.confirm.btnConfirm.one('click.netto', function() {
                resolve(true)
                self.hideOverlay()
            })

            self.objects.confirm.btnClose.one('click.netto', function() {
                resolve(false)
                self.hideOverlay()
            })
        })
    },

    showConfirmDelete: async function() {
        this.hideAll()

        let self = this

        this.objects.confirmDelete.container.show()
        this.objects.confirmDelete.visible = true

        this.showOverlay()
        this.objects.confirmDelete.container.attr('tabindex', -1).focus()

        return new Promise(function(resolve){
            self.objects.confirmDelete.btnConfirm.one('click.netto', function() {
                resolve(true)
                self.hideOverlay()
            })

            self.objects.confirmDelete.btnClose.one('click.netto', function() {
                resolve(false)
                self.hideOverlay()
            })
        })
    },

    showOverlay: function() {
        if (this.objects.overlay.visible) {
            return
        }

        this.objects.overlay.container.show()
        this.objects.overlay.visible = true
    },

    showMessage: function(text) {
        this.hideAll()

        let self = this

        this.objects.message.btnClose.one('click.netto', function() {
            self.hideOverlay()
        })

        this.objects.message.text.html(text)
        this.objects.message.container.show()
        this.objects.message.visible = true

        this.showOverlay()
        this.objects.message.container.attr('tabindex', -1).focus()
    },

    showPopup: function(modal) {
        if (this.objects.popup.visible) {
            return
        }

        this.hideAll()

        if (!modal) {
            let self = this
            this.objects.overlay.container.one('click.netto', function() {
                self.hideOverlay()
            })
        }

        this.objects.popup.container.show()
        this.objects.popup.visible = true

        this.showOverlay()
        this.objects.popup.container.attr('tabindex', -1).focus()
    },

    showPrompt: async function(message) {
        this.hideAll()

        this.objects.prompt.text.html(message)
        this.objects.prompt.container.show()
        this.objects.prompt.visible = true

        this.showOverlay()
        this.objects.prompt.input.attr('tabindex', -1).focus()

        let self = this
        return new Promise(function(resolve){
            self.objects.prompt.btnConfirm.one('click.netto', function() {
                resolve(self.objects.prompt.input.val())
                self.hideOverlay()
            })

            self.objects.prompt.btnClose.one('click.netto', function() {
                resolve(false)
                self.hideOverlay()
            })
        })
    }
}

$(document).ready(function() {
    Overlay.init()
})
