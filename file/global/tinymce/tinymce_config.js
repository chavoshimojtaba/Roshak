function dh() {
    return $(window).height();
}
var dheight = dh();
var tm_fonts = "BBadr=BBadr;" +
        "BBaran=BBaran;" +
        "BBardiya=BBardiya;" +
        "BCompset=BCompset;" +
        "BDavat=BDavat;" +
        "BElham=BElham;" +
        "BEsfehanBold=BEsfehanBold;" +
        "BFantezy=BFantezy;" +
        "BFarnaz=BFarnaz;" +
        "BFerdosi=BFerdosi;" +
        "BHamid=BHamid;" +
        "BHelal=BHelal;" +
        "BHoma=BHoma;" +
        "BJadidBold=BJadidBold;" +
        "BJalal=BJalal;" +
        "BKoodakBold=BKoodakBold;" +
        "BKourosh=BKourosh;" +
        "BLotus=BLotus;" +
        "BMahsa=BMahsa;" +
        "BMehrBold=BMehrBold;" +
        "BMitra=BMitra;" +
        "BMorvarid=BMorvarid;" +
        "BNarm=BNarm;" +
        "BNasimBold=BNasimBold;" +
        "BSetarehBold=BSetarehBold;" +
        "BShiraz=BShiraz;" +
        "BSinaBold=BSinaBold;" +
        "BTabassom=BTabassom;" +
        "BTehran=BTehran;" +
        "BTitrBold=BTitrBold;" +
        "BTitrTGEBold=BTitrTGEBold;" +
        "BVahidBold=BVahidBold;" +
        "BYagut=BYagut;" +
        "BYas=BYas;" +
        "BYekan=BYekan;" +
        "BZar=BZar;" +
        "BZiba=BZiba;" +
        "Arial=arial,helvetica,sans-serif;" +
        "Arial Black=arial black,avant garde;" +
        "Book Antiqua=book antiqua<Plug>PeepOpenalatino;" +
        "Comic Sans MS=comic sans ms,sans-serif;" +
        "Courier New=courier new,courier;" +
        "Georgia=georgia<Plug>PeepOpenalatino;" +
        "Helvetica=helvetica;" +
        "Impact=impact,chicago;" +
        "Symbol=symbol;" +
        "Tahoma=tahoma,arial,helvetica,sans-serif;" +
        "Terminal=terminal,monaco;" +
        "Times New Roman=times new roman,times;" +
        "Trebuchet MS=trebuchet ms,geneva;" +
        "Verdana=verdana,geneva;" +
        "Webdings=webdings;" +
        "Wingdings=wingdings,zapf dingbats";

var has_change = false;



