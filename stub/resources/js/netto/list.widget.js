export default class ListWidget {
    locked = true
    objects = {
        body: null,
        layers: {
            results: null,
            animation: null,
            found: null,
            empty: null
        },
        icons: {
            invert: null,
            delete: null
        }
    }
    selected = []

    constructor(object) {
        this.initObjects(object)
        this.initActions(object)

        this.initSort()
    }

    checkSelection() {
        this.lockBulkActionButtons()

        let selected = []
        this.objects.body.children('.selected').each(function() {
            selected[selected.length] = $(this).data('id')
        })
        this.selected = selected

        if (this.selected.length) {
            this.unlockBulkActionButtons()
        }
    }

    delete() {
        this.lock()
        let self = this

        Ajax.post(this.url.delete, {id: this.selected}, function(data) {
            if (data.status) {
                Overlay.showMessage(data.status)
            }

            self.load()
        }, function() {
            self.unlock()
        })
    }

    initActions(object) {
        this.initIconDelete()
        this.initIconInvert()
    }

    initIconDelete() {
        let self = this
        this.objects.icons.delete.click(async function() {
            if (await Overlay.showConfirmDelete()) {
                self.delete()
            }
        })
    }

    initIconInvert() {
        let self = this
        this.objects.icons.invert.click(function() {
            self.objects.body.children().each(function() {
                $(this).toggleClass('selected')
            })

            self.checkSelection()
        })
    }

    initObjects(object) {
        this.objects.body = object.find('.js-body')
        this.objects.head = object.find('.js-head')

        this.objects.layers.results = object.find('.js-results')
        this.objects.layers.animation = object.find('.js-animation')
        this.objects.layers.found = object.find('.js-results-found')
        this.objects.layers.empty = object.find('.js-results-empty')

        this.objects.icons.invert = object.find('.js-icon-invert')
        this.objects.icons.delete = object.find('.js-icon-delete')
    }

    initSort() {
        let self = this
        this.objects.head.on('click', 'th', function() {
            let code = $(this).data('code')
            if (code === self.params.sort) {
                self.params.sortDir = (self.params.sortDir === 'asc') ? 'desc' : 'asc'
            } else {
                self.params.sort = code
            }

            self.saveParams()
        })
    }

    load() {
        this.lock()
        this.reset()

        let self = this
        Ajax.get(this.url.list, this.filter, function(data) {
            self.onAfterLoad(data)
            self.unlock()
        }, function() {
            self.unlock()
        })
    }

    lock() {
        if (this.locked) {
            return
        }

        this.objects.layers.results.hide()
        this.objects.layers.animation.show()

        this.locked = true
    }

    objectDisable(button) {
        button.attr('disabled', true).addClass('disabled')
    }

    objectEnable(button) {
        button.removeAttr('disabled').removeClass('disabled')
    }

    render(tr) {
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
        }, function(event) {
            self.onRowClick($(this))
        })

        this.objects.body.append(tr)
    }

    renderSortParams() {
        this.objects.head.find('th').removeClass('sort asc desc')
        this.objects.head.find('th[data-code="' + this.params.sort + '"]').addClass('sort ' + ((this.params.sortDir === 'asc') ? 'asc' : 'desc'))
    }

    reset() {
        this.selected = [];
        this.objects.body.html('')

        this.objects.layers.found.hide()
        this.objects.layers.empty.hide()

        for (let key in this.objects.icons) {
            this.objectDisable(this.objects.icons[key])
        }
    }

    saveParams() {
        this.lock()

        let self = this
        Ajax.post(App.url.setCookie, {
            key: self.id,
            value: JSON.stringify(self.params),
        }, function() {
            self.onAfterSaveParams()
        }, function() {
            self.unlock()
        })
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

        this.objects.layers.animation.hide()
        this.objects.layers.results.show()

        this.locked = false
    }
}
