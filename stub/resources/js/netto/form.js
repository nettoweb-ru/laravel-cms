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
    transliterateTimeout = null
    uploadMaxFileSize = 0
    postMaxSize = 0

    constructor(object) {
        let id = object.data('id')
        if (id.length) {
            this.cookieId = id + '_sheet'
        }

        this.multiLang = (parseInt(object.data('multilang')) === 1)

        this.uploadMaxFileSize = parseInt(object.data('upload-max-filesize'))
        this.postMaxSize = parseInt(object.data('post-max-size'))

        this.initDelete(object)
        this.initEditors(object)
        this.initFileFields(object)
        this.initAnimation(object)
        this.initJsonFields(object)
        this.initTransliterateFields(object)
        this.initAutocompleteFields(object)

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

        this.initFocus()
    }

    addMultipleValue(parent, object) {
        let current = Array(),
            value = parseInt(object.value)

        parent.find('.js-autocomplete-multiple').each(function() {
            current[current.length] = parseInt($(this).data('id'))
        })

        if ($.inArray(value, current) > -1) {
            return
        }

        parent.find('.js-autocomplete-multiple-hold').append($('<div />', {
            'class': 'value-item js-autocomplete-multiple',
            'data-id': object.value
        }).append($('<div />', {
            'class': 'value-item-table'
        }).append($('<div />', {
            'class': 'value-item-cell'
        }).append($('<span />', {
            'class': 'text-small'
        }).html(object.label)).append($('<input />', {
            'type': 'hidden',
            'name': parent.data('name') + '[]',
            'value': object.value
        })))))

        this.checkMultipleValue(parent)
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

    checkMultipleValue(parent) {
        if (parent.find('.js-autocomplete-multiple').length) {
            parent.find('.js-autocomplete-multiple-hidden').remove()
        } else {
            parent.find('.js-autocomplete-multiple-hold').append($('<input />', {
                'type': 'hidden',
                'class': 'js-autocomplete-multiple-hidden',
                'name': parent.data('name') + '[]',
                'value': '',
            }))
        }
    }

    initAnimation(object) {
        object.find('form').submit(function() {
            Overlay.showAnimation()
        })
    }

    initAutocompleteFields(object) {
        let self = this

        object.find('.js-autocomplete').each(function() {
            let parent = $(this),
                name = parent.data('name'),
                multiple = (parseInt(parent.data('multiple')) === 1),
                options = autocomplete[name]

            parent.find('.js-autocomplete-input').autocomplete({
                source: function(request, response) {
                    response($.ui.autocomplete.filter(options, request.term).slice(0, 3))
                },
                focus(event, ui) {
                    event.target.value = ui.item.label
                    return false
                },
                change: function(event, ui) {
                    event.target.value = ''

                    if (!multiple) {
                        let hidden = parent.find('.js-autocomplete-single')
                        if (ui.item === null) {
                            hidden.val('')
                        } else {
                            hidden.val(ui.item.value)
                            event.target.value = ui.item.label
                        }
                    }
                },
                select: function(event, ui) {
                    if (multiple) {
                        self.addMultipleValue(parent, ui.item)
                        event.target.value = ''
                    } else {
                        parent.find('.js-autocomplete-single').val(ui.item.value)
                        event.target.value = ui.item.label
                    }

                    return false
                }
            })

            parent.on('click', '.js-autocomplete-multiple', function() {
                self.removeMultipleValue(parent, $(this))
            })
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
        let self = this
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
                let uploadMaxFileSizeError = false,
                    postSize = 0

                object.find('.js-file-input').each(function() {
                    if (this.files.length) {
                        for (let key in this.files) {
                            if (typeof this.files[key] === 'object') {
                                postSize += this.files[key].size
                                if (this.files[key].size > self.uploadMaxFileSize) {
                                    uploadMaxFileSizeError = true
                                    break
                                }
                            }
                        }

                        if (uploadMaxFileSizeError) {
                            Overlay.showMessage(App.messages.errors.uploadMaxFileSize)
                            this.value = ''
                            this.files.value = null
                            return false
                        }

                        if (postSize > self.postMaxSize) {
                            Overlay.showMessage(App.messages.errors.postMaxSize)
                            this.value = ''
                            this.files.value = null
                            return false
                        }
                    }
                })

                let value = $(this).val()
                if (!value.length) {
                    value = current
                }

                text.val(value)
                node.val(value)
            })

            download.click(function() {
                App.downloadFile($(this).data('filename'))
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

    initTransliterateFields(object) {
        let self = this
        object.find('.js-transliterate').each(function() {
            let target = $('#' + $(this).data('transliterate-code'))
            $(this).on('change', function() {
                let source = $(this)
                clearTimeout(self.transliterateTimeout)
                self.transliterateTimeout = setTimeout(function() {
                    self.transliterate(source, target)
                }, 1000)
            })
        })
    }

    initJsonFields(object) {
        let self = this
        object.find('.js-json').each(function() {
            let parent = $(this)

            parent.find('.js-json-add').click(function() {
                let input = $('<input />', {
                    'type': 'text',
                    'class': 'input text js-json-value',
                    'name': parent.data('name') + '[]'
                })

                parent.find('.js-json-values').append($('<div />', {
                    'class': 'json-value-item'
                }).append(input))
            })

            parent.on('blur change', '.js-json-value', function() {
                self.initJsonFieldWidth($(this))
            })

            parent.find('.js-json-value').each(function() {
                self.initJsonFieldWidth($(this))
            })

            parent.find('.js-json-values').change(function() {
                let object = $(this)
                setTimeout(function() {
                    if (object.find('.js-json-value').length === 0) {
                        object.append($('<input />', {
                            'type': 'hidden',
                            'name': parent.data('name'),
                            'value': '',
                        }))
                    } else {
                        object.find('input[type="hidden"]').remove()
                    }
                }, 1);
            })
        })
    }

    initJsonFieldWidth(object) {
        let length = object.val().length
        if (length) {
            object.css('width', (length + 2) + 'ch')
            return
        }

        object.parent().remove()
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

    removeMultipleValue(parent, item) {
        item.remove()
        this.checkMultipleValue(parent)
    }

    showLanguage(id) {
        let key
        if (this.language) {
            this.objects.langSwitch[this.language].removeClass('active')
            for (key in this.objects.langInputs[this.language]) {
                this.objects.langInputs[this.language][key].hide()
            }
        }

        this.language = id

        if (!$.isEmptyObject(this.objects.langSwitch)) {
            this.objects.langSwitch[this.language].addClass('active')
        }

        for (key in this.objects.langInputs[this.language]) {
            this.objects.langInputs[this.language][key].show()
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

    transliterate(source, target) {
        Overlay.showAnimation()
        Ajax.get('/admin/transliterate', {
            string: source.val()
        }, function(data) {
            Overlay.hideAnimation()
            target.val(data.string)
        })
    }
}

$(document).ready(function() {
    $('.js-form').each(function() {
        new Form($(this))
    })
})
