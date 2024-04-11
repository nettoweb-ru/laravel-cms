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
        menu: null,
        languages: null
    },
    timeout: null,

    downloadFile: function(filename) {
        window.open('/admin/download/?filename=' + filename)
    },

    init: function() {
        this.initObjects()
        this.initMobileMenu()

        this.initDropdownLinks()
        this.initResize()

        this.initLogoutLinks()
        this.initLanguageLinks()
        this.initLinks()
    },

    initDropdownLinks: function() {
        let self = this
        $('#js-mobile-menu-icon').click(function() {
            self.showMenu()
        })

        $('#js-mobile-lang-icon').click(function() {
            self.showLang()
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

    initMobileMenu: function() {
        let menu = $('#js-desktop-menu .menu').clone()
        $('#js-mobile-menu').append(menu)
    },

    initObjects: function() {
        this.objects.menu = $('#js-mobile-menu')
        this.objects.languages = $('#js-mobile-lang')
    },

    initResize: function() {
        let self = this
        $(window).on('resize.netto', function() {
            if (window.innerWidth > 1023) {
                self.hideDropdown()
            }
        })
    },

    hideDropdown: function() {
        this.objects.menu.hide()
        this.objects.languages.hide()

        $(document).off('click.netto')

        clearTimeout(this.timeout)
    },

    setDropdownClose: function() {
        let self = this
        $(document).one('click.netto', function() {
            self.hideDropdown()
        })
    },

    showMenu: function() {
        this.hideDropdown()

        window.scrollTo({top: 0, behavior: 'smooth'})
        this.objects.menu.show()

        this.timeout = setTimeout('App.setDropdownClose()', 1)
    },

    showLang: function() {
        this.hideDropdown()

        this.objects.languages.show()

        this.timeout = setTimeout('App.setDropdownClose()', 1)
    }
}

$(document).ready(function() {
    App.init()
})
