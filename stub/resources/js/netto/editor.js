export default class Editor {
    id = null
    native = null
    language = null

    constructor(object) {
        this.init(object)
        this.create()
    }

    create() {
        CKEDITOR.ClassicEditor.create(document.getElementById(this.id), {
            toolbar: {
                items: [
                    'undo', 'redo', '|',
                    'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript', '|',
                    'heading', '|', 'style', '|',
                    'bulletedList', 'numberedList', '|',
                    'alignment', '|',
                    'indent', 'outdent', '|',
                    'link', 'insertTable', 'specialCharacters', 'mediaEmbed', 'horizontalLine', '|',
                    'findAndReplace', 'selectAll', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: false
            },
            language: {
                ui: App.lang,
                content: this.language
            },
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            heading: {
                options: App.editor.headings
            },
            style: {
                definitions: App.editor.styles
            },
            placeholder: '',
            link: {
                defaultProtocol: 'https://',
                decorators: {
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    },
                    openInNewTab: {
                        mode: 'manual',
                        label: 'Open in a new tab',
                        defaultValue: true,
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer'
                        }
                    }
                }
            },
            removePlugins: [
                'TextPartLanguage',
                'PageBreak',
                'HtmlEmbed',
                'CodeBlock',
                'BlockQuote',
                'Highlight',
                'FontBackgroundColor',
                'FontSize',
                'FontFamily',
                'FontColor',
                'TodoList',
                'Code',
                'RemoveFormat',
                'ExportPdf',
                'ExportWord',
                'AIAssistant',
                'CKBox',
                'CKFinder',
                'EasyImage',
                'Base64UploadAdapter',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                'MathType',
                'SlashCommand',
                'Template',
                'DocumentOutline',
                'FormatPainter',
                'TableOfContents',
                'PasteFromOfficeEnhanced',
                'CaseChange'
            ]
        }).then(editor => {
            editor.model.document.on('change:data', () => {
                this.native.val(editor.getData())
            })
        })
    }

    init(object) {
        this.id = object.find('.js-editor-object').attr('id')
        this.native = object.find('.js-editor-value')
        this.language = object.data('language')
    }
}