tinymce.PluginManager.add('image_gallery', function (editor, url) {
    editor.addButton('image_gallery', {
        icon: 'image',
        onclick: function () {
            editor.windowManager.open({
                title: 'انتخاب تصویر',
                width: 430,
                height: 350,
                buttons: [
                    {
                        text: 'افزودن تصویر',
                        onclick: function(){
                            img_link = $(".image_preview img").attr("src");
                            if(img_link === "" || typeof img_link === "undefined"){
                                alert("تصویری انتخاب نشده است.");
                                return false;
                            }

                            width  = $("#image_width").val();
                            if(width === "") width = 350;

                            height = $("#image_height").val();
                            title  = $("#image_title").val();

                            img = '<img src="'+img_link+'" width="'+width+'" height="'+height+'" title="'+title+'">';
                            tinymce.activeEditor.execCommand('mceInsertContent', false, img);

                            parent.tinyMCE.activeEditor.windowManager.close(window);
                        }
                    },
                    {
                        text: 'خروج',
                        onclick: 'close'
                    }
                ],
                body: [
                    {
                        type: 'button',
                        name: 'button',
                        label: ' ',
                        text: 'انتخاب تصویر',
                        onclick: function (e) {
                            window.open($_Burl+ "file/index/image/editor","گالری تصاویر",'left=5, top=200, width=1024,height=600,status=yes,scrollbars=yes,directories=no,menubar=no,resizable=yes,toolbar=no');
                        }
                    },
                    {
                        type   : 'container',
                        label  : 'عنوان تصویر',
                        html   : '<input type="text" id="image_title" placeholder="عنوان تصویر" class="mce-textbox mce-abs-layout-item" style="float:left;width: 100%">'
                    },
                    {
                        type   : 'container',
                        label  : 'اندازه تصویر',
                        html   : '<div class="mce-container-body mce-abs-layout" style="width: 297px; height: 30px;">'
                                +'<div class="mce-abs-end"></div>'
                                +'<input placeholder="عرض" name="image_width" id="image_width" maxlength="5" size="3" class="mce-textbox mce-abs-layout-item mce-first" aria-label="Width" style="left: 0px; top: 0px; width: 50px; height: 28px;">'
                                +'<span class="mce-widget mce-label mce-abs-layout-item" style="line-height: 16px; left: 65px; top: 7px; width: 7px; height: 16px;">x</span>'
                                +'<input placeholder="ارتفاع" name="image_height" id="image_height" maxlength="5" size="3" class="mce-textbox mce-abs-layout-item" aria-label="Height" style="left: 77px; top: 0px; width: 50px; height: 28px;">'
                                +'</div>'
                    },
                    {
                        type   : 'container',
                        label  : '',
                        html   : '<div class="image_preview"></div>',
                        minWidth: 160,
                        minHeight: 160
                    },
                ]
            });
        }
    });
});



tinymce.init({
    editor_selector : 'editor',
    mode : "specific_textareas",
    language: 'fa',
    language_url: HOST + "file/global/tinymce/langs/fa_IR.js",
    resize: true, 
    content_css: HOST + "file/global/tinymce/custom/css/font.min.css", 
    fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 24px 32px 36px",
    plugins: [
        "image_gallery advlist  anchor autoresize colorpicker directionality ",
        "fullscreen hr image imagetools importcss insertdatetime link lists media nonbreaking",
        "paste preview table textcolor "
    ],
    /*paste_as_text: true,*/
    directionality : "rtl",
    paste_as_text: true,
    add_unload_trigger: false,
    toolbar1: "formatselect fontsizeselect  ",
    toolbar2: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | table | hr removeformat | outdent indent blockquote | undo redo | link unlink anchor image_gallery | forecolor backcolor |subscript superscript |  cut copy paste pastetext | fullscreen | ltr rtl | insertfile insertimage",
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
    /* Start excel */
    /*Excel copy-paste Utility :Starts*/
    paste_retain_style_properties: "all", //keep excel style
    paste_strip_class_attributes: "none", //keep excel style
    //paste_remove_spans : true,
    /*Excel copy-paste Utility :Ends*/
    /* End excel*/
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
    setup : function(ed) {
        const _this = this;
        let max_chars = 25000;
        if (document.getElementById(_this.id).hasAttribute('data-max')) {
            max_chars =document.getElementById(_this.id).getAttribute('data-max');
        }
        ed.on('KeyDown', function (e) {
            var allowedKeys = [8, 37, 38, 39, 40, 46]; // backspace, delete and cursor keys
            if (allowedKeys.indexOf(e.keyCode) != -1) return true;

            if ( $(ed.getBody()).text().length > max_chars){
                e.preventDefault();
                e.stopPropagation();
                var el =document.querySelector('#'+_this.id+' + .invalid-feedback');
                el.classList.add('d-flex');
                el.innerText = `حداکثر تعداد کاراکتر ${max_chars} میباشد`;
                setTimeout(() => {
                    el.classList.remove('d-flex');
                }, 5000);

            }
            return
        });
        return
        ed.onKeyDown.add(function(ed, evt) {

          if ( $(ed.getBody()).text().length > ed.getParam('max_char')){
            console.log(ed.getParam('max_char'));
            e.preventDefault();
            e.stopPropagation();
            return false;
          }

        });
    }

});
function CountCharacters() {
    var body = tinymce.get("summary").getBody();
    var content = tinymce.trim(body.innerText || body.textContent);
    return content.length;
};





