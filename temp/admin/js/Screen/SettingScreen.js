import { fetchApi } from "../api";
import { DataTable } from "../components/_DataTable";
import { UploadForm } from "../components/UploadForm";
import {
  LNG_ERROR,
  LNG_MSG_CONNECTION_ERROR,
  LNG_MSG_SUCCESS,
  LNG_SUCCESS,
} from "../util/consts";
import { loading, toast } from "../util/util";
import { Validator } from "../util/Validator";
export const SettingScreen = {
    calendar: (id) => {
        var CalendarApp = function () {
        this.$body = $("body");
        (this.$calendar = $("#calendar")),
            (this.$event = "#calendar-events div.calendar-events"),
            (this.$extEvents = $("#calendar-events")),
            (this.$modal = $("#my-event")),
            (this.$saveCategoryBtn = $(".save-category")),
            (this.$calendarObj = null);
        };
        (CalendarApp.prototype.onDrop = function (eventObj, date) {
        var $this = this;
        // retrieve the dropped element's stored Event Object
        var originalEventObject = eventObj.data("eventObject");
        var $categoryClass = eventObj.attr("data-class");
        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject);
        // assign it the date that was reported
        copiedEventObject.start = date;
        if ($categoryClass) copiedEventObject["className"] = [$categoryClass];
        // render the event on the calendar
        $this.$calendar.fullCalendar("renderEvent", copiedEventObject, true);
        // is the "remove after drop" checkbox checked?
        eventObj.remove();
        // if ($('#drop-remove').is(':checked')) {
        //     // if so, remove the element from the "Draggable Events" list
        // }
        }), 
        (CalendarApp.prototype.onEventClick = function (calEvent, jsEvent, view) {
            var $this = this;
            var form = $("<form></form>");
            form.append(
            "<div class='input-group flex-column'><label class='control-label'>عنوان</label><input class='form-control w-100' type=text value='" +
                calEvent.title +
                "' /></div>"
            );
            form.append(
            "<div class='input-group flex-column mt-2'><label class='control-label'>مقدار</label><input class='form-control w-100' name='val' type=text value='" +
                calEvent.value +
                "' /><span class='input-group-btn mt-2 mx-0'><button type='submit' class='btn btn-success waves-effect waves-light'><i class='fa fa-check'></i> ثبت</button></span></div>"
            );
            $this.$modal.modal({ backdrop: "static" });
            $this.$modal
            .find(".delete-event")
            .show()
            .end()
            .find(".save-event")
            .hide()
            .end()
            .find(".modal-body")
            .empty()
            .prepend(form)
            .end()
            .find(".delete-event")
            .unbind("click")
            .click(async function () {
                const res = await fetchApi("setting/delete_event", "DELETE", {
                params: {
                    id: calEvent.id,
                },
                });

                if (res.status) {
                $this.$calendarObj.fullCalendar("removeEvents", function (ev) {
                    return ev._id == calEvent._id;
                });
                $this.$modal.modal("hide");
                } else {
                toast("e", "خطا در برقراری ارتباط    ");
                }
            });
            $this.$modal.modal("show");
            $this.$modal.find("form").on("submit", async function () {
            calEvent.title = form.find("input[type=text]").val();
            calEvent.val = form.find("input[name=val]").val();
            const res = await fetchApi(
                "setting/update_event/" + calEvent.id,
                "PUT",
                {
                body: calEvent,
                }
            );
            if (res.status) {
                $this.$calendarObj.fullCalendar("updateEvent", calEvent);
                $this.$modal.modal("hide");
            } else {
                toast("e", "خطا در برقراری ارتباط    ");
            }

            return false;
            });
        }), 
        (CalendarApp.prototype.onSelect = function (start, end, allDay) {
            
            var $this = this;
            $this.$modal.modal({
            backdrop: "static",
            });
            var form = $("<form></form>");
            form.append("<div class='row'></div>");
            form
            .find(".row")
            .append(
                "<div class='col-md-6'><div class='form-group'><label class='control-label'>عنوان</label><input class='form-control' placeholder='عنوان را وارد کنید' type='text' name='title'/></div></div>"
            )
            .append(
                "<div class='col-md-6'><div class='form-group'><label class='control-label'>رنگ ایتم</label><select class='form-control' name='category'></select></div></div>"
            )
            .append(
                "<div class='col-md-12'><div class='form-group mt-2'><label class='control-label'>مقدار</label><input class='form-control' placeholder='مقدار را وارد کنید' type='text' name='url'/></div></div>"
            )
            .find("select[name='category']")
            .append("<option value='bg-danger'>قرمز</option>")
            .append("<option value='bg-success'>سبز</option>")
            .append("<option value='bg-purple'>بنفش</option>")
            .append("<option value='bg-primary'>بنفش</option>")
            .append("<option value='bg-pink'>صورتی</option>")
            .append("<option value='bg-info'>آبی</option>")
            .append("<option value='bg-warning'>نارنجی</option></div></div>");
            $this.$modal
            .find(".delete-event")
            .hide()
            .end()
            .find(".save-event")
            .show()
            .end()
            .find(".modal-body")
            .empty()
            .prepend(form)
            .end()
            .find(".save-event")
            .unbind("click")
            .click(function () {
                form.submit();
            });
            $this.$modal.modal("show");

            $this.$modal.find("form").on("submit", async function (e) {
                e.preventDefault();
                var title = form.find("input[name='title']").val();
                var value = form.find("input[name='url']").val();
                var categoryClass = form
                    .find("select[name='category'] option:checked")
                    .val();
                if (title !== null && title.length != 0) {
                    const data = {
                    title,
                    start: end._d.toLocaleDateString(),
                    value,
                    end: end._d.toLocaleDateString(),
                    className: categoryClass,
                    };
                    const res = await fetchApi("setting/add_event", "POST", {
                    body: data,
                    });
                    if (res.status) {
                    $this.$calendarObj.fullCalendar("renderEvent", res.data, true);
                    $this.$modal.modal("hide");
                    } else {
                    toast("e", "خطا در برقراری ارتباط    ");
                    }
                } else {
                    alert("You have to give a title to your event");
                }
                return false;
            });
            // $this.$calendarObj.fullCalendar("unselect");
        }),
        (CalendarApp.prototype.enableDrag = function () {
            //init events
            $(this.$event).each(function () {
            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()), // use the element's text as the event title
            };
            // store the Event Object in the DOM element so we can get to it later
            $(this).data("eventObject", eventObject);
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true, // will cause the event to go back to its
                revertDuration: 0, //  original position after the drag
            });
            });
        }); 
        (CalendarApp.prototype.init = function () {
            this.enableDrag(); 
            var $this = this;
            $this.$calendarObj = $this.$calendar.fullCalendar({
                slotDuration:"00:15:00" ,
                defaultView: "month",
                handleWindowResize: true,
                isJalaali: true,
                locale: "fa",
                header: {
                    left: "prev,next today",
                    center: "",
                    right: "title",
                }, 
                events: async function (start, end, timezone, callback) {
                const res = await fetchApi("setting/get_events", "GET", {
                    params: {},
                }); 
                if (res.status) {
                    var events = [];
                    var obj = res.data;
                    $(obj).each(function () {
                    events.push({
                        className: $(this).attr("className"),
                        value: $(this).attr("value"),
                        id: $(this).attr("id"),
                        title: $(this).attr("title"),
                        start: $(this).attr("start"),
                    });
                    });
                    if (callback) callback(events);
                }
                },
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!
                eventLimit: true, // allow "more" link when too many events
                selectable: true,
                drop: function (date) {
                $this.onDrop($(this), date);
                },
                eventRender: function (eventObj, $el) {
                //   $el.popover({
                //     title: eventObj.title,
                //     content: eventObj.description,
                //     trigger: "hover",
                //     placement: "top",
                //     container: "body",
                //   });
                },
                select: function (start, end, allDay) {
                $this.onSelect(start, end, allDay);
                },
                eventClick: function (calEvent, jsEvent, view) {
                $this.onEventClick(calEvent, jsEvent, view);
                },
            });
        }),
        ($.CalendarApp = new CalendarApp()),
        ($.CalendarApp.Constructor = CalendarApp);
        $.CalendarApp.init();
  },
  site: () => {
    const form = document.getElementById("form-seo-submit");
    document
      .getElementById("submit_seo")
      .addEventListener("click", function (e) {
        e.preventDefault();
        const editorContent = tinymce.get("inp_home_desc").getContent();
        tinymce.DOM.addClass(".mce-container-body", "myclass");
        var valid = Validator("form-seo-submit").validate();
        if (editorContent.length === 0) {
          editorValidation("form-group_desc");
          valid = false;
        }
        if (valid) {
          const formData = new FormData(form);
          const formDataEntries = Object.fromEntries(formData);
          formDataEntries.home_desc = tinymce.get("inp_home_desc").getContent();
          loading(1, e);
          fetchApi(`setting/seo_update`, "PUT", { body: formDataEntries })
            .then((res) => {
              if (res.status) {
                toast("s", LNG_MSG_SUCCESS, LNG_SUCCESS);
              } else {
                toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
              }
              loading(0, e);
            })
            .catch((err) => {
              loading(0, e);
              toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            });
        }
      });
    const form_social = document.getElementById("form-social-submit");
    document
      .getElementById("submit_social")
      .addEventListener("click", function (e) {
        e.preventDefault();
        var valid = Validator("form-social-submit").validate();
        if (valid) {
          const formData = new FormData(form_social);
          const formDataEntries = Object.fromEntries(formData);
          loading(1, e);
          fetchApi(`setting/social_update`, "PUT", { body: formDataEntries })
            .then((res) => {
              if (res.status) {
                toast("s", LNG_MSG_SUCCESS, LNG_SUCCESS);
              } else {
                toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
              }
              loading(0, e);
            })
            .catch((err) => {
              loading(0, e);
              toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            });
        }
      });
  },
  user_stories: async () => {
    new DataTable({
      filter: false,
      cell: [
        {
          key: "pic",
          title: "تصویر",
          type: "file",
          filetype: "image",
        },
        {
          key: "fullname",
          title: "نام و نام خ",
          editable: "text",
        },
        {
          key: "sub_title",
          title: "sub title",
        },
        {
          key: "text",
          title: "متن",
        },
        {
          key: "edit_user_stories",
          title: "ویرایش",
          type: "public-btn",
          options: {
            class: "btn-light-info text-info edit-user_stories",
            value: '<i class="ti-pencil"></i>',
          },
        },
        {
          key: "del",
          title: "حذف",
          type: "public-btn",
          attr: [
            'data-resource="setting"',
            'data-ext-url="delete_user_stories"',
          ],
          options: {
            class: "btn-light-danger text-danger del-user_stories",
            value: '<i class="far fa-trash-alt"></i>',
          },
        },
      ],
      api: (data) => {
        return fetchApi(`setting/user_stories`, "GET", {
          params: { data },
        });
      },
      actions: {
        edit_user_stories(data) {
          window.open($_Curl + "add_user_stories/" + data.id, "_blank");
        },
      },
    });
  },
  footer_links: async () => {
    const editModal = document.getElementById("editModal");
    const form = document.getElementById("form-footer_link-submit");
    const modal = new bootstrap.Modal(editModal);
    const inpTitle = document.querySelector("#inp_title");
    const inpUrl = document.querySelector("#inp_url");
    const inpColumn = document.querySelector("#inp_pid");
    const submitBtn = document.getElementById("submit_footer_links");
    new DataTable({
      filter: false,
      perPage: 100,
      cell: [
        {
          key: "title",
          title: "عنوان",
          editable: "text",
        },
        {
          key: "column",
          title: "ستون",
        },
        {
          key: "createAt",
          sort: true,
          title: "تاریخ",
          type: "date",
        },
        {
          key: "edit_footer_links",
          title: "ویرایش",
          type: "public-btn",
          options: {
            class: "btn-light-info text-info edit-footer_links",
            value: '<i class="ti-pencil"></i>',
          },
        },
        {
          key: "del",
          title: "حذف",
          type: "public-btn",
          attr: [
            'data-resource="setting"',
            'data-ext-url="delete_footer_links"',
          ],
          options: {
            class: "btn-light-danger text-danger del-footer_links",
            value: '<i class="far fa-trash-alt"></i>',
          },
        },
      ],
      api: (data) => {
        return fetchApi(`setting/footer_links`, "GET", {
          params: { data },
        });
      },
      actions: {
        edit_footer_links(data) {
          inpTitle.value = data.title;
          document.querySelector("#inp_id").value = data.id;
          inpUrl.value = data.url;
          inpColumn.value = data.pid;
          modal.show();
        },
      },
    });
    editModal.addEventListener("hide.bs.modal", function (e) {
      form.reset();
    });
    submitBtn.addEventListener("click", function (e) {
      e.preventDefault();
      var valid = Validator("form-footer_link-submit").validate();

      if (valid) {
        const formData = new FormData(form);
        const formDataEntries = Object.fromEntries(formData);
        let method = "POST";
        let url = `setting/add_footer_links`;
        loading(1, e);
        if (formDataEntries.id && parseInt(formDataEntries.id) > 0) {
          method = "PUT";
          url = `setting/update_footer_links/${formDataEntries.id}`;
        } else delete formDataEntries.id;
        fetchApi(url, method, { body: formDataEntries })
          .then((res) => {
            form.reset();
            if (res.status) {
              toast("s", LNG_MSG_SUCCESS, LNG_SUCCESS);
              window.location.href = $_Burl + "setting/footer_links";
            } else {
              toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            }
            loading(0, e);
          })
          .catch((err) => {
            loading(0, e);
            toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
          });
      }
    });
    document
      .getElementById("submit_column")
      .addEventListener("click", function (e) {
        e.preventDefault();
        var valid = Validator("form-column-submit").validate();

        if (valid) {
          const formData = new FormData(
            document.getElementById("form-column-submit")
          );
          const formDataEntries = Object.fromEntries(formData);
          let method = "PUT";
          let url = `setting/update_footer_columns`;
          loading(1, e);
          fetchApi(url, method, { body: formDataEntries })
            .then((res) => {
              form.reset();
              if (res.status) {
                toast("s", LNG_MSG_SUCCESS, LNG_SUCCESS);
                window.location.href = $_Burl + "setting/footer_links";
              } else {
                toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
              }
              loading(0, e);
            })
            .catch((err) => {
              loading(0, e);
              toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            });
        }
      });
  },
  header_links: async () => {
    new DataTable({
      filter: false,
      perPage: 100,
      cell: [
        {
          key: "title",
          title: "عنوان",
          editable: "text",
        },
        {
          key: "url",
          title: "لینک",
        },
        {
          key: "createAt",
          sort: true,
          title: "تاریخ",
          type: "date",
        },
        {
          key: "edit_header_links",
          title: "ویرایش",
          type: "public-btn",
          options: {
            class: "btn-light-info text-info edit-header_links",
            value: '<i class="ti-pencil"></i>',
          },
        },
        {
          key: "del",
          title: "حذف",
          type: "public-btn",
          attr: [
            'data-resource="setting"',
            'data-ext-url="delete_header_links"',
          ],
          options: {
            class: "btn-light-danger text-danger del-header_links",
            value: '<i class="far fa-trash-alt"></i>',
          },
        },
      ],
      api: (data) => {
        return fetchApi(`setting/header_links`, "GET", {
          params: { data },
        });
      },
      actions: {
        edit_header_links(data) {
          location.href = HOST + "admin/setting/add_header_links/" + data.id;
          // modal.show();
        },
      },
    });
  },
  add_header_links: async () => {
    const form = document.getElementById("form-header_links-submit");

    const submitBtn = document.getElementById("submit_header_links");
    submitBtn.addEventListener("click", function (e) {
      e.preventDefault();
      var valid = Validator("form-header_links-submit").validate();
      if (valid) {
        const formData = new FormData(form);
        const formDataEntries = Object.fromEntries(formData);
        let method = "POST";
        let url = `setting/add_header_links`;
        loading(1, e);
        if (formDataEntries.id && parseInt(formDataEntries.id) > 0) {
          method = "PUT";
          url = `setting/update_header_links/${formDataEntries.id}`;
        } else delete formDataEntries.id;
        fetchApi(url, method, { body: formDataEntries })
          .then((res) => {
            // form.reset();
            if (res.status) {
              toast("s", LNG_MSG_SUCCESS, LNG_SUCCESS);
              window.location.href = $_Burl + "setting/header_links";
            } else {
              toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            }
            loading(0, e);
          })
          .catch((err) => {
            loading(0, e);
            toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
          });
      }
    });
  },
  add_user_stories: async (id) => {
    id = parseInt(id);
    if (id > 0) {
      document.getElementById("inp_id").value = id;
    } else {
      id = 0;
    }
    const user_storiesFile = new UploadForm({
      formats: ".jpg,.png",
      title: "تصویر  ",
      max: 1,
      files: {},
      filesType: "image",
      btnTitle: "افزودن تصویر",
    });
    const submitBtn = document.getElementById("submit_user_stories");
    const form = document.getElementById("form-user_stories-submit");

    submitBtn.addEventListener("click", function (e) {
      e.preventDefault();
      const images = user_storiesFile.getFiles();
      const imagesKeys = Object.keys(images);
      var valid = Validator("form-user_stories-submit").validate();
      if (imagesKeys.length <= 0) {
        toast("e", "لطفا تصویر را انتخاب کنید", LNG_ERROR);
        valid = false;
      }
      if (valid) {
        const formData = new FormData(form);
        formData.append("pic", images[imagesKeys[0]].file);
        const formDataEntries = Object.fromEntries(formData);
        let method = "POST";
        let url = `setting/add_user_stories`;
        loading(1, e);
        if (formDataEntries.id && parseInt(formDataEntries.id) > 0) {
          method = "PUT";
          url = `setting/update_user_stories/${formDataEntries.id}`;
        } else delete formDataEntries.id;
        fetchApi(url, method, { body: formDataEntries })
          .then((res) => {
            form.reset();
            if (res.status) {
              toast("s", LNG_MSG_SUCCESS, LNG_SUCCESS);
              window.location.href = $_Burl + "setting/user_stories";
            } else {
              toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            }
            loading(0, e);
          })
          .catch((err) => {
            loading(0, e);
            toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
          });
      }
    });
  },
  public_links: async () => {
    new DataTable({
      filter: false,
      cell: [
        {
          sort: true,
          key: "title",
          title: "عنوان",
          editable: "text",
        },
        {
          key: "type",
          sort: true,
          title: "نوع",
        },
        {
          key: "url",
          title: "لینک",
        },
        {
          key: "edit_public_links",
          title: "ویرایش",
          type: "public-btn",
          options: {
            class: "btn-light-info text-info edit-public_links",
            value: '<i class="ti-pencil"></i>',
          },
        },
        {
          key: "del",
          title: "حذف",
          type: "public-btn",
          attr: [
            'data-resource="setting"',
            'data-ext-url="delete_public_links"',
          ],
          options: {
            class: "btn-light-danger text-danger del-public_links",
            value: '<i class="far fa-trash-alt"></i>',
          },
        },
      ],
      api: (data) => {
        return fetchApi(`setting/public_links`, "GET", {
          params: { data },
        });
      },
      actions: {
        edit_public_links(data) {
          window.open($_Curl + "add_public_links/" + data.id, "_blank");
        },
      },
    });
  },
  add_public_links: async (id) => {
    id = parseInt(id);
    if (id > 0) {
      document.getElementById("inp_id").value = id;
    } else {
      id = 0;
    }
    const submitBtn = document.getElementById("submit_public_links");
    const form = document.getElementById("form-public_links-submit");
    submitBtn.addEventListener("click", function (e) {
      e.preventDefault();
      var valid = Validator("form-public_links-submit").validate();

      if (valid) {
        const formData = new FormData(form);
        const formDataEntries = Object.fromEntries(formData);
        let method = "POST";
        let url = `setting/add_public_links`;
        loading(1, e);
        if (formDataEntries.id && parseInt(formDataEntries.id) > 0) {
          method = "PUT";
          url = `setting/update_public_links/${formDataEntries.id}`;
        } else delete formDataEntries.id;
        fetchApi(url, method, { body: formDataEntries })
          .then((res) => {
            if (res.status) {
              toast("s", LNG_MSG_SUCCESS, LNG_SUCCESS);
              // window.location.href = $_Burl + 'setting/public_links';
              // form.reset();
            } else {
              toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            }
            loading(0, e);
          })
          .catch((err) => {
            loading(0, e);
            toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
          });
      }
    });
  },
};

