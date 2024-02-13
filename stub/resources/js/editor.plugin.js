import { Plugin } from '@ckeditor/ckeditor5-core';

export default class OpenBrowser extends Plugin {
    init() {
        const editor = this.editor

        console.log(editor)
    }
}
