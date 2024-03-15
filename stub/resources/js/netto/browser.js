import ListWidget from './list.widget.js'

class Browser extends ListWidget {
    control = {
        parent: '/',
        path: '',
        /*storage: '',
        func: ''*/
    }
    filter = {
        dir: ''
    }
    id = 'browser'
    params = {}
    url = {
        list: '/admin/browser/list',
        delete: '/admin/browser/delete',
        upload: '/admin/browser/upload',
        dir: '/admin/browser/directory'
    }

    constructor(object) {
        super(object);

        this.filter.dir = object.data('dir')

        this.control.func = object.data('func')
        this.control.storage = object.data('storage')

        this.load()
    }

    initActions(object) {
        super.initActions(object)

        this.initUpload()

        this.initIconFolderUp()
        this.initIconFolderAdd()
        this.initIconUpload()
    }

    initIconFolderAdd() {
        let self = this
        this.objects.icons.folderAdd.click(async function() {
            let name = await Overlay.showPrompt($(this).data('message'))
            if ((name === false) || (name.length === 0)) {
                return
            }

            self.lock()
            Ajax.put(self.url.dir, {
                name: name,
                dir: self.filter.dir
            }, function() {
                self.load()
            }, function() {
                self.unlock()
            })
        })
    }

    initIconFolderUp() {
        let self = this
        this.objects.icons.folderUp.click(function() {
            self.filter.dir = self.control.parent
            self.load()
        })
    }

    initIconUpload() {
        let self = this
        this.objects.icons.upload.click(function() {
            self.objects.upload.click()
        })
    }

    initObjects(object) {
        super.initObjects(object)

        this.objects.icons.folderUp = object.find('.js-icon-folder-up')
        this.objects.icons.folderAdd = object.find('.js-icon-folder-add')
        this.objects.icons.upload = object.find('.js-icon-upload')

        this.objects.path = object.find('.js-path')
        this.objects.upload = object.find('.js-upload')

        /*if (window.parent && window.parent.CKEDITOR) {
            this.objects.editor = window.parent.CKEDITOR
        } else if (window.opener && window.opener.CKEDITOR) {
            this.objects.editor = window.opener.CKEDITOR
        }*/
    }

    initUpload() {
        let self = this
        this.objects.upload.change(function() {
            self.upload($(this).prop('files'))
        })
    }

    lockBulkActionButtons() {
        this.objectDisable(this.objects.icons.delete)
    }

    onAfterLoad(data) {
        this.params = data.params
        this.control.path = data.path
        this.objects.path.val(data.dir)

        if (data.parent) {
            this.control.parent = data.parent
            this.objectEnable(this.objects.icons.folderUp)
        }

        this.objectEnable(this.objects.icons.folderAdd)
        this.objectEnable(this.objects.icons.upload)

        this.renderSortParams()

        if (data.list.length === 0) {
            this.objects.layers.empty.show()
            return
        }

        this.objectEnable(this.objects.icons.invert)

        let attr = {}
        for (let key in data.list) {
            attr = {
                'data-id': data.dir + data.list[key].name
            }

            if (data.list[key].dir) {
                attr['class'] = 'dir'
                attr['data-id'] += '/'
            }

            this.render(
                $('<tr />', attr)
                    .append(
                        $('<td />', {
                            'class': 'name'
                        }).append($('<span />', {
                            'class': 'text',
                            'dir': 'ltr'
                        }).html(data.list[key].name))
                    ).append(
                    $('<td />', {
                        'class': 'size'
                    }).append($('<span />', {
                        'class': 'text',
                        'dir': 'ltr'
                    }).html(data.list[key].size))
                ).append(
                    $('<td />', {
                        'class': 'date'
                    }).append($('<span />', {
                        'class': 'text'
                    }).html(data.list[key].date))
                )
            )
        }

        this.objects.layers.found.show()
    }

    onAfterSaveParams(data) {
        this.load()
    }

    onRowClick(object) {
        if (object.hasClass('selected')) {
            return
        }

        if (object.hasClass('dir')) {
            this.filter.dir = object.data('id')
            this.load()
        } else {
            App.downloadFile(this.control.path + object.data('id'))
        }
    }

    reset() {
        super.reset()

        this.objects.path.val('')
        this.control.parent = ''
    }

    unlockBulkActionButtons() {
        this.objectEnable(this.objects.icons.delete)
    }

    upload(files) {
        this.lock()

        let form = new FormData()
        form.append('file', files[0])
        form.append('dir', this.filter.dir)

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
