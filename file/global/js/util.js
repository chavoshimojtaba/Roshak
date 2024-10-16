var timeouts = [];
var curr_types = {
    1: 'Fiat',
    2: 'Crypto',
    3: 'E-Money',
};
var fileExts = {
    'webp' : 'image',
    'jpeg' : 'image',
    'jpg' : 'image',
    'png' : 'image',
    'gif' : 'image',
    'mp4' : 'video',
    'zip' : 'doc',
    'pdf' : 'doc',
    'xls' : 'doc',
    'xlsx' : 'doc',
    'csv' : 'doc',
    'txt' : 'doc',
};
function addDefaultFF(dir, id) {
    if (document.querySelector('.upload-file__items')) {
        const file__items = document.querySelector('.upload-file__items');
        const img = `
        <div class="upload-file__item mb-3" id="upload-file__item-${id}">
            <img src="${$_url}${dir}" data-key="${id}" data-dir="${dir}">
            <button
                type="button"
                class="
                btn btn-light-danger btn-circle btn-sm
                d-inline-flex
                align-items-center
                justify-content-center
                m-0
                del-item
                upload-file__action-del">
                <i class="del-item ti-trash text-danger"></i>
            </button>
        </div>
        `;
        file__items.innerHTML += img;
        delFileListenerFF();
    }
}
function delFileListenerFF() {
    if (document.querySelector('.upload-file__items')) {
        const delBtns = document.querySelectorAll(
            '.upload-file__items .upload-file__action-del'
        );
        for (let index = 0; index < delBtns.length; index++) {
            const element = delBtns[index];
            element.addEventListener('click', function (e) {
                e.preventDefault();
                console.log('delete parent by class');
                element.closest('.upload-file__item').remove();
            });
        }
    }
}

function getFileFF(file, is_editor, type) {
    type = type || 'image';
    let fileEl = '';
    switch (type) {
        case 'image':
            fileEl = `<img src="${$_url}${file.dir}" data-key="${file.id}" data-dir="${file.dir}">`;
            break;
        case 'video':
            fileEl = `
                <video width="100%" height="240" controls  data-key="${file.id}" data-dir="${file.dir}">
                    <source src="${$_url}${file.dir}" type="video/mp4">
                    <source src="movie.ogg" type="video/ogg">
                    Your browser does not support the video tag.
                </video>
            `;
            break;
        default:
            fileEl = `<a class="file_box doc bg-info p-3 text-white"  data-key="${file.id}" data-dir="${file.dir}" href="${$_url}${file.dir}" target="_blank"><i class="mdi mdi-file-document text-white mb-1"></i>document</a>`;
            break;
    }
    if (is_editor === 'single') {
        if (document.querySelector('.upload-file__items')) {
            const file__items = document.querySelector(
                '.upload-file__items.active'
            )
                ? document.querySelector('.upload-file__items.active')
                : document.querySelector('.upload-file__items');
            const img = `
            <div class="upload-file__item mb-3" id="upload-file__item-${file.id}">
                ${fileEl}
                <button
                    type="button"
                    data-key="${file.id}"

                    class="
                    btn btn-light-danger btn-circle btn-sm
                    d-inline-flex
                    align-items-center
                    justify-content-center
                    m-0
                    del-item
                    upload-file__action-del">
                    <i class="del-item ti-trash text-danger"  data-key="${file.id}"></i>
                </button>
            </div>
            `;
            file__items.innerHTML += img;
            const delBtns = file__items.querySelectorAll(
                '.upload-file__action-del'
            );
            for (let index = 0; index < delBtns.length; index++) {
                const element = delBtns[index];
                element.addEventListener('click', function (e) {
                    e.preventDefault();
                    element.closest('.upload-file__item').remove();
                });
            }
        }
    } else if (is_editor === 'multi') {
        if (document.querySelector('.upload-file__items')) {
            const file__items = document.querySelector(
                '.upload-file__items.active'
            )
                ? document.querySelector('.upload-file__items.active')
                : document.querySelector('.upload-file__items');
            const img = `
            <div class="upload-file__item mb-3" id="upload-file__item-${file.id}">
                ${fileEl}
                <button
                    type="button"
                    data-key="${file.id}"

                    class="
                    btn btn-light-danger btn-circle btn-sm
                    d-inline-flex
                    align-items-center
                    justify-content-center
                    m-0
                    del-item
                    upload-file__action-del">
                    <i class="del-item ti-trash text-danger"  data-key="${file.id}"></i>
                </button>
            </div>
            `;
            file__items.innerHTML += img;
            const delBtns = file__items.querySelectorAll(
                '.upload-file__action-del'
            );
            for (let index = 0; index < delBtns.length; index++) {
                const element = delBtns[index];
                element.addEventListener('click', function (e) {
                    e.preventDefault();
                    element.closest('.upload-file__item').remove();
                });
            }
            return true;
        }
    } else {
        if (document.querySelector('.image_preview')) {
            const img = `<img src="${$_url}${file.dir}" class="d-block  w-100 ">`;
            document.querySelector('.image_preview').innerHTML = img;
            return true;
        }
    }
}
function getFileType(file) {
    const extSplit = file.split('.');
    if (extSplit.length > 0) {
        let ext = extSplit[extSplit.length-1];
        if (fileExts[ext]) {
            return fileExts[ext];
        }
    }
    return 'undifined';
}
function getFFItems(cls) {
    cls = cls || '';
    const files = document.querySelectorAll(cls + ' .upload-file__item img');
    let res = {};
    if (files.length > 0) {
        for (let index = 0; index < files.length; index++) {
            const id = files[index].getAttribute('data-key');
            const dir = files[index].getAttribute('data-dir');
            res[id] = dir;
        }
    }
    return res;
}
function resetFF() {
    document.querySelectorAll('.upload-file__items').innerHTML = '';
}

