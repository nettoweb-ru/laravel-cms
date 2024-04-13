import ListWidget from './list.widget.js'

class List extends ListWidget {
    filter = {}
    id = ''
    init = false
    params = {}
    url = {
        list: '',
        delete: '',
        toggle: ''
    }

    constructor(object) {
        super(object);

        this.initPerPage()
        this.initPage()

        this.url.list = object.data('url')

        this.load()
    }

    initActions(object) {
        super.initActions(object)

        this.initIconToggle()
        this.initIconFind()
        this.initIconDownload()
    }

    initIconDownload() {

    }

    initIconFind() {

    }

    initObjects(object) {
        super.initObjects(object)

        this.objects.icons.toggle = object.find('.js-icon-toggle')
        this.objects.icons.find = object.find('.js-icon-find')
        this.objects.icons.download = object.find('.js-icon-download')
        this.objects.icons.create = object.find('.js-icon-create')

        this.objects.title = object.find('.js-title')

        this.objects.page = object.find('.js-page')
        this.objects.perPage = object.find('.js-per-page')
        this.objects.countItems = object.find('.js-counter-items')
        this.objects.countPages = object.find('.js-counter-pages')
    }

    initPage() {
        let self = this
        this.objects.page.change(function() {
            self.params.page = parseInt($(this).val())

            self.saveParams()
        })
    }

    initPerPage() {
        let self = this
        this.objects.perPage.change(function() {
            self.params.page = 1
            self.params.perPage = parseInt($(this).val())

            self.saveParams()
        })
    }

    lockBulkActionButtons() {
        this.objectDisable(this.objects.icons.delete)
        this.objectDisable(this.objects.icons.toggle)
    }

    onAfterLoad(data) {
        this.params = data.params

        if (!this.init) {
            this.id = data.id
            this.objects.title.html(data.title)

            if (typeof data.url.create === 'string') {
                this.objects.icons.create.data('url', data.url.create).show()
            }

            let invert = false
            if (typeof data.url.delete === 'string') {
                this.url.delete = data.url.delete
                this.objects.icons.delete.show()
                invert = true
            }

            if (typeof data.url.toggle === 'string') {
                this.url.toggle = data.url.toggle
                this.objects.icons.toggle.show()
                invert = true
            }

            if (invert) {
                this.objects.icons.invert.show()
            }

            this.renderPerPage()
            this.init = true
        }

        this.objectEnable(this.objects.icons.create)

        if (data.nav.total === 0) {
            this.objects.layers.empty.show()
            return
        }

        this.objectEnable(this.objects.icons.invert)

        this.objects.countItems.html(data.nav.total)
        this.objects.countPages.html(data.nav.max)

        this.renderPages(data.nav.max)

        if (data.nav.max > 1) {
            this.objectEnable(this.objects.page)
        }

        let attr = {}, k1
        for (k1 in data.columns) {
            this.objects.head.append($('<th />', {'data-code': k1, 'width': data.columns[k1].width + '%', 'class': 'sortable'}).html($('<span />', {'class': 'text-small'}).html(data.columns[k1].title)))
        }

        this.renderSortParams()

        let tr, k2
        for (k1 in data.items) {
            attr = {
                'data-id': data.items[k1].id,
                'data-url': data.items[k1].url
            }

            if ((typeof data.items[k1].is_active === 'boolean') && !data.items[k1].is_active) {
                attr.class = 'inactive'
            }

            tr = $('<tr />', attr)
            for (k2 in data.columns) {
                tr.append($('<td />').html($('<span />', {'class': 'text'}).html(data.items[k1][k2])))
            }

            this.render(tr)
        }

        this.objects.layers.found.show()
    }

    onAfterSaveParams(data) {
        this.renderPerPage()
        this.load()
    }

    onRowClick(object) {
        let url = object.data('url')
        if (!url.length) {
            return
        }

        Overlay.redirect(url)
    }

    renderPages(max) {
        let a, params
        for (a = 1; a <= max; a++) {
            params = {
                value: a
            }

            if (a === this.params.page) {
                params.selected = true
            }

            this.objects.page.append($('<option />', params).html(a))
        }
    }

    renderPerPage() {
        this.objects.perPage.find('option:selected').attr('selected', false)
        this.objects.perPage.find('option[value="' + this.params.perPage + '"]').attr('selected', true)
    }

    reset() {
        super.reset()

        this.objects.page.html('')
        this.objects.countItems.html('0')
        this.objects.countPages.html('0')
        this.objects.head.html('')

        this.objectDisable(this.objects.page)
    }

    unlockBulkActionButtons() {
        this.objectEnable(this.objects.icons.delete)
        this.objectEnable(this.objects.icons.toggle)
    }
}

$(document).ready(function() {
    $('.js-list').each(function() {
        new List($(this))
    })
})
