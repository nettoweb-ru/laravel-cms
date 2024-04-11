window.App = {
    editor: {
        headings: [],
        styles: [],
    },
    lang: '',
    token: '',
    messages: {
        confirm: {
            toggle: '',
            logout: '',
        },
        labels: {
            delete: '',
        },
        errors: {
            uploadMaxFileSize: '',
            postMaxSize: '',
        },
    },
    url: {
        setCookie: '/admin/setCookie'
    },
    objects: {
        iconMenuOpen: null,
        iconMenuClose: null,
        iconLanguages: null,
        blockMenu: null,
        blockLanguages: null,
    },
    timeout: null,

    downloadFile: function(filename) {
        window.open('/admin/download/?filename=' + filename)
    },

    hideLanguages: function() {
        this.objects.blockLanguages.hide()
    },

    hideMobileMenu: function() {
        this.objects.iconMenuClose.hide()
        this.objects.iconMenuOpen.show()

        this.objects.blockMenu.hide()
    },

    init: function() {
        this.initObjects()
        this.initDropdowns()
        this.initResize()

        this.initLogoutLinks()
        this.initLanguageLinks()
        this.initLinks()
    },

    initDropdowns: function() {
        let self = this

        this.objects.iconMenuOpen.click(function() {
            self.showMobileMenu()
        })

        this.objects.iconMenuClose.click(function() {
            self.hideMobileMenu()
        })

        this.objects.iconLanguages.click(function() {
            self.showLanguages()
        })
    },

    initLanguageLinks: function() {
        let self = this
        $('.js-set-language').click(function() {
            Overlay.showAnimation()
            Ajax.post(self.url.setCookie, {
                key: 'lang',
                value: $(this).data('code')
            }, function() {
                window.location.reload()
            })
        })
    },

    initLinks: function() {
        $('.js-link').click(function() {
            let url = $(this).data('url')
            if (url.length) {
                Overlay.redirect(url)
            }
        })

        $('a.js-href').click(function() {
            Overlay.showAnimation()
        })
    },

    initLogoutLinks: function() {
        let self = this
        $('.js-logout').click(async function() {
            if (await Overlay.showConfirm(self.messages.confirm.logout)) {
                $(this).find('form').submit();
            }
        })
    },

    initObjects: function() {
        this.objects.iconMenuOpen = $('#js-icon-menu-open')
        this.objects.iconMenuClose = $('#js-icon-menu-close')
        this.objects.iconLanguages = $('#js-icon-languages')

        this.objects.blockMenu = $('#js-mobile-menu')
        this.objects.blockLanguages = $('#js-mobile-languages')
    },

    initResize: function() {
        let self = this
        $(window).on('resize.netto', function() {
            if (window.innerWidth > 1023) {
                self.hideLanguages()
                self.hideMobileMenu()

                $(document).off('click.netto')
                clearTimeout(self.timeout)
            }
        })
    },

    showLanguages: function() {
        this.objects.blockLanguages.show()

        let self = this
        this.timeout = setTimeout(function() {
            $(document).one('click.netto', function() {
                self.hideLanguages()
            })
        }, 1)
    },

    showMobileMenu: function() {
        this.objects.iconMenuOpen.hide()
        this.objects.iconMenuClose.show()

        this.objects.blockMenu.show().scrollTop(0)

        let self = this
        this.timeout = setTimeout(function() {
            $(document).one('click.netto', function() {
                self.hideMobileMenu()
            })
        }, 1)
    },
}

$(document).ready(function() {
    App.init()
})