function loader(thic) {
    if (thic.hasAttribute('disabled')) {
        thic.innerHTML = loader_txt;
    } else {
        loader_txt = thic.innerHTML;
        thic.innerHTML =
            loader_txt +
            '<svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"\
        width="40px" height="40px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">\
        <path opacity="0.5" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946\
            s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634\
            c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/>\
        <path fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0\
            C22.32,8.481,24.301,9.057,26.013,10.047z">\
            <animateTransform attributeType="xml"\
            attributeName="transform"\
            type="rotate"\
            from="0 20 20"\
            to="360 20 20"\
            dur="1s"\
            repeatCount="indefinite"/>\
            </path>\
        </svg>';
    }
    thic.toggleAttribute('disabled');
}

function showPass() {
    var i = document.getElementsByClassName('show-pass');
    for (let index = 0; index < i.length; index++) {
        i[index].onclick = function (e) {
            if (typeof this.attributes.for != undefined) {
                if (
                    document.getElementById(this.attributes.for.value).type ===
                    'password'
                ) {
                    document.getElementById(this.attributes.for.value).type =
                        'text';
                } else {
                    document.getElementById(this.attributes.for.value).type =
                        'password';
                }
            } else {
            }
        };
    }
}

function CHECK_PASS(str, forget) {
    forget = forget || 'no';
    if (forget == 'forget') {
        var res = {
            lower: document.getElementById('forget-rule-lower'),
            upper: document.getElementById('forget-rule-upper'),
            number: document.getElementById('forget-rule-number'),
            len: document.getElementById('forget-rule-len'),
            character: document.getElementById('forget-rule-character'),
        };
    } else {
        var res = {
            lower: document.getElementById('rule-lower'),
            upper: document.getElementById('rule-upper'),
            number: document.getElementById('rule-number'),
            len: document.getElementById('rule-len'),
            character: document.getElementById('rule-character'),
        };
    }
    var ret = true;
    if (str.match(/[a-z]/g)) {
        res.lower.classList.add('active');
    } else {
        ret = false;
        res.lower.classList.remove('active');
    }

    if (str.match(/[A-Z]/g)) {
        res.upper.classList.add('active');
    } else {
        ret = false;
        res.upper.classList.remove('active');
    }

    if (str.match(/[0-9]/g)) {
        res.number.classList.add('active');
    } else {
        ret = false;
        res.number.classList.remove('active');
    }

    if (str.match(/[^a-zA-Z\d]/g)) {
        res.character.classList.add('active');
    } else {
        ret = false;
        res.character.classList.remove('active');
    }

    if (str.length >= 8) {
        res.len.classList.add('active');
    } else {
        ret = false;
        res.len.classList.remove('active');
    }
    return ret;
}

