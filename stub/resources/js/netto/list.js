import ListWidget from './list.widget.js'

class List extends ListWidget {
    body = null
    columns = null
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
        pages: null
    }

    constructor(object) {
        super(object)
        this.initObjects(object)
        this.initIcons(object)
        this.setObjectId(object)
        this.initParams()
        this.initSortColumns()
        this.initPerPage()
        this.initPage()
        this.load()
    }

    getDefaultParams() {
        return {
            page: 1,
            perPage: 10,
            sort: 'id',
            sortDir: 'asc'
        }
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

        this.columns = data.columns
        for (let k1 in this.columns) {
            this.objects.head.append($('<th />', {'data-code': k1, 'width': this.columns[k1].width + '%', 'class': 'sortable'}).html($('<span />', {'class': 'text-small'}).html(this.columns[k1].title)))
        }

        this.renderSortColumns()
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

        this.objects.total.html(data.nav.total)
        this.objects.pages.html(data.nav.max)

        let a, params
        for (a = 1; a <= data.nav.max; a++) {
            params = {
                value: a
            }

            if (a === this.params.page) {
                params.selected = true
            }

            this.objects.page.append($('<option />', params).html(a))
        }

        if (data.nav.max > 1) {
            this.enable(this.objects.page)
        }

        let k1, k2, tr, attr = {}, self = this
        for (k1 in data.items) {
            attr = {
                'data-id': data.items[k1].id,
                'data-url': data.items[k1].url
            }

            if ((typeof data.items[k1].is_active === 'boolean') && !data.items[k1].is_active) {
                attr.class = 'inactive'
            }

            tr = $('<tr />', attr)
            for (k2 in this.columns) {
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
        super.reset()
    }

    unlockBulkButtons() {
        this.enable(this.iconToggle)
        super.unlockBulkButtons()
    }
}

$(document).ready(function() {
    $('.js-list').each(function() {
        new List($(this))
    })
})
