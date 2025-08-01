import ListWidget from './list.widget.js'

window.lists = []

class List extends ListWidget {
    columns = {}
    defaultSort = {}
    defaultWidth = 15
    id = 'netto-list'
    navObjects = {
        perPage: null,
        page: null,
        pages: null,
    }
    objects = {
        head: null,
        dropdown: null,
        columns: null
    }
    noSort = {}

    constructor(object) {
        super(object)

        this.defaultSort = object.data('default-sort')
        this.noSort = object.data('no-sort')

        this.setId(object)

        this.initObjects(object)
        this.initButtons()

        this.initColumns()
        this.initParams()

        this.initDropdown()
        this.initSortColumns()

        this.initPage()
        this.initPerPage()

        this.load()
    }

    getDefaultParams() {
        let k, codes = Array(), code
        for (k in this.columns) {
            if (this.columns[k].default) {
                codes[codes.length] = k
            }
        }

        let columns = {},
            width = 100,
            names = ['name', 'title'],
            nameColumn = null

        for (k in codes) {
            code = codes[k]
            if (this.isNarrowColumn(code)) {
                columns[code] = 1
                width -= 1
            } else if (names.includes(code)) {
                columns[code] = 0
                nameColumn = code
            } else {
                columns[code] = this.defaultWidth
                width -= this.defaultWidth
            }
        }

        if (!nameColumn) {
            let keys = Object.keys(columns)
            nameColumn = keys[keys.length - 1];
        }

        columns[nameColumn] = width

        return {
            page: 1,
            perPage: 10,
            columns: columns,
            sort: Object.keys(this.defaultSort)[0],
            sortDir: Object.values(this.defaultSort)[0],
            toggle: (typeof this.buttons.toggle === 'object') ? 1 : 0
        }
    }

    initButtons() {
        super.initButtons()

        /*this.buttons.search.click(function() {

        })

        this.buttons.download.click(function() {

        })*/
    }

    initColumns() {
        let self = this
        this.objects.columns.each(function() {
            let id = $(this).data('id')
            self.columns[id] = {
                label: $(this).find('span').html(),
                default: parseInt($(this).data('default')) === 1
            }
        })
    }

    initDropdown() {
        let self = this
        this.objects.head.bind('contextmenu', function(event) {
            let wHead = self.objects.head.width(),
                wPopup = self.objects.dropdown.width(),
                left = event.pageX - ($(window).width() - wHead) / 2

            if (App.textDir === 'ltr') {
                left += 8
                if ((left + wPopup) > wHead) {
                    left = wHead - wPopup
                }
            } else {
                left -= (wPopup + 8)
                if (left < 0) {
                    left = 0
                }
            }

            self.objects.dropdown.css('left', left).show()
            $(window).one('click.netto', function() {
                self.objects.dropdown.hide()
            })

            return false
        })

        this.objects.columns.click(function() {
            let id = $(this).data('id'), columns = {}
            if (typeof self.params.columns[id] === 'undefined') {
                columns = self.params.columns
                columns[id] = self.isNarrowColumn(id) ? 1 : self.defaultWidth
            } else {
                let k, i = 0
                for (k in self.params.columns) {
                    if (k === id) {
                        continue
                    }

                    columns[k] = self.params.columns[k]
                    i++
                }

                if (i === 0) {
                    columns = self.getDefaultParams()['columns']
                }
            }

            self.params.columns = columns
            self.validateWidth()
            self.saveParams()

            self.load()
        })
    }

    initObjects(parent) {
        this.objects.head = parent.find('.js-head')
        this.objects.dropdown = parent.find('.js-dropdown')
        this.objects.columns = this.objects.dropdown.children()

        this.navObjects.perPage = parent.find('.js-per-page')
        this.navObjects.page = parent.find('.js-page')
        this.navObjects.pages = parent.find('.js-pages')
    }

    initPage() {
        let self = this
        this.navObjects.page.change(function() {
            self.params.page = parseInt($(this).val())
            self.saveParams()
            self.load()
        })
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

    isNarrowColumn(code) {
        let narrow = ['id', 'sort']
        return narrow.includes(code)
    }

    lock() {
        super.lock()

        this.disable(this.navObjects.page)
        this.disable(this.navObjects.perPage)
    }

    render(data) {
        if (data.total) {
            let k1, k2, tr, attr = {}, self = this, th
            for (k1 in this.params.columns) {
                attr = {'data-code': k1, 'class': 'col-' + k1}
                if (!this.noSort.includes(k1)) {
                    attr.class += ' sortable'
                }

                th = $('<th />', attr).html($('<span />', {'class': 'text-small'}).html(this.columns[k1].label))
                th.resizable({
                    containment: "parent",
                    handles: "e",
                    stop: function() {
                        let total = self.objects.head.width()
                        self.objects.head.find('th').each(function() {
                            let code = $(this).data('code'),
                                abs = Math.round(parseFloat($(this).css('width')))
                                    + parseInt($(this).css('padding-left'))
                                    + parseInt($(this).css('padding-right'))
                                    + parseInt($(this).css('border-right-width')),
                                rel = Math.round(abs * 100 / total)

                            $(this).width($(this).css('width'))
                            self.params.columns[code] = rel
                        })

                        self.validateWidth()
                        self.saveParams()
                    }
                })

                this.objects.head.append(th)
            }

            this.objects.columns.removeClass('selected').each(function() {
                if (typeof self.params.columns[$(this).data('id')] === 'number') {
                    $(this).addClass('selected')
                }
            })

            setTimeout(function() {
                self.setColumnsWidth()
            }, 10)

            this.renderSortColumns()

            for (k1 in data.items) {
                attr = {
                    'data-id': data.items[k1].id,
                    'data-url': data.items[k1]._editUrl
                }

                if ((typeof data.items[k1].is_active === 'boolean') && !data.items[k1].is_active) {
                    attr.class = 'inactive'
                }

                tr = $('<tr />', attr)
                for (k2 in this.params.columns) {
                    tr.append($('<td />', {'class': 'col-' + k2}).html($('<span />', {'class': 'text'}).html(data.items[k1][k2])))
                }

                this.setClickEvents(tr, function(event) {
                    if (self.isRightClick(event)) {
                        return false
                    }

                    self.followUrl($(this))
                })

                this.body.append(tr)
            }

            this.renderNavigation(data.maxPage)
        }

        super.render(data)
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

    reset() {
        super.reset()
        this.objects.head.html('')

        this.navObjects.pages.html(App.formatNumber(0))
        this.navObjects.page.html('')
    }

    setColumnsWidth() {
        let width = this.objects.head.width(),
            self = this

        this.objects.head.find('th').each(function() {
            $(this).width((self.params.columns[$(this).data('code')] * width / 100) + 'px')
        })
    }

    unlock() {
        super.unlock()

        this.enable(this.navObjects.perPage)
    }

    validateWidth() {
        let key, total = 0, check = 0, biggest
        for (key in this.params.columns) {
            total += this.params.columns[key]
            if (this.params.columns[key] > check) {
                check = this.params.columns[key]
                biggest = key
            }
        }

        if (total < 100) {
            this.params.columns[key] += (100 - total)
        } else if (total > 100) {
            this.params.columns[biggest] -= (total - 100)
        }
    }
}

$(document).ready(function() {
    $('.js-list').each(function() {
        window.lists[window.lists.length] = new List($(this))
    })
})
