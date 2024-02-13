class Tabs {
    id = null
    current = null
    objects = {
        switch: {},
        tab: {},
    }

    constructor(object) {
        this.id = object.data('id')

        this.initObjects(object)
        this.initSwitch()

        this.checkErrors()

        if (!this.current) {
            this.show(object.data('current'))
        }
    }

    checkErrors() {
        for (let key in this.objects.tab) {
            if (this.objects.tab[key].find('.js-form-error').length) {
                this.show(key)
                return
            }
        }
    }

    initObjects(object) {
        let self = this
        object.find('.js-tab').each(function() {
            self.objects.tab[parseInt($(this).data('id'))] = $(this)
        })

        object.find('.js-switch-tab').each(function() {
            self.objects.switch[parseInt($(this).data('id'))] = $(this)
        })
    }

    initSwitch() {
        let self = this
        for (let key in this.objects.switch) {
            this.objects.switch[key].click(function() {
                if ($(this).hasClass('active')) {
                    return
                }

                let id = $(this).data('id')
                Overlay.showAnimation()

                Ajax.post(App.url.setCookie, {
                    key: self.id,
                    value: id
                }, function() {
                    self.show(id)
                    Overlay.hideAnimation()
                })
            })
        }
    }

    show(id) {
        if (this.current) {
            this.objects.switch[this.current].removeClass('active')
            this.objects.tab[this.current].hide()
        }

        this.current = id

        this.objects.switch[this.current].addClass('active')
        this.objects.tab[this.current].show()
    }
}

$(document).ready(function() {
    $('.js-tabs').each(function() {
        new Tabs($(this))
    })
})