// $(document).ready(function () {
//     var calendar = $('#calendar').fullCalendar({
//         editable: true,
//         events: "fetch-event.php",
//         displayEventTime: false,
//         eventRender: function (event, element, view) {
//             if (event.allDay === 'true') {
//                 event.allDay = true;
//             } else {
//                 event.allDay = false;
//             }
//         },
//         selectable: true,
//         selectHelper: true,
//         select: function (start, end, allDay) {
//             var title = prompt('Event Title:');

//             if (title) {
//                 var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
//                 var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");

//                 $.ajax({
//                     url: 'add-event.php',
//                     data: 'title=' + title + '&start=' + start + '&end=' + end,
//                     type: "POST",
//                     success: function (data) {
//                         displayMessage("Added Successfully");
//                     }
//                 });
//                 calendar.fullCalendar('renderEvent',
//                         {
//                             title: title,
//                             start: start,
//                             end: end,
//                             allDay: allDay
//                         },
//                 true
//                         );
//             }
//             calendar.fullCalendar('unselect');
//         },

//         editable: true,
//         eventDrop: function (event, delta) {
//                     var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
//                     var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
//                     $.ajax({
//                         url: 'edit-event.php',
//                         data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
//                         type: "POST",
//                         success: function (response) {
//                             displayMessage("Updated Successfully");
//                         }
//                     });
//                 },
//         eventClick: function (event) {
//             var deleteMsg = confirm("Do you really want to delete?");
//             if (deleteMsg) {
//                 $.ajax({
//                     type: "POST",
//                     url: "delete-event.php",
//                     data: "&id=" + event.id,
//                     success: function (response) {
//                         if(parseInt(response) > 0) {
//                             $('#calendar').fullCalendar('removeEvents', event.id);
//                             displayMessage("Deleted Successfully");
//                         }
//                     }
//                 });
//             }
//         }
//     });
// });