function sideNav(id) {
    if (document.getElementsByClassName('menu_item')) {
        var items = document.getElementsByClassName('menu_item');
        for (var index = 0; index < items.length; index++) {
            var element = items[index];
            element.classList.remove('active');
        }
        if (document.getElementById('menu_' + id)) {
            document.getElementById('menu_' + id).classList.add('active');
        }

        if (id == 'tc_alpha_generation' || id == 'analyst_views') {
            document.getElementById('menu_collapsible-body').style.cssText =
                'display: block;';
        }
    }
    $('.collapsible').collapsible();
}
function fileSize(e, max) {
    var count = 1;
    var files = e.currentTarget.files;
    for (var x = 0; x < files.length; x++) {
        var maxAllowedSize = max * 1024 * 1024;
        if (e.currentTarget.files[x].size > maxAllowedSize) {
            e.currentTarget.value = '';
            return false;
        }
    }
    return true;
}
function LOGIN_FORM_VALIDATE() {
    var $form = $('form');
    $.validator.addMethod('letters', function (value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]*$/);
    });
    $form.validate({
        rules: {
            first_name: {
                required: true,
                minlength: 3,
                letters: true,
            },
            last_name: {
                required: true,
                minlength: 3,
                letters: true,
            },
            email: {
                required: true,
                email: true,
            },
        },
        messages: {
            first_name:
                'Please specify your name (only letters and spaces are allowed)',
            last_name:
                'Please specify your last name (only letters and spaces are allowed)',
            email: 'Please specify a valid email address',
        },
    });
}
function numeric(inputtxt) {
    var numbers = /^[0-9]+$/;
    if (inputtxt.value.match(numbers)) {
        alert('Your Registration number has accepted....');
        document.form1.text1.focus();
        return true;
    } else {
        alert('Please input numeric characters only');
        document.form1.text1.focus();
        return false;
    }
}
function _modal(id) {
    $('#' + id).modal('toggle');
    $('#' + id + ' .modal-content').append(
        '<div class="preloader" id="preloader" >\
                        <div class="loader">\
                            <div class="loader__figure"></div>\
                            <p class="loader__label">مدیریت طرح پیچ</p>\
                        </div>\
                    </div>'
    );
}

function scroll_top() {
    window.scroll({
        top: 0,
        left: 0,
        behavior: 'smooth',
    });
}

function dataTable(id) {
    id = id || 'table-rows';
    id = id || false;
    $('#' + id).DataTable({
        dom: 'Bfrtip',
        pageLength: 15,
        destroy: true,
    });
}

function now() {
    var today = new Date();
    var date =
        today.getFullYear() +
        ' ' +
        (today.getMonth() + 1) +
        ' ' +
        today.getDate();
    var time =
        today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
    return date + ' ' + time;
}

function _NON(_jsn, noneData_func) {
    txt = _jsn.text;
    var _subTxt = _jsn.subTxt;
    var _txtBtn = 'بازگشت';
    if (typeof _jsn.btn !== 'undefined') {
        _txtBtn = _jsn.btn;
    }
    var _icon = 'error';
    if (typeof _jsn.icon !== 'undefined') {
        _icon = _jsn.icon;
    }
    document.querySelector(_jsn.id).innerHTML =
        document.querySelector(_jsn.id).innerHTML +
        '<div id="none-data" class="col s12 flex-center bounceInRight animatable">\
            <div class="card-panel " style="width: 100%;padding:8px 15px;border-radius:10px;">\
                <div style="padding:0 ;" class="col s12 flex-center">\
                <img src="' +
        $_url +
        'file/site/images/error.svg">\
                    <br>\
                </div>\
                <div class="col s12 grey-text text-darken-2 center-align" style="padding:5px 0;">\
                    <h6 style="font-size: 13px;text-align: center;">' +
        txt +
        '</h6>\
                    <label style="font-size: 13px;text-align: center;">' +
        _subTxt +
        '</label>\
                </div>\
                <div class="col s12 grey-text text-darken-2 flex-center">\
                    <a class=" " id="none-data-btn">' +
        _txtBtn +
        '</a>\
                </div>\
            </div>\
        </div>';

    if (typeof _jsn.btntype !== 'undefined' && _jsn.btntype !== 'link') {
        document
            .getElementById('none-data-btn')
            .addEventListener('click', function (e) {
                e.preventDefault();
                noneData_func();
            });
    } else {
        if (typeof _jsn.value !== 'undefined') {
            var value = _jsn.value;
        } else {
            var value = {};
        }
        document.getElementById('none-data-btn').classList.add('hide');
    }
    // anim();
}

function isMobile() {
    return (
        typeof window.orientation !== 'undefined' ||
        navigator.userAgent.indexOf('IEMobile') !== -1
    );
}

function _toman(x) {
    return Number(String(x).replace(/[^0-9.-]+/g,"")).toLocaleString({
        style: 'currency',
        currency: 'USD',
    });
}


