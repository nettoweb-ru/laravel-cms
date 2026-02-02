export default class ListAbstract {
    actions = {}
    body = null
    layers = {
        results: null,
        animation: null,
        found: null,
        empty: null
    }
    locked = true
    objects = {}
    params = {}
    url = null

    constructor(object) {
        this.url = object.data('url')
        this.body = object.find('.js-body')

        this.initLayers(object)
    }

    disable(object) {
        object.attr('disabled', true).addClass('disabled')
    }

    enable(object) {
        object.removeAttr('disabled').removeClass('disabled')
    }

    followUrl(object) {
        let url = object.data('url')
        if (!url.length) {
            return
        }

        window.location.href = url
    }

    getDefaultParams() {
        return {}
    }

    initLayers(object) {
        this.layers.animation = object.find('.js-layer-animation')
        this.layers.results = object.find('.js-layer-results')
        this.layers.found = object.find('.js-layer-results-found')
        this.layers.empty = object.find('.js-layer-results-empty')
    }

    load() {
        this.reset()
        this.lock()

        let self = this,
            finish = function() {
                self.unlock()
            }

        Ajax.get(this.url, this.params, function(data) {
            self.render(data)
            finish()
        }, finish)
    }

    lock() {
        if (this.locked) {
            return
        }

        this.layers.results.hide()
        this.layers.animation.show()

        this.locked = true
    }

    render(data) {
        if (data.total === 0) {
            this.layers.empty.show()
        } else {
            this.layers.found.show()
        }
    }

    reset() {
        this.layers.found.hide()
        this.layers.empty.hide()

        this.body.html('')
    }

    unlock() {
        if (!this.locked) {
            return
        }

        this.layers.animation.hide()
        this.layers.results.show()

        this.locked = false
    }
}
