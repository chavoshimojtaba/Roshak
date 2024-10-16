import { fetchApi } from "../api";
import { CAROUSEL_BTNS } from "../util/consts";
import { owlCarousel, select2 } from "../util/util";
import { productActionBtns } from "../util/util_product";
export const HomeScreen = {
    render() {
        select2();
        productActionBtns();
        // return
        $(".favorite-slider").owlCarousel({
            items: 5,
            margin: 15,
            center: true,
            animateOut: "slideInLeft",
            animateIn: "slideOutRight",
            rtl: true,
            autoplay: true,
            dots: true,
            nav: true,
            loop: true,
            responsive: {
                0: {
                    items: 1.5,
                },
                992: {
                    items: 5,
                },
            },
            navText: CAROUSEL_BTNS,
        });

        $(".blog-slider").owlCarousel({
            center: true,
            animateOut: "slideInLeft",
            animateIn: "slideOutRight",
            margin: 25,
            rtl: true,
            autoWidth: true,
            autoplay: true,
            nav: true,
            dots: true,
            loop: true,
            responsive: {
                0: {
                    items: 1.5,
                },
                800: {
                    items: 4,
                },
                1200: {
                    items: 5,
                },
            },
            navText: CAROUSEL_BTNS,
        });

        $(".comment-slider").owlCarousel({
            items: 1,
            margin: 45,
            rtl: true,
            nav: true,
            dots: true,
            loop: true,
            responsive: {
                0: {
                    items: 1,
                    center: true,
                    margin: 0,
                },
                1200: {
                    items: 1,
                },
            },
            navText: CAROUSEL_BTNS,
        });

        $(".category-slider").owlCarousel({
            items: 6,
            margin: 15,
            rtl: true,
            autoplay: true,
            dots: false,
            nav: true,
            responsive: {
                0: {
                    items: 2,
                },
                577: {
                    items: 3,
                },
                768: {
                    items: 4,
                },
                992: {
                    items: 5,
                },
                1200: {
                    items: 6,
                },
            },
            navText: [
                "<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
                "<i class='d-block icon fs-4 icon-arrow-left4'></i>",
            ],
        });

        $(".profile-slider").owlCarousel({
            items: 1,
            margin: 15,
            rtl: true,
            dots: false,
            nav: true,
            responsive: {
                0: {
                    items: 1,
                },
                1200: {
                    items: 1,
                },
            },
            navText: [
                "<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
                "<i class='`d-block icon fs-4 icon-arrow-left4`'></i>",
            ],
        });

        owlCarousel(".profile-slider-mobile", {
            items: 2,
            responsive: {
                0: {
                    items: 2,
                },
                577: {
                    items: 3,
                },
                768: {
                    items: 4,
                },
                992: {
                    items: 5,
                },
            },
        });

        document.addEventListener("click", (e) => {
            if (!e.target.closest(".header-search-form")) {
                document
                    .querySelector(".search-header-result")
                    .classList.remove("show");
            }
        });

        var myModal = document.querySelector(".calendar-modal");
        if (myModal !== null) { 
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
                console.log(this);
                $this.$calendarObj = $this.$calendar.fullCalendar({
                    locale: "fa",
                    isJalaali: true,
                    eventLimit: 3,
                    defaultView: "month",
                    slotDuration: "00:15:00",
                    handleWindowResize: true,
                    showNonCurrentDates: false,
                    header: {
                        left: " prev,next today",
                        center: "",
                        right: "title",
                    },
                    buttonIcons: {
                        next: "d-block icon fs-4 icon-arrow-left4",
                        prev: "d-block icon fs-4 icon-arrow-right-14",
                        prevYear: "right-double-arrow",
                        nextYear: "left-double-arrow",
                    },
                    events: async function (start, end, timezone, callback) {
                        const res = await fetchApi("util/get_events", "GET", {
                            params: {},
                        });
                        if (res.status) {
                            var events = [];
                            var obj = res.data;
                            $(obj).each(function () {
                                events.push({
                                    className: $(this).attr("className"),
                                    _url: $(this).attr("value"),
                                    title: $(this).attr("title"),
                                    start: $(this).attr("start"),
                                });
                            });
                            if (callback) callback(events);
                        }
                    },
                    eventRender: function (eventObj, $el) { 
                        $el.popover({
                            title: eventObj.title,
                            content: eventObj.title,
                            customClass: "calendar-popover",
                            delay: { show: 100, hide: 300 },
                            trigger: "hover",
                            placement: "top",
                            container: "body",
                        });
                    },
                    eventClick: function (event) {
                        if (event._url) {
                            window.open(event._url, "_blank");
                        }
                    },
                });
            }),
            ($.CalendarApp = new CalendarApp()),
            ($.CalendarApp.Constructor = CalendarApp);
            document.querySelector(".calendar-btn").addEventListener("click", function (e) {
                const div = document.createElement("div");
                div.className = "modal-backdrop calendar-backdrop  fade show ";
                document.body.appendChild(div);
                myModal.classList.add("d-flex");
                $.CalendarApp.init();
                setTimeout(() => {
                    $('.calendar-modal').click(function (event)  {
                        if (document.querySelector('.calendar-modal').classList.contains('d-flex')) {
                            if($(event.target).closest('#calendar').length == 0 && !$(event.target).is('#calendar')) {
                                document.querySelector('.modal-backdrop').remove();
                                myModal.classList.remove("d-flex");
                            }     
                        }
                    });
                }, 1000);
            });
            document.querySelector(".btn-close").addEventListener("click", function (e) {
                document.querySelector('.modal-backdrop').remove()
                myModal.classList.remove("d-flex"); 
            });
        }
    },
};
