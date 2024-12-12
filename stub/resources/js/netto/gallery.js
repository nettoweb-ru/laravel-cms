import ListWidget from './list.widget.js'

class Gallery extends ListWidget {
    body = null
    id = 'netto-admin-gallery'
    objects = {
        title: null,
        total: null
    }
    iconCreate = null

    constructor(object) {
        super(object)
        this.initObjects(object)
        this.initIcons(object)
        this.setObjectId(object)
        this.initParams()
        this.load()
    }

    getDefaultParams() {
        return {
            sort: 'sort',
            sortDir: 'asc'
        }
    }

    initIcons(object) {
        this.initToggleIcon(object)

        let self = this
        this.iconCreate = object.find('.js-icon-create')
        this.iconCreate.click(function() {
            self.followUrl($(this))
        })
    }

    initObjects(object) {
        this.body = object.find('.js-images')
        this.objects.title = object.find('.js-title')
        this.objects.total = object.find('.js-counter-items')
    }

    initWidget(data) {
        this.objects.title.html(data.title)
        super.initWidget(data)

        this.iconCreate.data('url', this.url.create)
    }

    lock() {
        if (this.locked) {
            return
        }

        this.disable(this.iconToggle)
        this.disable(this.iconCreate)

        super.lock()
    }

    render(data) {
        this.enable(this.iconCreate)
        if (data.nav.total === 0) {
            this.layers.empty.show()
            return
        }

        this.enable(this.iconInvert)
        this.objects.total.html(data.nav.total)

        let tr, k1, className, self = this
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

            this.setClickEvent(tr, function() {
                self.followUrl($(this))
            })

            this.body.append(tr)
        }

        super.render(data)
    }

    reset() {
        this.objects.total.html('0')
        super.reset()
    }
}

$(document).ready(function() {
    $('.js-gallery').each(function() {
        new Gallery($(this))
    })
})
