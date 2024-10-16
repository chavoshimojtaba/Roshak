function dh() {
    return $(window).height();
}
var dheight = dh();
var tm_fonts = "Dana-Medium='Dana-Medium',tahoma,sans-serif;"

var has_change = false;





tinymce.init({
    editor_selector : 'editor',
    mode : "specific_textareas",
    language: 'fa',
    content_css: HOST + "file/global/tinymce/custom/css/font.min.css", 
    language_url: HOST + "file/global/tinymce/langs/fa_IR.js",
    content_style: ".tiny_editor{padding-bottom: 0 !important; min-height:80px; font-family:'Yekan Bakh';  }*{box-shadow: none !important;}.mce-tinymce{box-shadow: none !important;}",
    body_class: 'tiny_editor',
    resize: true,
    height: 400,
    statusbar: false, 
    fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 24px 32px 36px",
    plugins: [
        "   autoresize colorpicker directionality ",
        "fullscreen hr   link lists nonbreaking",
        "paste textcolor "
    ],
    /*paste_as_text: true,*/
    directionality : "rtl",
    add_unload_trigger: false,
    toolbar1: "formatselect fontsizeselect  ",
    toolbar2: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist |  hr removeformat | outdent indent blockquote | undo redo | link unlink anchor   | forecolor   |   cut copy paste pastetext   | ltr rtl ",
    menubar: "tools",
    toolbar_items_size: 'small',
    style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        {title: 'Example 1', inline: 'span', classes: 'example1'},
        {title: 'Example 2', inline: 'span', classes: 'example2'},
        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ],

    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,

});





