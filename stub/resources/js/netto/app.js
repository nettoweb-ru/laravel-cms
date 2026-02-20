window.App = {
    dropdown: {
        mobile: {
            lang: false,
            menu: false,
        },
    },
    editor: {
        headings: [],
        styles: [],
    },
    objects: {
        body: null,
        logoutForm: null,
        mobile: {
            menuBtnOpen: null,
            menuBtnClose: null,
            menu: null,
            lang: null,
        },
        desktop: {},
    },
    timeout: {},
    url: {
        cookie: '/admin/tools/cookie',
        download: '/admin/tools/download',
        transliterate: '/admin/tools/transliterate',
    },

    downloadFile: function(filename) {
        window.open(this.url.download + '?filename=' + encodeURIComponent(filename))
    },

    formatCurrency: function(value, currency, precision) {
        if (typeof precision === 'undefined') {
            precision = 0
        }

        return new Intl.NumberFormat(window.nettoweb.locale, {style: 'currency', currency: currency, maximumFractionDigits: precision}).format(value)
    },

    formatDate: function(value, options) {
        if (typeof options === 'undefined') {
            options = {}
        }

        return new Intl.DateTimeFormat(window.nettoweb.locale, options).format(value)
    },

    formatNumber: function(value, precision) {
        if (typeof precision === 'undefined') {
            precision = 0
        }

        return new Intl.NumberFormat(window.nettoweb.locale, {style: 'decimal', maximumFractionDigits: precision}).format(value)
    },

    hideDesktopMenu: function(id) {
        clearTimeout(this.timeout[id])
        this.objects.desktop[id].menu.hide()
        this.objects.desktop[id].trigger.removeClass('current')
    },

    hideMobileLanguages: function() {
        if (!this.dropdown.mobile.lang) {
            return
        }

        this.objects.mobile.lang.hide()
        this.objects.body.removeClass('show-overlay')

        this.dropdown.mobile.lang = false
    },

    hideMobileMenu: function() {
        if (!this.dropdown.mobile.menu) {
            return
        }

        this.objects.mobile.menuBtnClose.hide()
        this.objects.mobile.menuBtnOpen.show()

        this.objects.mobile.menu.hide()
        this.objects.body.removeClass('show-overlay')

        this.dropdown.mobile.menu = false
    },

    init: function() {
        this.initObjects()
        this.initResize()

        this.initMobile()
        this.initDesktop()

        this.initLinks()
        this.initLogoutLink()
        this.initLanguageLinks()
    },

    initDesktop: function() {
        let key
        for (key in this.objects.desktop) {
            this.initDesktopMenuItem(key, this.objects.desktop[key])
        }
    },

    initDesktopMenuItem: function(id, object) {
        object.trigger.on('mouseenter', function() {
            App.showDesktopMenu(id)
        })

        object.menu.on('mouseenter', function() {
            App.showDesktopMenu(id)
        })

        object.trigger.on('mouseleave', function() {
            App.hideDesktopMenu(id)
        })

        object.menu.on('mouseleave', function() {
            App.hideDesktopMenu(id)
        })
    },

    initDesktopPositions: function() {
        let key, pos, scrollW = window.innerWidth - document.documentElement.clientWidth

        for (key in this.objects.desktop) {
            if (this.objects.desktop[key].reversed) {
                pos = window.innerWidth - this.objects.desktop[key].trigger.offset().left - this.objects.desktop[key].trigger.width() - scrollW
                this.objects.desktop[key].menu.css('right', pos)
            } else {
                pos = this.objects.desktop[key].trigger.offset().left
                this.objects.desktop[key].menu.css('left', pos)
            }
        }
    },

    initLanguageLinks: function() {
        $('.js-set-language').click(function() {
            Overlay.animation()
            Ajax.post(App.url.cookie, {
                key: 'netto-admin-lang',
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
                window.location.href = url
            }
        })
    },

    initLogoutLink: function() {
        $('#js-logout').click(async function() {
            if (await Overlay.confirmation(window.nettoweb.messages.confirm_logout)) {
                Overlay.animation()
                App.objects.logoutForm.submit();
            }
        })
    },

    initMobile: function() {
        this.objects.mobile.menuBtnOpen.click(function() {
            App.showMobileMenu()
        })

        this.objects.mobile.menuBtnClose.click(function() {
            App.hideMobileMenu()
        })

        $('#js-mobile-languages-toggle').click(function() {
            App.toggleLanguages()
        })
    },

    initObjects: function() {
        this.objects.body = $('body')

        this.objects.logoutForm = $('#js-logout-form')

        this.objects.mobile.menuBtnOpen = $('#js-mobile-menu-open')
        this.objects.mobile.menuBtnClose = $('#js-mobile-menu-close')
        this.objects.mobile.menu = $('#js-mobile-menu')

        this.objects.mobile.lang = $('#js-mobile-languages')

        $('.js-desktop-menu').each(function() {
            let id = $(this).data('id'),
                trigger = $('.js-desktop-menu-show[data-id="' + id+ '"]')

            if ($(this).hasClass('dropdown-normal')) {
                App.objects.desktop[id] = {
                    trigger: trigger,
                    menu: $(this),
                    reversed: false
                }
            } else {
                App.objects.desktop[id] = {
                    trigger: trigger,
                    menu: $(this),
                    reversed: true
                }
            }

            App.timeout[id] = null
        })
    },

    initResize: function() {
        $(window).on('resize', function() {
            if (window.innerWidth > 767) {
                App.hideMobileLanguages()
                App.hideMobileMenu()


                $(document).off('click.netto')
            }
        })
    },

    showDesktopMenu: function(id) {
        clearTimeout(this.timeout[id])

        this.objects.desktop[id].trigger.addClass('current')
        this.objects.desktop[id].menu.show()

        this.initDesktopPositions()

        setTimeout(function() {
            App.hideMobileMenu(id)
        }, 100)
    },

    showMobileLanguages: function() {
        if (this.dropdown.mobile.lang) {
            return
        }

        if (this.dropdown.mobile.menu) {
            this.hideMobileMenu()
        }

        this.objects.mobile.lang.show()
        this.dropdown.mobile.lang = true

        setTimeout(function() {
            App.objects.body.addClass('show-overlay')

            $(document).one('click.netto', function() {
                App.hideMobileLanguages()
            })
        }, 1)
    },

    showMobileMenu: function() {
        if (this.dropdown.mobile.menu) {
            return
        }

        if (this.dropdown.mobile.lang) {
            this.hideMobileLanguages()
        }

        this.objects.mobile.menuBtnOpen.hide()
        this.objects.mobile.menuBtnClose.show()

        this.objects.mobile.menu.show();
        this.dropdown.mobile.menu = true

        setTimeout(function() {
            App.objects.body.addClass('show-overlay')

            $(document).one('click.netto', function() {
                App.hideMobileMenu()
            })
        }, 1)
    },

    toggleLanguages: function() {
        if (this.dropdown.mobile.lang) {
            this.hideMobileLanguages()
        } else {
            this.showMobileLanguages()
        }
    },
}

$(document).ready(function() {
    App.init()
})
