import ListWidget from './list.widget.js'

class Gallery extends ListWidget {
    filter = {}
    id = ''
    init = false
    url = {
        list: '',
        delete: ''
    }

    constructor(object) {
        super(object);
        this.url.list = object.data('url')
        this.load()
    }

    initActions(object) {
        super.initActions(object)
    }

    initObjects(object) {
        super.initObjects(object)

        this.objects.icons.create = object.find('.js-icon-create')
        this.objects.countItems = object.find('.js-counter-items')
        this.objects.body = object.find('.js-images')
    }

    lockBulkActionButtons() {
        this.objectDisable(this.objects.icons.delete)
    }

    onAfterLoad(data) {
        if (!this.init) {
            this.id = data.id
            this.objects.icons.create.data('url', data.url.create)
            this.url.delete = data.url.delete
            this.init = true
        }

        this.objectEnable(this.objects.icons.create)
        this.objectEnable(this.objects.icons.invert)

        if (data.nav.total === 0) {
            this.objects.layers.empty.show()
            return
        }

        this.objects.countItems.html(data.nav.total)

        let tr, k1
        for (k1 in data.items) {
            tr = $('<div />', {
                'class': 'gallery-item',
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
    }
}

$(document).ready(function() {
    $('.js-gallery').each(function() {
        new Gallery($(this))
    })
})
