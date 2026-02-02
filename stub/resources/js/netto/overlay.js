window.Overlay = {
    init: false,
    visible: false,
    objects: {
        body: null,
        overlay: null,
        animation: null,
        popup: {
            window: null,
            text: null,
            input: {
                hold: null,
                object: null,
            },
            buttons: {
                ok: null,
                confirm: null,
                close: null,
                remove: null,
            }
        },
        custom: null,
    },

    animation: function() {
        this.hide()

        this.objects.animation.show()
        this.show()
    },

    confirmation: async function(message, remove) {
        this.hide()

        if (typeof remove === 'undefined') {
            remove = false
        }

        this.objects.popup.text.html(message)

        if (remove) {
            this.objects.popup.buttons.remove.show()
        } else {
            this.objects.popup.buttons.confirm.show()
        }

        this.objects.popup.buttons.close.show()
        this.objects.popup.window.show()
        this.show()

        return new Promise(function(resolve){
            if (remove) {
                Overlay.objects.popup.buttons.remove.one('click.netto', function() {
                    resolve(true)
                    Overlay.hide()
                })
            } else {
                Overlay.objects.popup.buttons.confirm.one('click.netto', function() {
                    resolve(true)
                    Overlay.hide()
                })
            }

            Overlay.objects.popup.buttons.close.one('click.netto', function() {
                resolve(false)
                Overlay.hide()
            })
        })
    },

    custom: function() {
        this.hide()

        this.objects.custom.show()
        this.show()
    },

    hide: function() {
        this.initialize()

        if (!this.visible) {
            return
        }

        this.objects.body.removeClass('show-overlay')
        this.objects.popup.text.html('')

        this.objects.popup.buttons.confirm.hide()
        this.objects.popup.buttons.remove.hide()
        this.objects.popup.buttons.close.hide()
        this.objects.popup.buttons.ok.hide()

        this.objects.popup.input.object.val('')
        this.objects.popup.input.hold.hide()

        this.objects.popup.window.hide()
        this.objects.animation.hide()
        this.objects.custom.hide()

        this.objects.overlay.hide()

        this.visible = false
    },

    initialize: function() {
        if (this.init) {
            return
        }

        this.objects.body = $('body')

        this.objects.popup.text = $('<span />', {
            'class': 'text js-text',
        })
        let objTextHold = $('<div />', {
            'class': 'overlay-item text',
        }).append(this.objects.popup.text)

        this.objects.popup.input.hold = $('<div />', {
            'class': 'overlay-item input',
        })
        this.objects.popup.input.object = $('<input />', {
            'type': 'text',
            'name': 'prompt',
            'class': 'input text',
        })
        this.objects.popup.input.hold.append(this.objects.popup.input.object)

        let btnMessages = {
            btn_label_cancel: '',
            btn_label_confirm: '',
            btn_label_delete: '',
            btn_label_ok: '',
        }

        if (typeof window.nettoweb !== 'undefined') {
            btnMessages.btn_label_cancel = window.nettoweb.messages.btn_label_cancel
            btnMessages.btn_label_confirm = window.nettoweb.messages.btn_label_confirm
            btnMessages.btn_label_delete = window.nettoweb.messages.btn_label_delete
            btnMessages.btn_label_ok = window.nettoweb.messages.btn_label_ok
        }

        this.objects.popup.buttons.close = $('<button />', {
            'type': 'button',
            'class': 'btn btn-form btn-normal btn-unavailable',
        }).html(btnMessages.btn_label_cancel)
        this.objects.popup.buttons.confirm = $('<button />', {
            'type': 'button',
            'class': 'btn btn-form btn-normal btn-done',
        }).html(btnMessages.btn_label_confirm)
        this.objects.popup.buttons.remove = $('<button />', {
            'type': 'button',
            'class': 'btn btn-form btn-warning btn-remove',
        }).html(btnMessages.btn_label_delete)
        this.objects.popup.buttons.ok = $('<button />', {
            'type': 'button',
            'class': 'btn btn-form btn-normal btn-done',
        }).html(btnMessages.btn_label_ok)

        let objBtnHold = $('<div />', {
            'class': 'overlay-item buttons',
        })
            .append(this.objects.popup.buttons.ok)
            .append(this.objects.popup.buttons.remove)
            .append(this.objects.popup.buttons.confirm)
            .append(this.objects.popup.buttons.close)


        this.objects.popup.window = $('<div />', {
            'class': 'overlay-popup',
        }).append(objTextHold).append(this.objects.popup.input.hold).append(objBtnHold)

        this.objects.animation = $('<div />', {
            'class': 'overlay-loading',
        })

        for (let a = 0; a < 12; a++) {
            this.objects.animation.append($('<div />'))
        }

        this.objects.overlay = $('<div />', {
            'class': 'layer layer-overlay',
        })

        let objHold = $('<div />', {
            'class': 'overlay-hold',
        })

        objHold.append(this.objects.popup.window)
        objHold.append(this.objects.animation)

        this.objects.custom = $('<div />', {
            'class': 'overlay-custom',
        })

        objHold.append(this.objects.custom)

        this.objects.overlay.append($('<div />', {
            'class': 'overlay-table',
        }).html(
            $('<div />', {
                'class': 'overlay-cell',
            }).append(objHold)
        ))

        this.objects.body.append(this.objects.overlay)
        this.init = true
    },

    initMessages: function() {
        let message = '';
        $('.js-flash-message').each(function() {
            message += ($(this).html() + '<br />')
            $(this).remove()
        })

        if (message.length) {
            this.message(message)
        }
    },

    message: function(message) {
        this.hide()

        this.objects.popup.text.html(message)

        this.objects.popup.buttons.ok.one('click.netto', function() {
            Overlay.hide()
        }).show()

        this.objects.popup.window.show()
        this.show()
    },

    prompt: async function(message) {
        this.hide()

        this.objects.popup.text.html(message)
        this.objects.popup.input.hold.show()

        this.objects.popup.buttons.confirm.show()
        this.objects.popup.buttons.close.show()

        this.objects.popup.input.object.attr('tabindex', -1).focus()

        this.objects.popup.window.show()
        this.show()

        return new Promise(function(resolve){
            Overlay.objects.popup.buttons.confirm.one('click.netto', function() {
                resolve(Overlay.objects.popup.input.object.val())
                Overlay.hide()
            })

            Overlay.objects.popup.buttons.close.one('click.netto', function() {
                resolve(false)
                Overlay.hide()
            })
        })
    },

    show: function() {
        this.objects.body.addClass('show-overlay')
        this.objects.overlay.show()

        this.visible = true
    }
}

$(document).ready(function() {
    Overlay.initMessages()
})
