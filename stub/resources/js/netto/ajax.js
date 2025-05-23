window.Ajax = {
    delete: function(urn, data, onSuccess, onError) {
        this.send({
            url: urn,
            method: 'delete',
            data: data
        }, onSuccess, onError)
    },

    get: function(urn, data, onSuccess, onError) {
        this.send({
            url: urn,
            method: 'get',
            data: data
        }, onSuccess, onError)
    },

    patch: function(urn, data, onSuccess, onError) {
        this.send({
            url: urn,
            method: 'patch',
            data: data
        }, onSuccess, onError)
    },

    post: function(urn, data, onSuccess, onError) {
        this.send({
            url: urn,
            method: 'post',
            data: data
        }, onSuccess, onError)
    },

    put: function(urn, data, onSuccess, onError) {
        this.send({
            url: urn,
            method: 'put',
            data: data
        }, onSuccess, onError)
    },

    send: function(params, onSuccess, onError) {
        if (typeof onSuccess === 'undefined') {
            onSuccess = function(data) {}
        }

        if (typeof onError === 'undefined') {
            onError = function() {}
        }

        params.success = function(data, textStatus, jqXHR) {
            onSuccess(data)
        }

        if (typeof params.error === 'undefined') {
            params.error = function(jqXHR, textStatus, errorThrown) {
                let message = ''
                if (jqXHR.responseText.length) {
                    let parse = JSON.parse(jqXHR.responseText)

                    if (typeof parse.message === 'string') {
                        message = parse.message
                    }
                }

                if (!message.length) {
                    if (errorThrown.length) {
                        message = errorThrown
                    } else if (textStatus.length) {
                        message = textStatus
                    }
                }

                if (!message.length) {
                    message = 'Unknown error'
                }

                Overlay.showMessage(message)
                onError()
            }
        }

        params.beforeSend = function(jqXHR, settings) {
            jqXHR.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'))
        }

        params.dataType = 'json'
        $.ajax(params)
    },
}
