import Editor from './editor.js'

class Form {
    id = null
    languages = {
        active: null,
        default: null,
        isMultiple: false,
        objects: [],
        switches: [],
        storageId: 'netto-admin-form-lang'
    }
    maxSizePost = 0
    maxSizeUpload = 0
    sheets = {
        active: null,
        default: null,
        isMultiple: false,
        objects: [],
        switches: [],
        storageId: 'netto-admin-form-sheet'
    }
    timeout = null

    constructor(object) {
        this.languages.isMultiple = (parseInt(object.data('multilang')) === 1)

        this.maxSizeUpload = parseInt(object.data('upload-max-filesize'))
        this.maxSizePost = parseInt(object.data('post-max-size'))

        let id = object.data('id')
        if (id.length) {
            this.id = id
            this.sheets.storageId += ('-' + this.id)

            if (this.languages.isMultiple) {
                this.languages.storageId += ('-' + this.id)
            }
        }

        this.initDelete(object)
        this.initEditors(object)
        this.initFileInputs(object)
        this.initAnimation(object)
        this.initJsonInputs(object)
        this.initTransliterateInputs(object)
        this.initAutocompleteInputs(object)
        this.initSheets(object)

        if (this.languages.isMultiple) {
            this.initLanguages(object)
        }

        this.checkErrors()

        if (this.sheets.active === null) {
            this.clickSheet(localStorage.getItem(this.sheets.storageId) ?? this.sheets.default)
        }

        if (this.languages.isMultiple && (this.languages.active === null)) {
            this.clickLanguage(localStorage.getItem(this.languages.storageId) ?? this.languages.default)
        }

        this.initFocus()
    }

    autoCompleteAdd(parent, object) {
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

        this.autoCompleteCheck(parent)
    }

    autoCompleteCheck(parent) {
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

    autoCompleteRemove(parent, item) {
        item.remove()
        this.autoCompleteCheck(parent)
    }

    checkErrors() {
        let key, error, lang
        for (key in this.sheets.objects) {
            error = this.sheets.objects[key].find('.js-form-error')
            if (error.length) {
                this.clickSheet(key)

                if (this.languages.isMultiple) {
                    let multiLang = error.closest('.js-multilang')
                    if (multiLang.length) {
                        this.clickLanguage(multiLang.data('code'))
                    }
                }

                return
            }
        }
    }

    clickLanguage(code) {
        let key
        if (this.languages.active) {
            this.languages.switches[this.languages.active].removeClass('active')
            for (key in this.languages.objects[this.languages.active]) {
                this.languages.objects[this.languages.active][key].hide()
            }
        }

        this.languages.active = code
        localStorage.setItem(this.languages.storageId, this.languages.active)

        this.languages.switches[this.languages.active].addClass('active')
        for (key in this.languages.objects[this.languages.active]) {
            this.languages.objects[this.languages.active][key].show()
        }
    }

    clickSheet(id) {
        if (this.sheets.active) {
            this.sheets.switches[this.sheets.active].removeClass('active')
            this.sheets.objects[this.sheets.active].hide()
        }

        this.sheets.active = parseInt(id)
        localStorage.setItem(this.sheets.storageId, this.sheets.active)

        this.sheets.switches[this.sheets.active].addClass('active')
        this.sheets.objects[this.sheets.active].show()
    }

    initAnimation(object) {
        object.find('form').submit(function() {
            Overlay.showAnimation()
        })
    }

    initAutocompleteInputs(object) {
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
                        self.autoCompleteAdd(parent, ui.item)
                        event.target.value = ''
                    } else {
                        parent.find('.js-autocomplete-single').val(ui.item.value)
                        event.target.value = ui.item.label
                    }

                    return false
                }
            })

            parent.on('click', '.js-autocomplete-multiple', function() {
                self.autoCompleteRemove(parent, $(this))
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

    initFileInputs(object) {
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
                                if (this.files[key].size > self.maxSizeUpload) {
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

                        if (postSize > self.maxSizePost) {
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
        let focused = this.sheets.objects[this.sheets.active].find('*[autofocus]:first')
        if (focused.length) {
            if (this.languages.isMultiple) {
                let parent = focused.parent()
                if (parent.hasClass('js-multilang')) {
                    focused = parent.parent().find('.js-multilang[data-code="' + this.languages.active + '"] *[autofocus]:first')
                }
            }

            setTimeout(function() {focused.focus()}, 100)
        }
    }

    initJsonInputs(object) {
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
                self.initJsonValueWidth($(this))
            })

            parent.find('.js-json-value').each(function() {
                self.initJsonValueWidth($(this))
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
                }, 1)
            })
        })
    }

    initJsonValueWidth(object) {
        let length = object.val().length
        if (length) {
            object.css('width', (length + 2) + 'ch')
            return
        }

        object.parent().remove()
    }

    initLanguages(object) {
        let self = this
        object.find('.js-form-lang-switch').each(function() {
            self.languages.switches[$(this).data('id')] = $(this)
            self.languages.objects[$(this).data('id')] = []

            if (self.languages.default === null) {
                self.languages.default = $(this).data('id')
            }

            $(this).click(function() {
                self.clickLanguage($(this).data('id'))
            })
        })

        object.find('.js-multilang').each(function() {
            self.languages.objects[$(this).data('code')][self.languages.objects[$(this).data('code')].length] = $(this)
        })
    }

    initSheets(object) {
        let self = this,
            count = 0

        object.find('.js-form-sheet').each(function() {
            let id = parseInt($(this).data('id')),
                objectSwitch = object.find('.js-form-sheet-switch[data-id="' + id + '"]')

            if (self.sheets.default === null) {
                self.sheets.default = id
            }

            self.sheets.objects[id] = $(this)
            self.sheets.switches[id] = objectSwitch

            objectSwitch.click(function() {
                if ($(this).hasClass('active')) {
                    return
                }

                self.clickSheet(parseInt($(this).data('id')))
            })

            count++
        })

        if (count > 1) {
            this.sheets.isMultiple = true
        } else {
            this.sheets.objects[1].addClass('single')
        }
    }

    initTransliterateInputs(object) {
        let self = this
        object.find('.js-transliterate').each(function() {
            let target = $('#' + $(this).data('transliterate-code'))
            $(this).on('change', function() {
                let source = $(this)
                clearTimeout(self.timeout)
                self.timeout = setTimeout(function() {
                    self.transliterate(source, target)
                }, 1000)
            })
        })
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
