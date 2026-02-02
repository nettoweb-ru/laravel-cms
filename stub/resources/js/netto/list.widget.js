import ListAbstract from './list.abstract.js'

export default class ListWidget extends ListAbstract {
    id = null
    buttons = {}
    selected = []
    total = null

    constructor(object) {
        super(object)
        this.findButtons(object)

        this.total = object.find('.js-total')
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

        Ajax.post(this.actions.delete, {id: this.selected}, function(data) {
            if (data.status) {
                Overlay.message(data.status)
            }

            self.params.page = 1
            self.saveParams()

            self.load()
        }, function() {
            self.unlock()
        })
    }

    findButtons(object) {
        let self = this
        object.find('.js-list-button').each(function() {
            self.buttons[$(this).data('type')] = $(this)
        })
    }

    initButtons() {
        let self = this

        this.buttons.invert.click(function() {
            self.invert()
        })

        if (typeof this.buttons.delete === 'object') {
            this.actions.delete = this.buttons.delete.data('url')
            this.buttons.delete.click(async function() {
                if (await Overlay.confirmation(window.nettoweb.messages.confirm_delete, true)) {
                    self.delete()
                }
            })
        }

        if (typeof this.buttons.toggle === 'object') {
            this.actions.toggle = this.buttons.toggle.data('url')
            this.buttons.toggle.click(async function() {
                if (await Overlay.confirmation(window.nettoweb.messages.confirm_toggle)) {
                    self.toggle()
                }
            })
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

    initParams() {
        let params = localStorage.getItem(this.id)

        if (params === null) {
            this.params = this.getDefaultParams()
            this.saveParams()
        } else {
            this.params = JSON.parse(params)
        }
    }

    invert() {
        this.body.children().toggleClass('selected')
        this.checkSelection()
    }

    isRightClick(event) {
        if (("which" in event) && (event.which === 3)) {
            return true
        }

        return ("button" in event) && (event.button === 2);
    }

    lock() {
        super.lock()

        for (let key in this.buttons) {
            this.disable(this.buttons[key])
        }
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
        super.render(data)
        this.total.html(App.formatNumber(data.total))

        if (data.total > 0) {
            this.enable(this.buttons.invert)
        }
    }

    renderSortColumns() {
        this.objects.head.find('th.sortable').removeClass('sort asc desc')
        this.objects.head.find('th.sortable[data-code="' + this.params.sort + '"]').addClass('sort ' + ((this.params.sortDir === 'asc') ? 'asc' : 'desc'))
    }

    reset() {
        this.selected = []
        this.total.html(App.formatNumber(0))

        super.reset()
    }

    saveParams() {
        this.params = Object.keys(this.params).sort().reduce((obj, key) => {
            obj[key] = this.params[key]
            return obj
        }, {})

        localStorage.setItem(this.id, JSON.stringify(this.params))
    }

    setClickEvents(tr, event) {
        let self = this
        tr.longpress(function(event) {
            if (self.isRightClick(event)) {
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
                Overlay.message(data.status)
            }

            self.load()
        }, function() {
            self.unlock()
        })
    }

    unlock() {
        super.unlock()

        if (typeof this.buttons.create === 'object') {
            this.enable(this.buttons.create)
        }
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
