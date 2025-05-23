export default class ListWidget {
    id = null
    actions = {}
    body = null
    buttons = {}
    layers = {
        results: null,
        animation: null,
        found: null,
        empty: null
    }
    locked = true
    navigation = false
    navObjects = {
        perPage: null,
        page: null,
        pages: null,
    }
    objects = {}
    params = {}
    selected = []
    total = null
    url = null

    constructor(object) {
        this.initArguments(object)
        this.initLayers(object)
        this.findButtons(object)
        this.initSharedObjects(object)

        if (this.navigation) {
            this.initPage()
            this.initPerPage()
        }
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

    enable(object) {
        object.removeAttr('disabled').removeClass('disabled')
    }

    delete() {
        this.lock()
        let self = this

        Ajax.post(this.actions.delete, {id: this.selected}, function(data) {
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

    findButtons(object) {
        let self = this
        object.find('.js-list-button').each(function() {
            self.buttons[$(this).data('type')] = $(this)
        })
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

    initArguments(object) {
        this.url = object.data('url')
        this.navigation = parseInt(object.data('show-navigation')) === 1
    }

    initButtons() {
        let self = this

        this.buttons.invert.click(function() {
            self.invert()
        })

        if (typeof this.buttons.delete === 'object') {
            this.actions.delete = this.buttons.delete.data('url')
            this.buttons.delete.click(async function() {
                if (await Overlay.showConfirmDelete()) {
                    self.delete()
                }
            })
        }

        if (typeof this.buttons.toggle === 'object') {
            this.actions.toggle = this.buttons.toggle.data('url')
            this.buttons.toggle.click(async function() {
                if (await Overlay.showConfirm(App.messages.confirm.toggle)) {
                    self.toggle()
                }
            })
        }
    }

    initLayers(object) {
        this.layers.animation = object.find('.js-layer-animation')
        this.layers.results = object.find('.js-layer-results')
        this.layers.found = object.find('.js-layer-results-found')
        this.layers.empty = object.find('.js-layer-results-empty')
    }

    initPage() {
        let self = this
        this.navObjects.page.change(function() {
            self.params.page = parseInt($(this).val())
            self.saveParams()
            self.load()
        })
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

    initPerPage() {
        let self = this
        this.navObjects.perPage.change(function() {
            self.params.page = 1
            self.params.perPage = parseInt($(this).val())

            self.saveParams()
            self.load()
        })
    }

    initSharedObjects(parent) {
        this.body = parent.find('.js-body')
        this.total = parent.find('.js-total')

        if (this.navigation) {
            this.navObjects.perPage = parent.find('.js-per-page')
            this.navObjects.page = parent.find('.js-page')
            this.navObjects.pages = parent.find('.js-pages')
        }
    }

    initSortColumns() {
        let self = this
        this.objects.head.on('click', 'th.sortable', function() {
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

    invert() {
        this.body.children().toggleClass('selected')
        this.checkSelection()
    }

    load() {
        this.reset()
        this.lock()

        let self = this,
            finish = function() {
                self.unlock()
            }

        Ajax.get(this.url, this.params, function(data) {
            self.render(data)
            finish()
        }, finish)
    }

    lock() {
        if (this.locked) {
            return
        }

        for (let key in this.buttons) {
            this.disable(this.buttons[key])
        }

        this.layers.results.hide()
        this.layers.animation.show()

        if (this.navigation) {
            this.disable(this.navObjects.page)
            this.disable(this.navObjects.perPage)
        }

        this.locked = true
    }

    lockBulkButtons() {
        if (typeof this.buttons.delete === 'object') {
            this.disable(this.buttons.delete)
        }

        if (typeof this.buttons.toggle === 'object') {
            this.disable(this.buttons.toggle)
        }
    }

    render(data) {
        this.total.html(App.formatNumber(data.total))

        if (data.total === 0) {
            this.layers.empty.show()
            return
        }

        this.enable(this.buttons.invert)

        if (this.navigation) {
            this.renderNavigation(data.maxPage)
        }

        this.layers.found.show()
    }

    renderNavigation(max) {
        this.navObjects.pages.html(App.formatNumber(max))

        let a, params
        for (a = 1; a <= max; a++) {
            params = {
                value: a
            }

            if (a === this.params.page) {
                params.selected = true
            }

            this.navObjects.page.append($('<option />', params).html(App.formatNumber(a)))
        }

        if (max > 1) {
            this.enable(this.navObjects.page)
        }

        this.navObjects.perPage.find('option[value=' + this.params.perPage + ']').attr('selected', true);
    }

    renderSortColumns() {
        this.objects.head.find('th.sortable').removeClass('sort asc desc')
        this.objects.head.find('th.sortable[data-code="' + this.params.sort + '"]').addClass('sort ' + ((this.params.sortDir === 'asc') ? 'asc' : 'desc'))
    }

    reset() {
        let zero = App.formatNumber(0)

        this.selected = []
        this.total.html(zero)

        this.layers.found.hide()
        this.layers.empty.hide()

        this.body.html('')

        if (this.navigation) {
            this.navObjects.pages.html(zero)
            this.navObjects.page.html('')
        }
    }

    saveParams() {
        localStorage.setItem(this.id, JSON.stringify(this.params))
    }

    setClickEvents(tr, event) {
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

    setId(object) {
        let id = object.attr('id')
        if (typeof id === 'undefined') {
            return
        }

        this.id += ('-' + id.toString())
    }

    toggle() {
        this.lock()
        let self = this

        Ajax.post(this.actions.toggle, {id: this.selected}, function(data) {
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

        if (typeof this.buttons.create === 'object') {
            this.enable(this.buttons.create)
        }

        if (this.navigation) {
            this.enable(this.navObjects.perPage)
        }

        this.layers.animation.hide()
        this.layers.results.show()

        this.locked = false
    }

    unlockBulkButtons() {
        if (typeof this.buttons.delete === 'object') {
            this.enable(this.buttons.delete)
        }

        if (typeof this.buttons.toggle === 'object') {
            this.enable(this.buttons.toggle)
        }
    }
}
