import ListWidget from './list.widget.js'

class Gallery extends ListWidget {
    id = 'netto-gallery'

    constructor(object) {
        super(object)
        this.setId(object)

        this.initButtons()
        this.initParams()

        this.load()
    }

    getDefaultParams() {
        return {
            columns: {
                id: 1,
                sort: 1,
                thumb: 98
            },
            sort: 'sort',
            sortDir: 'asc',
            toggle: (typeof this.buttons.toggle === 'object') ? 1 : 0
        }
    }

    render(data) {
        if (data.total) {
            let tr, k1, className, self = this
            for (k1 in data.items) {
                className = 'gallery-item'

                if ((typeof data.items[k1].is_active === 'boolean') && !data.items[k1].is_active) {
                    className += ' inactive'
                }

                tr = $('<div />', {
                    'class': className,
                    'data-id': data.items[k1].id,
                    'data-url': data.items[k1]._editUrl
                }).append($('<img />', {'alt': '', 'src': data.items[k1].thumb}))

                this.setClickEvents(tr, function(event) {
                    if (self.isRightClick(event)) {
                        return
                    }

                    self.followUrl($(this))
                })

                this.body.append(tr)
            }
        }

        super.render(data)
    }
}

$(document).ready(function() {
    $('.js-gallery').each(function() {
        new Gallery($(this))
    })
})
