import ListAbstract from './list.abstract.js'

class Logs extends ListAbstract {
    constructor(object) {
        super(object)
        this.initButtons(object)

        this.actions = {
            delete: this.body.data('delete-url')
        }

        this.load()
    }

    delete(filename) {
        Overlay.showAnimation()

        Ajax.post(this.actions.delete, {filename: filename}, function() {
            window.location.reload()
        }, function() {
            Overlay.hideAnimation()
        })
    }

    initButtons(object) {
        object.on('click', '.js-list-button[data-type="download"]', function() {
            App.downloadFile($(this).data('filename'))
        })

        let self = this
        object.on('click', '.js-list-button[data-type="delete"]', async function() {
            if (await Overlay.showConfirmDelete()) {
                self.delete($(this).data('filename'))
            }
        })
    }

    render(data) {
        this.body.html(data.items)
        super.render(data)
    }
}

$(document).ready(function() {
    $('.js-logs').each(function() {
        new Logs($(this))
    })
})
