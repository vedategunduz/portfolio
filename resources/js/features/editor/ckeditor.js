import {
    ClassicEditor,
    Essentials,
    Bold,
    Italic,
    Font,
    Paragraph,
    List,
    Link,
    Table,
    TableToolbar,
    BlockQuote,
    Heading,
    Undo,
    Image,
    ImageUpload,
    ImageToolbar,
    ImageStyle,
    ImageCaption,
    Indent,
    Alignment
} from 'ckeditor5';

import 'ckeditor5/ckeditor5.css';

/**
 * CKEditor Başlatıcı
 * @param {string} selector - Textarea ID'si veya Class'ı (örn: '#editor')
 * @param {object} userConfig - Dışarıdan gelen ek ayarlar
 */
export const initEditor = async (selector, userConfig = {}) => {
    const element = document.querySelector(selector);

    if (!element) return;

    const defaultConfig = {
        plugins: [
            Essentials, Bold, Italic, Font, Paragraph, List, Link,
            Table, TableToolbar, BlockQuote, Heading, Undo,
            Image, ImageUpload, ImageToolbar, ImageStyle, ImageCaption,
            Indent, Alignment
        ],
        toolbar: {
            items: [
                'undo', 'redo', '|',
                'heading', '|',
                'bold', 'italic', 'fontSize', 'fontColor', '|',
                'link', 'insertTable', 'blockQuote', '|',
                'bulletedList', 'numberedList', 'outdent', 'indent', '|', 'imageUpload', 'alignment'
            ],
            shouldNotGroupWhenFull: true
        },
        image: {
            toolbar: [
                'imageStyle:inline',
                'imageStyle:block',
                'imageStyle:side',
                '|',
                'toggleImageCaption',
                'imageTextAlternative'
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells'
            ]
        },
        licenseKey: 'GPL',
    };

    const config = { ...defaultConfig, ...userConfig };

    try {
        const editor = await ClassicEditor.create(element, config);

        editor.model.document.on('change:data', () => {
            element.value = editor.getData();
        });

        return editor;
    } catch (error) {
        console.error('CKEditor hatası:', error);
    }
};
