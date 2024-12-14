export default class ListWidget {
    iconDelete = null
    iconInvert = null
    iconToggle = null
    id = 'netto-admin-widget'
    init = false
    layers = {
        results: null,
        animation: null,
        found: null,
        empty: null
    }
    locked = true
    params = {}
    selected = []
    url = {}
    urlLoad = ''

    constructor(object) {
        this.initLayers(object)
        this.initCommonIcons(object)
        this.setLoadUrl(object)
    }

    checkSelection() {
        this.lockBulkButtons()

        let selected = []
        this.body.children('.selected').each(function() {
            selected[selected.length] = $(this).data('id')
        })
        this.selected = selected

        if (this.selected.length) {
            this.unlockBulkButtons()
        }
    }

    delete() {
        this.lock()
        let self = this

        Ajax.post(this.url.delete, {id: this.selected}, function(data) {
            if (data.status) {
                Overlay.showMessage(data.status)
            }

            self.params.page = 1
            self.saveParams()

            self.load()
        }, function() {
            self.unlock()
        })
    }

    disable(object) {
        object.attr('disabled', true).addClass('disabled')
    }

    enable(object) {
        object.removeAttr('disabled').removeClass('disabled')
    }

    followUrl(object) {
        let url = object.data('url')
        if (!url.length) {
            return
        }

        Overlay.redirect(url)
    }

    getDefaultParams() {
        return {}
    }

    initCommonIcons(object) {
        let self = this

        this.iconInvert = object.find('.js-icon-invert')
        this.iconInvert.click(function() {
            self.invert()
        })

        this.iconDelete = object.find('.js-icon-delete')
        this.iconDelete.click(async function() {
            if (await Overlay.showConfirmDelete()) {
                self.delete()
            }
        })
    }

    initLayers(object) {
        this.layers.animation = object.find('.js-animation')
        this.layers.results = object.find('.js-results')
        this.layers.found = object.find('.js-results-found')
        this.layers.empty = object.find('.js-results-empty')
    }

    initParams() {
        let params = localStorage.getItem(this.id)

        if (params === null) {
            this.params = this.getDefaultParams()
            this.saveParams()
        } else {
            this.params = JSON.parse(params)
        }
    }

    initSortColumns() {
        let self = this
        this.objects.head.on('click', 'th', function() {
            let code = $(this).data('code')

            if (code === self.params.sort) {
                self.params.sortDir = (self.params.sortDir === 'asc') ? 'desc' : 'asc'
            } else {
                self.params.sort = code
            }

            self.saveParams()
            self.renderSortColumns()

            self.load()
        })
    }

    initToggleIcon(object) {
        let self = this

        this.iconToggle = object.find('.js-icon-toggle')
        this.iconToggle.click(async function() {
            if (await Overlay.showConfirm(App.messages.confirm.toggle)) {
                self.toggle()
            }
        })
    }

    initWidget(data) {
        this.url = data.url
        this.init = true
    }

    invert() {
        this.body.children().each(function() {
            $(this).toggleClass('selected')
        })

        this.checkSelection()
    }

    load() {
        this.reset()
        this.lock()

        let self = this,
            finish = function() {
                self.unlock()
            },
            params = this.params

        params.init = this.init ? 0 : 1

        Ajax.get(this.urlLoad, params, function(data) {
            if (!self.init) {
                self.initWidget(data.init)
            }

            self.render(data.results)
            finish()
        }, finish)
    }

    lock() {
        this.disable(this.iconInvert)
        this.disable(this.iconDelete)

        this.layers.results.hide()
        this.layers.animation.show()

        this.locked = true
    }

    lockBulkButtons() {
        this.disable(this.iconDelete)
    }

    render(data) {
        this.layers.found.show()
    }

    renderSortColumns() {
        this.objects.head.find('th').removeClass('sort asc desc')
        this.objects.head.find('th[data-code="' + this.params.sort + '"]').addClass('sort ' + ((this.params.sortDir === 'asc') ? 'asc' : 'desc'))
    }

    reset() {
        this.selected = []

        this.layers.found.hide()
        this.layers.empty.hide()

        this.body.html('')
    }

    saveParams() {
        localStorage.setItem(this.id, JSON.stringify(this.params))
    }

    setClickEvent(tr, event) {
        let self = this
        tr.longpress(function(event) {
            if (("which" in event) && (event.which === 3)) {
                return
            }

            if (("button" in event) && (event.button === 2)) {
                return
            }

            $(this).toggleClass('selected')
            self.checkSelection()
        }, event)
    }

    setLoadUrl(object) {
        let url = object.data('url')
        if (typeof url === 'string') {
            this.urlLoad = url
        }
    }

    setObjectId(object) {
        let id = object.data('id')
        if (typeof id === 'undefined') {
            return
        }

        this.id += ('-' + id.toString())
    }

    toggle() {
        this.lock()
        let self = this

        Ajax.post(this.url.toggle, {id: this.selected}, function(data) {
            if (data.status) {
                Overlay.showMessage(data.status)
            }

            self.load()
        }, function() {
            self.unlock()
        })
    }

    unlock() {
        if (!this.locked) {
            return
        }

        this.layers.animation.hide()
        this.layers.results.show()

        this.locked = false
    }

    unlockBulkButtons() {
        this.enable(this.iconDelete)
    }
}
