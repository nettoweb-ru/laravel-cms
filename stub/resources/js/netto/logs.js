import ListAbstract from './list.abstract.js'

class Logs extends ListAbstract {
    constructor(object) {
        super(object)

        this.actions = {
            delete: this.body.data('delete-url'),
            download: this.body.data('download-url'),
        }

        this.initButtons(object)
        this.load()
    }

    delete(filename) {
        Overlay.animation()

        Ajax.post(this.actions.delete, {filename: filename}, function() {
            window.location.reload()
        }, function() {
            Overlay.hide()
        })
    }

    initButtons(object) {
        let self = this

        object.on('click', '.js-list-button[data-type="download"]', function() {
            window.open(self.actions.download + '?filename=' + $(this).data('filename'))
        })

        object.on('click', '.js-list-button[data-type="delete"]', async function() {
            if (await Overlay.confirmation(window.nettoweb.messages.confirm_delete, true)) {
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
