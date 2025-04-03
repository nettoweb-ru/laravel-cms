import ListWidget from './list.widget.js'

class List extends ListWidget {
    body = null
    columns = {}
    iconCreate = null
    iconDownload = null
    iconFind = null
    id = 'netto-admin-list'
    objects = {
        head: null,
        title: null,
        page: null,
        perPage: null,
        total: null,
        pages: null,
        dropdown: null,
        columns: null
    }

    constructor(object) {
        super(object)
        this.initObjects(object)
        this.initIcons(object)
        this.setObjectId(object)
        this.initColumns(object)

        this.initParams()
        this.initDropdown()
        this.initSortColumns()
        this.initPerPage()
        this.initPage()

        this.load()
    }

    getDefaultParams() {
        let k, codes = Array()
        for (k in this.columns) {
            if (this.columns[k].default) {
                codes[codes.length] = k
            }
        }

        let columns = {}, width = 100 - (codes.length - 1)
        for (k in codes) {
            columns[codes[k]] = (codes[k] === 'name') ? width : 1
        }

        return {
            page: 1,
            perPage: 10,
            sort: 'id',
            sortDir: 'asc',
            columns: columns,
        }
    }

    initColumns(object) {
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
            let w1 = $(window).width(),
                w2 = self.objects.head.width()

            self.objects.dropdown.css('left', Math.round((event.pageX - ((w1 - w2) / 2)) * 100 / w2) + '%').show()
            $(window).one('click.netto', function() {
                self.objects.dropdown.hide()
            })

            return false
        })

        this.objects.columns.click(function() {
            let id = $(this).data('id'), columns = {}
            if (typeof self.params.columns[id] === 'undefined') {
                columns = self.params.columns
                columns[id] = 1
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

    initIcons(object) {
        this.initToggleIcon(object)

        this.iconFind = object.find('.js-icon-find')
        this.iconDownload = object.find('.js-icon-download')

        let self = this
        this.iconCreate = object.find('.js-icon-create')
        this.iconCreate.click(function() {
            self.followUrl($(this))
        })
    }

    initObjects(object) {
        this.body = object.find('.js-body')

        this.objects.head = object.find('.js-head')
        this.objects.title = object.find('.js-title')
        this.objects.page = object.find('.js-page')
        this.objects.perPage = object.find('.js-per-page')
        this.objects.total = object.find('.js-counter-items')
        this.objects.pages = object.find('.js-counter-pages')
        this.objects.dropdown = object.find('.js-dropdown-columns')
        this.objects.columns = this.objects.dropdown.children()
    }

    initPage() {
        let self = this
        this.objects.page.change(function() {
            self.params.page = parseInt($(this).val())
            self.saveParams()
            self.load()
        })
    }

    initPerPage() {
        let self = this
        this.objects.perPage.change(function() {
            self.params.page = 1
            self.params.perPage = parseInt($(this).val())

            self.saveParams()
            self.load()
        })
    }

    initWidget(data) {
        this.objects.title.html(data.title)

        if (typeof data.url.toggle === 'string') {
            this.iconToggle.show()
        }

        super.initWidget(data)

        if (typeof data.url.create === 'string') {
            this.iconCreate.data('url', this.url.create).show()
        }
    }

    lock() {
        if (this.locked) {
            return
        }

        this.disable(this.iconToggle)
        this.disable(this.iconCreate)

        this.disable(this.objects.page)

        super.lock()
    }

    lockBulkButtons() {
        this.disable(this.iconToggle)
        super.lockBulkButtons()
    }

    render(data) {
        this.enable(this.iconCreate)
        if (data.nav.total === 0) {
            this.layers.empty.show()
            return
        }

        this.enable(this.iconInvert)

        this.objects.total.html(App.formatNumber(data.nav.total))
        this.objects.pages.html(App.formatNumber(data.nav.max))

        let a, params
        for (a = 1; a <= data.nav.max; a++) {
            params = {
                value: a
            }

            if (a === this.params.page) {
                params.selected = true
            }

            this.objects.page.append($('<option />', params).html(App.formatNumber(a)))
        }

        if (data.nav.max > 1) {
            this.enable(this.objects.page)
        }

        let k1, k2, tr, attr = {}, self = this, th

        for (k1 in this.params.columns) {
            th = $('<th />', {'data-code': k1, 'class': 'sortable'}).html($('<span />', {'class': 'text-small'}).html(this.columns[k1].label))
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

        setTimeout(function() {
            let width = self.objects.head.width()
            self.objects.head.find('th').each(function() {
                $(this).width((self.params.columns[$(this).data('code')] * width / 100) + 'px')
            })
        }, 10)

        this.renderSortColumns()
        this.objects.columns.removeClass('selected').each(function() {
            if (typeof self.params.columns[$(this).data('id')] === 'number') {
                $(this).addClass('selected')
            }
        })

        for (k1 in data.items) {
            attr = {
                'data-id': data.items[k1].id,
                'data-url': data.items[k1].url
            }

            if ((typeof data.items[k1].is_active === 'boolean') && !data.items[k1].is_active) {
                attr.class = 'inactive'
            }

            tr = $('<tr />', attr)
            for (k2 in this.params.columns) {
                tr.append($('<td />').html($('<span />', {'class': 'text'}).html(data.items[k1][k2])))
            }

            this.setClickEvent(tr, function() {
                self.followUrl($(this))
            })

            this.body.append(tr)
        }

        this.objects.perPage.find('option:selected').attr('selected', false)
        this.objects.perPage.find('option[value="' + this.params.perPage + '"]').attr('selected', true)

        super.render(data)
    }

    reset() {
        this.objects.page.html('')
        this.objects.total.html('0')
        this.objects.pages.html('0')
        this.objects.head.html('')

        super.reset()
    }

    unlockBulkButtons() {
        this.enable(this.iconToggle)
        super.unlockBulkButtons()
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
        new List($(this))
    })
})