function loading_btn(thic, type) {
    type = type || 'none';
    thic.toggleAttribute('disabled');
    thic.classList.toggle('submit-btn');
    thic.classList.toggle('loading');
    if (type == 'w') {
        thic.classList.toggle('trans');
    }
    if (type == 'g') {
        thic.classList.toggle('g');
    }
    if (type == 'hide') {
        thic.classList.remove('g');
        thic.classList.remove('trans');
    }
}

function loading(id) {
    id = id || 'none';
    if (id != 'none') {
        if (
            typeof document.querySelector('#' + id + ' #loading-overlay') ==
                'undefined' ||
            document.querySelector('#' + id + ' #loading-overlay') == null
        ) {
            var div = document.createElement('div');
            div.setAttribute('id', 'loading-overlay');
            div.setAttribute('class', 'loading-overlay hide');
            div.innerHTML =
                ' <div class="loading-box flex-center-column">\
                                <div class="loader">\
                                    <div class="inner one"></div>\
                                    <div class="inner two"></div>\
                                    <div class="inner three"></div>\
                                </div>\
                            </div>\
                        </div>';
            document.getElementById(id).classList.add('relative');

            document.getElementById(id).appendChild(div);
        }
        if (
            document
                .querySelector('#' + id + ' #loading-overlay')
                .classList.contains('hide')
        ) {
            document
                .querySelector('#' + id + ' #loading-overlay')
                .classList.remove('hide');
        } else {
            document
                .querySelector('#' + id + ' #loading-overlay')
                .classList.add('hide');
        }
    } else {
        if (
            document
                .getElementById('loading-overlay')
                .classList.contains('hide')
        ) {
            document.getElementById('loading-overlay').classList.remove('hide');
        } else {
            document.getElementById('loading-overlay').classList.add('hide');
        }
    }
}

function loading_admin(id) {
    id = id || 'none';
    if (id != 'none') {
        if (
            document
                .querySelector('#' + id + ' #preloader')
                .classList.contains('show')
        ) {
            document
                .querySelector('#' + id + ' #preloader')
                .classList.remove('show');
        } else {
            document
                .querySelector('#' + id + ' #preloader')
                .classList.add('show');
        }
    } else {
        if (document.getElementById('preloader').classList.contains('show')) {
            document.getElementById('preloader').classList.remove('show');
        } else {
            document.getElementById('preloader').classList.add('show');
        }
    }
}

function _enter(id, func_run) {
    document.getElementById(id).onkeyup = function (event) {
        event.preventDefault();
        if (event.target.type != 'textarea') {
            if (event.keyCode === 13) {
                func_run(event);
            }
        }
    };
}

function _materialboxed() {
    $('.materialboxed').materialbox();
}

function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : event.keyCode;
    if (
        !(
            charCode == 8 || // backspace
            charCode == 46 || // delete
            (charCode >= 35 && charCode <= 40) || // arrow keys/home/end
            (charCode >= 48 && charCode <= 57) || // numbers on keyboard
            (charCode >= 96 && charCode <= 105)
        ) // number on keypad
    ) {
        event.preventDefault(); // Prevent character input
    }
}

function num() {
    if (document.getElementsByClassName('num')) {
        var _elNum = document.getElementsByClassName('num');
        for (var j = 0; j < _elNum.length; j++) {
            _elNum[j].onkeydown = function (evt) {
                isNumberKey(evt);
            };
        }
    }
}

function switch_step(steps, step) {
    for (var j = 0; j < steps.length; j++) {
        if (steps[j].classList.contains(step)) {
            steps[j].classList.add('open');
        } else {
            steps[j].classList.remove('open');
        }
    }
}

function switch_form(form) {
    if (document.getElementsByClassName('authentication_box')) {
        var _elNum = document.getElementsByClassName('authentication_box');
        for (var j = 0; j < _elNum.length; j++) {
            if (_elNum[j].classList.contains(form)) {
                _elNum[j].classList.remove('hide');
            } else {
                _elNum[j].classList.add('hide');
            }
        }
    }
}

function downloadURI(uri, name) {
    var ifrm = document.createElement('iframe');
    ifrm.setAttribute('src', $_url + uri);
    ifrm.style.width = '0px';
    ifrm.style.height = '0px';
    document.body.appendChild(ifrm);
}

function switchMode(switchElement, checkedBool) {
    if (
        (checkedBool && !switchElement.isChecked()) ||
        (!checkedBool && switchElement.isChecked())
    ) {
        switchElement.setPosition(true);
        switchElement.handleOnchange(true);
    }
}

