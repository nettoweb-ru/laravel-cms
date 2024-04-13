import ListWidget from './list.widget.js'

class Gallery extends ListWidget {
    filter = {}
    id = ''
    init = false
    url = {
        list: '',
        delete: '',
        toggle: ''
    }

    constructor(object) {
        super(object);
        this.url.list = object.data('url')
        this.load()
    }

    initActions(object) {
        super.initActions(object)
        this.initIconToggle()
    }

    initObjects(object) {
        super.initObjects(object)

        this.objects.icons.create = object.find('.js-icon-create')
        this.objects.icons.toggle = object.find('.js-icon-toggle')

        this.objects.countItems = object.find('.js-counter-items')
        this.objects.body = object.find('.js-images')
        this.objects.title = object.find('.js-title')
    }

    lockBulkActionButtons() {
        this.objectDisable(this.objects.icons.delete)
        this.objectDisable(this.objects.icons.toggle)
    }

    onAfterLoad(data) {
        if (!this.init) {
            this.id = data.id
            this.objects.icons.create.data('url', data.url.create)
            this.url.delete = data.url.delete

            if (typeof data.url.toggle === 'string') {
                this.url.toggle = data.url.toggle
                this.objects.icons.toggle.show()
            }

            this.objects.title.html(data.title)
            this.init = true
        }

        this.objectEnable(this.objects.icons.create)

        if (data.nav.total === 0) {
            this.objects.layers.empty.show()
            return
        }

        this.objectEnable(this.objects.icons.invert)
        this.objects.countItems.html(data.nav.total)

        let tr, k1, className
        for (k1 in data.items) {
            className = 'gallery-item'

            if ((typeof data.items[k1].is_active === 'boolean') && !data.items[k1].is_active) {
                className += ' inactive'
            }

            tr = $('<div />', {
                'class': className,
                'data-id': data.items[k1].id,
                'data-url': data.items[k1].url
            }).append($('<img />', {'alt': '', 'src': data.items[k1].thumb}))

            this.render(tr)
        }

        this.objects.layers.found.show()
    }

    onRowClick(object) {
        let url = object.data('url')
        if (!url.length) {
            return
        }

        Overlay.redirect(url)
    }

    reset() {
        super.reset()
        this.objects.countItems.html('0')
    }

    unlockBulkActionButtons() {
        this.objectEnable(this.objects.icons.delete)
        this.objectEnable(this.objects.icons.toggle)
    }
}

$(document).ready(function() {
    $('.js-gallery').each(function() {
        new Gallery($(this))
    })
})
