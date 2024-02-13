import Editor from './editor.js'

class Form {
    cookieId = null
    language = null
    multiLang = false
    objects = {
        sheets: {},
        sheetSwitch: {},
        langInputs: [],
        langSwitch: {},
    }
    sheet = null
    sheetCount = 0
    sheetsActive = false

    constructor(object) {
        let id = object.data('id')
        if (id.length) {
            this.cookieId = id + '_sheet'
        }

        this.multiLang = (parseInt(object.data('multilang')) === 1)

        this.initDelete(object)
        this.initEditors(object)
        this.initFileFields(object)
        this.initAnimation(object)

        this.initObjects(object)
        this.sheetsActive = (this.sheetCount > 1)

        this.checkErrors()

        if (this.sheetsActive) {
            this.initSheetsSwitch()
        } else {
            this.objects.sheets[1].addClass('single')
        }

        if (this.multiLang) {
            this.initLanguageSwitch()
            if (!this.language) {
                this.showLanguage(object.data('language'))
            }
        }

        if (!this.sheet) {
            this.showSheet(object.data('sheet'))
        }

        this.initFocus();
    }

    checkErrors() {
        let key, error, lang
        for (key in this.objects.sheets) {
            error = this.objects.sheets[key].find('.js-form-error')
            if (error.length) {
                this.showSheet(key)
                lang = error.closest('.js-multilang')
                if (lang.length) {
                    this.showLanguage(lang.data('code'))
                }
                return
            }
        }
    }

    initAnimation(object) {
        object.find('form').submit(function() {
            Overlay.showAnimation()
        })
    }

    initDelete(object) {
        let button = object.find('.js-item-destroy')
        if (button.length) {
            let form = object.find('.js-form-destroy')
            button.click(async function() {
                if (await Overlay.showConfirmDelete()) {
                    form.submit()
                }
            })
        }
    }

    initEditors(object) {
        object.find('.js-editor').each(function() {
            new Editor($(this))
        })
    }

    initFileFields(object) {
        object.find('.js-file-attr').each(function() {
            let parent = $(this),
                node = parent.find('.js-file-value'),
                text = parent.find('.js-file-text'),
                file = parent.find('.js-file-input'),
                upload = parent.find('.js-file-upload'),
                download = parent.find('.js-file-download'),
                current = text.val()

            upload.click(function() {
                parent.find('.js-file-input').click()
            })

            file.change(function() {
                let value = $(this).val()
                if (!value.length) {
                    value = current
                }

                text.val(value)
                node.val(value)
            })

            download.click(function() {
                App.downloadFile($(this).data('filename'));
            })

            parent.find('.js-file-delete').click(function() {
                let status = parseInt($(this).data('status'))
                if (status) {
                    text.removeClass('delete')
                    node.val(current)
                    $(this).removeClass('on').data('status', '0')

                    upload.removeClass('disabled').removeAttr('disabled')
                    download.removeClass('disabled').removeAttr('disabled')
                } else {
                    file.val('')
                    text.addClass('delete').val(current)
                    node.val('')
                    $(this).addClass('on').data('status', '1')

                    upload.addClass('disabled').attr('disabled', true)
                    download.addClass('disabled').attr('disabled', true)
                }
            })
        })
    }

    initFocus() {
        let focused = this.objects.sheets[this.sheet].find('*[autofocus]:first')

        if (focused.length) {
            if (this.language) {
                let parent = focused.parent()
                if (parent.hasClass('js-multilang')) {
                    focused = parent.parent().find('.js-multilang[data-code="' + this.language + '"] *[autofocus]:first')
                }
            }

            setTimeout(function() {focused.focus()})
        }
    }

    initLanguageSwitch() {
        let self = this
        for (let key in this.objects.langSwitch) {
            this.objects.langSwitch[key].click(function() {
                if ($(this).hasClass('active')) {
                    return
                }

                let id = $(this).data('id')
                Overlay.showAnimation()

                Ajax.post(App.url.setCookie, {
                    key: 'lang_public',
                    value: id
                }, function() {
                    self.showLanguage(id)
                    Overlay.hideAnimation()
                })
            })
        }
    }

    initObjects(object) {
        let self = this
        object.find('.js-form-sheet').each(function() {
            self.objects.sheets[parseInt($(this).data('id'))] = $(this)
            self.sheetCount++
        })

        object.find('.js-form-sheet-switch').each(function() {
            self.objects.sheetSwitch[parseInt($(this).data('id'))] = $(this)
        })

        object.find('.js-multilang').each(function() {
            let code = $(this).data('code')
            if (typeof self.objects.langInputs[code] === 'undefined') {
                self.objects.langInputs[code] = []
            }

            self.objects.langInputs[code][self.objects.langInputs[code].length] = $(this)
        })

        object.find('.js-form-lang-switch').each(function() {
            self.objects.langSwitch[$(this).data('id')] = $(this)
        })
    }

    initSheetsSwitch() {
        let self = this
        for (let key in this.objects.sheetSwitch) {
            this.objects.sheetSwitch[key].click(function() {
                if ($(this).hasClass('active')) {
                    return
                }

                let id = $(this).data('id')
                Overlay.showAnimation()

                Ajax.post(App.url.setCookie, {
                    key: self.cookieId,
                    value: id
                }, function() {
                    self.showSheet(id)
                    Overlay.hideAnimation()
                })
            })
        }
    }

    showLanguage(id) {
        let key
        if (this.language) {
            this.objects.langSwitch[this.language].removeClass('active')
            for (key in this.objects.langInputs[this.language]) {
                this.objects.langInputs[this.language][key].hide();
            }
        }

        this.language = id

        if (!$.isEmptyObject(this.objects.langSwitch)) {
            this.objects.langSwitch[this.language].addClass('active')
        }

        for (key in this.objects.langInputs[this.language]) {
            this.objects.langInputs[this.language][key].show();
        }
    }

    showSheet(id) {
        if (this.sheet) {
            if (this.sheetsActive) {
                this.objects.sheetSwitch[this.sheet].removeClass('active')
            }

            this.objects.sheets[this.sheet].hide()
        }

        this.sheet = id

        if (this.sheetsActive) {
            this.objects.sheetSwitch[this.sheet].addClass('active')
        }

        this.objects.sheets[this.sheet].show()
    }
}

$(document).ready(function() {
    $('.js-form').each(function() {
        new Form($(this))
    })
})
