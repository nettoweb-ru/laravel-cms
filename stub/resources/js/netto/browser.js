import ListWidget from './list.widget.js'

class Browser extends ListWidget {
    id = 'netto-browser'
    parentDir = null
    objects = {
        head: null,
        hold: null,
        path: null,
        upload: null
    }
    maxSizePost = 0
    maxSizeUpload = 0
    root = null

    constructor(object) {
        super(object)
        this.setId(object)

        this.initObjects(object)
        this.initButtons()
        this.initParams()

        this.initBrowser(object)
        this.initSortColumns()
        this.initUpload()

        this.load()
    }

    getDefaultParams() {
        return {
            dir: '/',
            sort: 'name',
            sortDir: 'asc'
        }
    }

    initBrowser() {
        this.maxSizePost = this.objects.hold.data('post-max-size')
        this.maxSizeUpload = this.objects.hold.data('upload-max-filesize')
        this.params.dir = this.objects.hold.data('start-dir')
        this.root = this.objects.hold.data('root')
    }

    initButtons() {
        super.initButtons()

        let self = this

        this.buttons.up.click(function() {
            self.params.dir = self.parentDir
            self.load()
        })

        if (typeof this.buttons.directory === 'object') {
            this.actions.directory = this.buttons.directory.data('url')
            this.buttons.directory.click(async function () {
                let name = await Overlay.showPrompt($(this).data('message'))
                if ((name === false) || (name.length === 0)) {
                    return
                }

                self.lock()
                Ajax.put(self.actions.directory, {
                    name: name,
                    dir: self.params.dir
                }, function() {
                    self.load()
                }, function() {
                    self.unlock()
                })
            })
        }

        if (typeof this.buttons.upload === 'object') {
            this.actions.upload = this.buttons.upload.data('url')
            this.buttons.upload.click(function() {
                self.objects.upload.click()
            })
        }
    }

    initObjects(parent) {
        this.objects.head = parent.find('.js-head')
        this.objects.hold = parent.find('.js-browser-hold')
        this.objects.path = parent.find('.js-path')
        this.objects.upload = parent.find('.js-upload')
    }

    initUpload() {
        let self = this
        this.objects.upload.change(function() {
            self.upload($(this).prop('files'))
        })
    }

    render(data) {
        this.objects.path.val(data.currentDir)
        this.parentDir = data.parentDir

        if (data.total) {
            let attr = {}, tr, self = this
            for (let key in data.items) {
                attr = {
                    'data-id': data.currentDir + data.items[key].name
                }

                if (data.items[key].dir) {
                    attr['class'] = 'dir'
                }

                tr = $('<tr />', attr)
                    .append(
                        $('<td />', {
                            'class': 'name'
                        }).append($('<span />', {
                            'class': 'text',
                            'dir': 'ltr'
                        }).html(data.items[key].name))
                    ).append(
                        $('<td />', {
                            'class': 'size'
                        }).append($('<span />', {
                            'class': 'text',
                            'dir': 'ltr'
                        }).html(data.items[key].size))
                    ).append(
                        $('<td />', {
                            'class': 'date'
                        }).append($('<span />', {
                            'class': 'text'
                        }).html(data.items[key].date))
                    )

                this.setClickEvents(tr, function() {
                    let id = $(this).data('id')
                    if ($(this).hasClass('dir')) {
                        self.params.dir = id
                        self.load()
                    } else {
                        App.downloadFile(self.root + id)
                    }
                })

                this.body.append(tr)
            }

            this.renderSortColumns()
        }

        super.render(data)
    }

    unlock() {
        if (!this.locked) {
            return
        }

        if (this.parentDir) {
            this.enable(this.buttons.up)
        }

        if (typeof this.buttons.directory === 'object') {
            this.enable(this.buttons.directory)
        }

        if (typeof this.buttons.upload === 'object') {
            this.enable(this.buttons.upload)
        }

        super.unlock()
    }

    upload(files) {
        let fileSize = files[0].size,
            error = false

        if (fileSize > this.maxSizeUpload) {
            error = App.messages.errors.uploadMaxFileSize
        } else if (fileSize > this.maxSizePost) {
            error = App.messages.errors.postMaxSize
        }

        if (error) {
            files[0].value = ''
            Overlay.showMessage(error)
            return
        }

        this.lock()

        let form = new FormData()
        form.append('file', files[0])
        form.append('dir', this.params.dir)

        let self = this
        Ajax.send({
            url: this.actions.upload,
            method: 'post',
            data: form,
            contentType: false,
            processData: false,
        }, function() {
            self.objects.upload.val('')
            self.load()
        }, function() {
            self.objects.upload.val('')
            self.unlock()
        })
    }
}

$(document).ready(function() {
    $('.js-browser').each(function() {
        new Browser($(this))
    })
})
