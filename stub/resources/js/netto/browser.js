import ListWidget from './list.widget.js'

class Browser extends ListWidget {
    body = null
    dirParent = null
    iconFolderAdd = null
    iconFolderUp = null
    iconUpload = null
    id = 'netto-admin-browser'
    maxSizePost = 0
    maxSizeUpload = 0
    objects = {
        head: null,
        directory: null,
        upload: null
    }
    path = null

    constructor(object) {
        super(object)
        this.initObjects(object)
        this.initIcons(object)
        this.setObjectId(object)
        this.setInitDirectory(object)
        this.setUploadLimits(object)

        this.initParams()
        this.params.dir = '/'

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

    initIcons(object) {
        let self = this

        this.iconUpload = object.find('.js-icon-upload')
        this.iconUpload.click(function() {
            self.objects.upload.click()
        })

        this.iconFolderUp = object.find('.js-icon-folder-up')
        this.iconFolderUp.click(function() {
            self.params.dir = self.dirParent
            self.load()
        })

        this.iconFolderAdd = object.find('.js-icon-folder-add')
        this.iconFolderAdd.click(async function() {
            let name = await Overlay.showPrompt($(this).data('message'))
            if ((name === false) || (name.length === 0)) {
                return
            }

            self.lock()
            Ajax.put(self.url.directory, {
                name: name,
                dir: self.params.dir
            }, function() {
                self.load()
            }, function() {
                self.unlock()
            })
        })
    }

    initObjects(object) {
        this.body = object.find('.js-body')
        this.objects.head = object.find('.js-head')
        this.objects.directory = object.find('.js-path')
        this.objects.upload = object.find('.js-upload')
    }

    initUpload() {
        let self = this
        this.objects.upload.change(function() {
            self.upload($(this).prop('files'))
        })
    }

    initWidget(data) {
        this.path = data.path
        this.renderSortColumns()
        super.initWidget(data)
    }

    lock() {
        if (this.locked) {
            return
        }

        this.disable(this.iconUpload)
        this.disable(this.iconFolderUp)
        this.disable(this.iconFolderAdd)

        super.lock()
    }

    processClick(tr) {
        if (tr.hasClass('dir')) {
            this.params.dir = tr.data('id')
            this.load()
        } else {
            App.downloadFile(this.path + tr.data('id'))
        }
    }

    render(data) {
        this.objects.directory.val(data.dirCurrent)

        this.dirParent = data.dirParent
        if (this.dirParent) {
            this.enable(this.iconFolderUp)
        }

        this.enable(this.iconFolderAdd)
        this.enable(this.iconUpload)

        if (data.items.length === 0) {
            this.layers.empty.show()
            return
        }

        this.enable(this.iconInvert)

        let attr = {}, tr, self = this
        for (let key in data.items) {
            attr = {
                'data-id': data.dirCurrent + data.items[key].name
            }

            if (data.items[key].dir) {
                attr['class'] = 'dir'
                attr['data-id'] += '/'
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

            this.setClickEvent(tr, function() {
                self.processClick($(this))
            })

            this.body.append(tr)
        }

        super.render(data)
    }

    reset() {
        this.dirParent = null
        super.reset()
    }

    setInitDirectory(object) {
        let dir = object.data('dir')
        if (typeof dir === 'string') {
            this.params.dir = dir
        }
    }

    setUploadLimits(object) {
        this.maxSizeUpload = parseInt(object.data('upload-max-filesize'))
        this.maxSizePost = parseInt(object.data('post-max-size'))
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
            url: this.url.upload,
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
