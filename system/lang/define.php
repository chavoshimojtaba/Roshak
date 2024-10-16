<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Tehran');

define ('PATTERN_RED_STAR','<span class="redStar">*</span>');
define ("PATTERN_SELECT",'<select name="{name}" class="form-control {class} field_{name}" id="{id}" aria-add="{add}" json=\'{json}\' {attr} aria-js=\'{js}\' ness="{ness}">{option}</select><span class="inf_{name} _inf">{inf}</span>');
define ("PATTERN_INPUT",'<input type="{type}" maxlength="{maxlength}" aria-data="{func}" name="{name}" zero="{zero}" aria-add="{add}" lang="{lang}" aria-js=\'{js}\' class="field_{name} form-control {class} " aria-json=\'{json}\' value="{value}" id="{id}" {attr} ness="{ness}" {_checked} placeholder="{placeholder}" autocomplete="{autocomplete}"  /><span class="inf_{name} _inf">{inf}</span>');
define ("PATTERN_CHECKBOX",'<input type="{type}" name="{name}" class="{class} field_{name}" aria-add="{add}" value="{value}" id=""  lang="{lang}" aria-js=\'{js}\' aria-json=\'{json}\' {attr} ness="{ness}" {_checked} />');
define ('CHECKED','checked="checked"');
define ('SELECTED','SELECTED');
define ('PATTERN_RADIO_POS','<li class="{name}_{i}"><label class="pointer" title="{title}">{input}<span class="caption_inp_radio">{title}</span></label></li>');
define ('PATTERN_LIST_INPUT','<ul class="list_inp {class}" id="{id}" aria-ul="">{li}</ul>{inf}');
define ('DOT','.');
define ('DDOT',':');
define ('PATTERN_TEXTAREA', '<textarea class="field_{name} form-control {class}"  aria-add="{add}" name="{name}" aria-json=\'{json}\'  lang="{lang}" aria-js="{js}" {attr} ness="{ness}">{value}</textarea>{inf}');
define ('PATTERN_SUBMIT','<input type="{type}" name="{name}" class="field_{name} btn btn-{class} {add_class}" value="{value}" {attr} aria-js=\'{js}\'  />{inf}');
define ('PATTERN_FORM',' <form method="post" action="{action}" enctype="{enctype}" aria-check="{check}" name="{name}" id="{id}" {attr} aria-js=\'{js}\'>{content}</form>');
define ('PATTERN_INDEXTAB','<li><a aria-active="true">{title}</a></li></li>');
define ('PATTERN_CTAB','<div class="item_tab"><h2>{title}</h2>{content}</div>');
define ('PATTERN_LINK','<a href="{url}" title="{title}"  {attr} ><span>{title}</span></a>');
define ("PATTERN_CAPTCHA",'<div class="pos_captcha"> حاصل عبارت چیست<br><input type="{type}" name="{name}" class="inp_captcha {class} form-control " value="{value}" {attr} ness="{ness}" maxlength="2" size="8" lang="{lang}" />
        &nbsp;&nbsp;&nbsp;=&nbsp;&nbsp; <img src="{host}admin/cap" class="img_captcha img_c_{name}" />
        <span class="reload_captcha pointer" aria-img="{name}" onClick="reloadCaptcha(this)" style="font-size:12px" >[نمایش تصویر جدید]</span></div>'
);
define ("PATTERN_INPUT_FILE",'
        <div class="clearfix"><button name="{name}" type="button" class="btn btn-default {class}" {attr} ness="{ness}">{value}</button></div>
        <div class="clearfix"><div class="alert alert-warning CWF"><strong>توجه :</strong> فرمت فایل ارسالی{format}</div></div>
        <div class="clearfix {loading_class}"></di><ol class="list_upload">{IMG}</ol>');

define ("PATTERN_INPUT_IMAGE",'<hr><div class="alert alert-{text_class}">{text}</div>button name="{name}" type="button" class="btn btn-default {class}" {attr} ness="{ness}">{value}</button>
        <div class="alert alert-warning CWF"><strong>توجه :</strong> فرمت فایل ارسالی{format}</div>');

define ("PATTERN_INPUT_MULTI_FILE",'<input type="file" name="{name}[]" class="form-control{class}" {attr} ness="{ness}" />
        <span class="warning_msg"><strong>توجه :</strong> فرمت فایل ارسالی{format}</span>');

define ('_NOCASH_','?k='.nocash());
define ('_NOCASH_URL_',  nocash_url());
function nocash(){return date("YmdHis");}
function nocash_url(){return date("YmdHis").substr(str_shuffle('012345678'), 0, 8);}

define ('PATTERN_INF','<{type} class="{class}" {attr}>{text}</{type}> ');
define ('PATTERN_FORM_UPLOAD','<form id="{id}" class="no_dis" action="{action}"><input type="reset" name="reset" class="reset no_dis" /><input type="file" name="file" class="{class} no_dis" aria-form="{id}" /></form>');
define ('PATTERN_FORM_UPLOAD_IMAGE','<form id="{id}" class="no_dis" action="{action}"><input type="reset" name="reset" class="reset no_dis" /><input type="file" name="file" class="{class} no_dis" aria-form="{id}" /></form>');

define ('PATTERN_REDCOLOR','<span class="redStar">{text}</span>');
define ('LANG_ALERT','اخطار');
define ('_MSG_DO_DEL_ITEM','آیا از حذف این آیتم مطمئن هستید ؟');
define ('LANG_YES','بلی');
define ('LANG_NO','خیر');
define ('PATTERN_ALERT_DELETE','<div class="panel panel-red"><div class="panel-heading">'.LANG_ALERT.'</div><div class="panel-body"><div class="clear"></div>
        <div class="row-fluid "><h5 class="alert-warning" style="padding:5px 15px; margin-top:0px;"><i class="fa fa-warning"></i>{extra_desc}</h5></div>
        <p class="font13">'._MSG_DO_DEL_ITEM.'</p> </div><div class="panel-footer"><a href="{y}" class="btn btn-default Color_black">'.LANG_YES.'</a>
        <a href="{n}" class="btn btn-primary Color_white" style="margin-right: 20px;">'.LANG_NO.'</a></div></div>');

define ('PATTERN_PDATE','<input type="{type}" maxlength="{maxlength}" aria-data="{func}" name="{name}" aria-add="{add}" lang="{lang}" aria-js="{js}" class="form-control {class}" aria-json=\'{json}\' value="{value}" {attr} ness="{ness}" {_checked} /><i class="fa fa-calendar"></i><span class="inf_{name} _inf">{inf}</span>');

/*------------------------------------------------------------------------------
 * PATTERN BOOTSATRAP
 * ---------------------------------------------------------------------------*/

define ('ICON_IMG_PUBLISH','<i class="fa fa-check-circle"></i>');
define ('ICON_IMG_EDIT','<i class="fa fa-pencil-square-o"></i>');
define ('ICON_SETTING','<i class="fa fa-cogs"></i>');
define ('ICON_ADD_USER','<i class="fa fa fa-user-plus"></i>');
define ('ICON_IMG_DETAIL','<i class="fa fa-bars"></i>');
define ('ICON_IMG_TRASH','<i class="fa fa-trash-o"></i>');
define ('ICON_IMG_UPLOAD','<i class="fa fa-upload"></i>');
define ('ICON_IMG_PIC','<i class="fa fa-file-image-o"></i>');
define ('ICON_IMG_ATTACH','<i class="fa fa-paperclip font16" ></i>');
define ('PATTERN_PAGE_HEADER','<h1 class="page-header">{text}</h1>');


/*
 * FORM
 */

define ('PATTERN_TEL',' <input type="text" maxlength="8" aria-data="{func}" name="{number_name}" aria-add="{add}" lang="{lang}" aria-js=\'{js}\' class="form-control pull-right tel_number field_{number_name} " value="{number_value}" {attr} ness="{ness}" />
    <input type="text" maxlength="4" aria-data="{func}" name="{code_name}" aria-add="{add}" lang="{lang}" aria-js=\'{js}\' class="form-control pull-right tel_code field_{code_name} " value="{code_value}" {attr} ness="{ness}" />
   ');
define ('PATTERN_BTN_UPLOAD','<button type="button" aria-js="{js}" aria-file="{name}" aria-grab="{grab}" class="_btn btn_upload {trigger}" aria-single="{single}">{icon} {title}</button> <div class="{class_res} res_{name}">{res}</div> <div class="{class_res} to_res_{name}">{to_res}</div> <span class="format_file">{format}</span>');
