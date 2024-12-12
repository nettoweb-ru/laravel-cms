class Tabs {
    id = null
    current = null
    objects = {
        switch: {},
        tab: {},
    }
    storageIdTab = 'netto-admin-form-tab'

    constructor(object) {
        this.id = object.data('id')

        this.initObjects(object)
        this.initSwitch()

        this.checkErrors()

        let id = localStorage.getItem(this.storageIdTab)
        if ((id === null) || (typeof this.objects.tab[id] === 'undefined')) {
            id = Object.keys(this.objects.tab)[0]
        }

        this.show(id)
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
                localStorage.setItem(self.storageIdTab, id)
                self.show(id)
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