function currenyType(type) {
    if (type == 1) {
        return 'Fiat Currency';
    } else if (type == 2) {
        return 'Crypto Currency';
    } else if (type == 1) {
        return 'E-Money Currency';
    }
}

function mask() {
    $('.date-inputmask').inputmask({
        mask: 'yyyy/mm/dd',
        autoUnmask: true,
        removeMaskOnSubmit: true,
    }),
        $('.mobile-mask').inputmask({
            mask: '99999999999',
            autoUnmask: true,
            removeMaskOnSubmit: true,
        }),
        $('.phone-inputmask').inputmask({
            mask: '099-99999999',
            autoUnmask: true,
            removeMaskOnSubmit: true,
        }) /*

    $(".international-inputmask").inputmask("+9(999)999-9999"),  */,
        $('.card-inputmask').inputmask({
            mask: '9999 9999 9999 9999',
            autoUnmask: true,
            removeMaskOnSubmit: true,
        }),
        $('.currency-inputmask').inputmask({
            mask: '9,999,999,999 ریال',
            autoUnmask: true,
            removeMaskOnSubmit: true,
        }),
        $('.num-mask').inputmask({
            mask: '9999',
            autoUnmask: true,
            removeMaskOnSubmit: true,
        });

    $('.number-mask').inputmask({
        alias: 'numeric',
        autoUnmask: true,
        removeMaskOnSubmit: true,
    });

    $('.email-mask').inputmask({
        alias: 'email',
    });

    $('.melicode-mask').inputmask({
        mask: '9999999999',
        autoUnmask: true,
        removeMaskOnSubmit: true,
    });
}

function ValidateEmail(inp) {
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (inp.value.match(mailformat)) {
        return true;
    } else {
        inp.focus();
        return false;
    }
}

function select() {
    $('select').formSelect();
}

function getPercentOfNumber($number, $percent) {
    return ($number / 100) * $percent;
}

function collapsible() {
    $('.collapsible').collapsible();
}

function newModal(id, options) {
    options = options || {};
    var _cls_ele = document.querySelectorAll('#' + id + ' .modal-close');
    for (var index = 0; index < _cls_ele.length; index++) {
        _cls_ele[index].onclick = function (e) {
            e.preventDefault();
            document.getElementById(id).classList.remove('open');
            document.getElementById('modal-overlay').style.cssText =
                'z-index: 1002; display: none; opacity: 0;';
            document.getElementById(id).style.cssText =
                'z-index: 1003; display: none; opacity: 0; bottom: -100%;';
        };
    }
    var _modal = {
        close: function () {
            document.getElementById(id).classList.remove('open');
            document.getElementById('modal-overlay').style.cssText =
                'z-index: 1002; display: none; opacity: 0;';
            document.getElementById(id).style.cssText =
                'z-index: 1003; display: none; opacity: 0; bottom: -100%;';
        },
        open: function () {
            document.onkeydown = function (evt) {
                evt = evt || window.event;
                if (evt.keyCode == 27) {
                    document.getElementById(id).classList.remove('open');
                    document.getElementById('modal-overlay').style.cssText =
                        'z-index: 1002; display: none; opacity: 0;';
                    document.getElementById(id).style.cssText =
                        'z-index: 1003; display: none; opacity: 0; bottom: -100%;';
                }
            };
            document.getElementById(id).classList.add('open');
            document.getElementById('modal-overlay').style.cssText =
                'z-index: 1002; display: block; opacity: 0.5; ';
            document.getElementById(id).style.cssText =
                'z-index: 1003; display: block; opacity: 1; top: 10%; ';
        },
        destroy: function () {
            document.getElementById(id).classList.remove('open');
            document.getElementById('modal-overlay').style.cssText =
                'z-index: 1002; display: none; opacity: 0;';
            document.getElementById(id).style.cssText =
                'z-index: 1003; display: none; opacity: 0; bottom: -100%;';
        },
    };
    return _modal;
}

function farsi_nm2en(str) {
    str = str + '';
    str = str.replace(/Û°/gi, '0');
    str = str.replace(/Û±/gi, '1');
    str = str.replace(/Û²/gi, '2');
    str = str.replace(/Û³/gi, '3');
    str = str.replace(/Û´/gi, '4');
    str = str.replace(/Ûµ/gi, '5');
    str = str.replace(/Û¶/gi, '6');
    str = str.replace(/Û·/gi, '7');
    str = str.replace(/Û¸/gi, '8');
    str = str.replace(/Û¹/gi, '9');
    return str;
}

function toman(n, type) {
    if (typeof n == 'undefined' || n == '0' || n == null) {
        return 0;
    }

    if (typeof type == 'undefined' || n == '0' || n == null) {
        type = 0;
    }

    n = parseInt(n) / 10;
    a = Amounts(Math.round(n), type);
    return a;
}

function anim(element, animationName, callback) {
    const node = document.querySelector(element);
    node.classList.add('animated', animationName);

    function handleAnimationEnd() {
        node.classList.remove('animated', animationName);
        node.removeEventListener('animationend', handleAnimationEnd);

        if (typeof callback === 'function') callback();
    }

    node.addEventListener('animationend', handleAnimationEnd);
}

function toast(msg, type, clear) {
    type = type || 'primary';
    clear = clear || 0;
    if (clear == 'stop') {
        $('.toast').addClass('hide');
    }
    msg = msg || 'خطا در ارتباط با سرور';
    M.toast({ html: msg, displayLength: 4000 });
    switch (type) {
        case 's':
            $('.toast').css('background', 'rgba(27, 94, 32,.97)');
            break;
        case 'e':
            $('.toast').css('background', 'rgba(239, 83, 80,.97)');
            break;
        case 'w':
            $('.toast').css('background', 'rgba(239, 108, 0,.97)');
            break;
        default:
            $('.toast').css('background', 'rgba(50, 50, 50,.97)');
            break;
    }
}

function toast2(msg, type) {
    type = type || 'i';
    switch (type) {
        case 's':
            _type = 'success';
            header = 'Successfull';
            break;
        case 'e':
            _type = 'error';
            header = 'Error';
            break;
        case 'w':
            _type = 'warning';
            header = 'Warning';
            break;
        default:
            _type = 'info';
            header = 'Info';
            break;
    }
    $.toast({
        heading: header,
        text: msg,
        position: 'top-right',
        icon: _type,
        hideAfter: 3000,
        stack: 6,
    });
}

function date_picker() {
    var elems = document.querySelectorAll('.datepicker');
    var instances = M.Datepicker.init(elems, {
        yearRange: 100,
        format: 'yyyy-mm-dd',
        autoClose: true,
    });
}

function timer(timeLeft, func) {
    var elem = document.getElementById('timer-alert');
    var timerId = setInterval(countdown, 1000);
    timeouts.push(timerId);
    function countdown() {
        if (timeLeft == -1) {
            clearTimeout(timerId);
            func();
        } else {
            elem.innerHTML = timeLeft;
            timeLeft--;
        }
    }
}

function clear_timer() {
    var elem = document.getElementById('timer-alert');
    for (var i = 0; i < timeouts.length; i++) {
        clearTimeout(timeouts[i]);
    }
    elem.innerHTML = 0;
}

function reverse(s) {
    return s.split('').reverse().join('');
}

function loadjscssfile(filename, filetype) {
    if (filetype == 'js') {
        //if filename is a external JavaScript file
        var fileref = document.createElement('script');
        fileref.setAttribute('type', 'text/javascript');
        fileref.setAttribute('src', filename);
        if (typeof fileref != 'undefined') document.body.appendChild(fileref);
    } else if (filetype == 'css') {
        //if filename is an external CSS file
        var fileref = document.createElement('link');
        fileref.setAttribute('rel', 'stylesheet');
        fileref.setAttribute('type', 'text/css');
        fileref.setAttribute('href', filename);
        if (typeof fileref != 'undefined')
            document.getElementsByTagName('head')[0].appendChild(fileref);
    }
}

function isEmpty(obj) {
    for (var key in obj) {
        if (obj.hasOwnProperty(key)) return false;
    }
    return true;
}

$(document).ready(function () {
    if (document.querySelectorAll('.bt-switch').length > 0) {
        $(
            ".bt-switch input[type='checkbox'], .bt-switch input[type='radio']"
        ).bootstrapSwitch();
        $('.radio-switch').on('switch-change', function () {
            $('.radio-switch').bootstrapSwitch('toggleRadioState');
        }),
        $('.radio-switch').on('switch-change', function () {
            $('.radio-switch').bootstrapSwitch(
                'toggleRadioStateAllowUncheck'
            );
        }),
        $('.radio-switch').on('switch-change', function () {
            $('.radio-switch').bootstrapSwitch(
                'toggleRadioStateAllowUncheck',
                !1
            );
        });
    }
});
