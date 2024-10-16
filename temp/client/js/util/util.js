import { fetchApi } from "../api";

let toastInstance;
let toastEl;

export const Authorization = async () => {
  let l = {};
  const data = await localStorage.getItem("_auth");
  if (data !== null) {
    l = JSON.parse(data);
    l.s = true;
  } else {
    l.s = false;
  }
  return l;
};

/* export const toman = function (x) {
    return Number(String(x).replace(/[^0-9.-]+/g, "")).toLocaleString({
        style: 'currency',
        currency: 'USD',
    });
} */
export const toman = (x, reverse) => {
  reverse = reverse || false;
  if (x == 0 || x == "" || !x) {
    return 0;
  }
  if (reverse) {
    return Number(x.replace(/[^0-9.-]+/g, ""));
  } else {
    var re = "\\d(?=(\\d{" + 3 + "})+" + "$" + ")";
    return String(x).replace(new RegExp(re, "g"), "$&" + ",");
  }
};

export const owlCarousel = function (selector, options) {
  options = options || {};
  return $(selector).owlCarousel(
    Object.assign(
      {
        items: 5,
        margin: 15,
        rtl: !0,
        autoplay: !0,
        nav: !0,
        lazyLoad: true,
        responsive: { 0: { items: 2 }, 1200: { items: 5 } },
        navText: [
          '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="15" viewBox="0 0 23 15" fill="none"><path opacity="0.4" d="M16.5094 5.98503L21.0773 5.58105C22.1024 5.58105 22.9336 6.4203 22.9336 7.45541C22.9336 8.49052 22.1024 9.32976 21.0773 9.32976L16.5094 8.92578C15.7052 8.92578 15.0532 8.26744 15.0532 7.45541C15.0532 6.64201 15.7052 5.98503 16.5094 5.98503Z" fill="#33D1E7"/><path d="M1.16918 6.05835C1.24057 5.98626 1.50729 5.68158 1.75785 5.42858C3.21942 3.84395 7.03568 1.25278 9.03204 0.459782C9.33513 0.333284 10.1016 0.0639646 10.5125 0.0449219C10.9045 0.0449219 11.279 0.136055 11.6359 0.315601C12.0818 0.567238 12.4374 0.964415 12.6341 1.43232C12.7594 1.75605 12.9561 2.72859 12.9561 2.74627C13.1514 3.80859 13.2578 5.53604 13.2578 7.44575C13.2578 9.26298 13.1514 10.9197 12.9911 11.9997C12.9736 12.0187 12.7769 13.2252 12.5627 13.6387C12.1707 14.395 11.4042 14.8629 10.5839 14.8629H10.5125C9.97769 14.8452 8.85423 14.376 8.85423 14.3596C6.96429 13.5666 3.23828 11.1006 1.74033 9.46157C1.74033 9.46157 1.31735 9.0399 1.13415 8.77739C0.848572 8.39925 0.705782 7.93134 0.705782 7.46344C0.705782 6.94112 0.866084 6.45553 1.16918 6.05835Z" fill="#33D1E7"/></svg>',
          '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="15" viewBox="0 0 23 15" fill="none"><path opacity="0.4" d="M7.12732 5.98503L2.55939 5.58105C1.53427 5.58105 0.703125 6.4203 0.703125 7.45541C0.703125 8.49052 1.53427 9.32976 2.55939 9.32976L7.12732 8.92578C7.93152 8.92578 8.5835 8.26744 8.5835 7.45541C8.5835 6.64201 7.93152 5.98503 7.12732 5.98503Z" fill="#33D1E7"/><path d="M22.4675 6.05835C22.3961 5.98626 22.1294 5.68158 21.8789 5.42858C20.4173 3.84395 16.601 1.25278 14.6047 0.459782C14.3016 0.333284 13.5351 0.0639646 13.1242 0.0449219C12.7322 0.0449219 12.3578 0.136055 12.0008 0.315601C11.5549 0.567238 11.1993 0.964415 11.0026 1.43232C10.8773 1.75605 10.6807 2.72859 10.6807 2.74627C10.4853 3.80859 10.3789 5.53604 10.3789 7.44575C10.3789 9.26298 10.4853 10.9197 10.6456 11.9997C10.6631 12.0187 10.8598 13.2252 11.074 13.6387C11.466 14.395 12.2325 14.8629 13.0528 14.8629H13.1242C13.659 14.8452 14.7825 14.376 14.7825 14.3596C16.6724 13.5666 20.3984 11.1006 21.8964 9.46157C21.8964 9.46157 22.3194 9.0399 22.5026 8.77739C22.7881 8.39925 22.9309 7.93134 22.9309 7.46344C22.9309 6.94112 22.7706 6.45553 22.4675 6.05835Z" fill="#33D1E7"/></svg>',
        ],
      },
      options
    )
  );
};

export const owlCarouselGallery = function () {
  var slider_gallery = $(".slider_gallery");
  var slider_gallery_thumbs = $(".slider_gallery_thumbs");
  var syncedSecondary = true;
  owlCarousel(".slider_gallery", {
    items: 1,
    slideSpeed: 2000,
    nav: true,
    autoplay: false,
    dots: true,
    loop: true,
    responsiveRefreshRate: 200,
    responsive: {},
  }).on("changed.owl.carousel", syncPosition);

  slider_gallery_thumbs
    .on("initialized.owl.carousel", function () {
      setTimeout(function () {
        slider_gallery_thumbs.find(".owl-item").last().removeClass("current");
        slider_gallery_thumbs.find(".owl-item").first().addClass("current"); 
      }, 1000);
    })
    .owlCarousel({
      items: 10,
      dots: true,
      autoWidth: true,
      nav: true,
      smartSpeed: 200,
      margin: 4,
      slideSpeed: 500,
      slideBy: 1, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
      responsiveRefreshRate: 100,
    })
    .on("changed.owl.carousel", syncPosition2);

  function syncPosition(el) {
    //if you set loop to false, you have to restore this next line
    //var current = el.item.index;

    //if you disable loop you have to comment this block
    var count = el.item.count - 1;
    var current = Math.round(el.item.index - el.item.count / 2 - 0.5);

    if (current < 0) {
      current = count;
    }
    if (current > count) {
      current = 0;
    }

    //end block

    slider_gallery_thumbs
      .find(".owl-item")
      .removeClass("current")
      .eq(current)
      .addClass("current");
    var onscreen = slider_gallery_thumbs.find(".owl-item.active").length - 1;
    var start = slider_gallery_thumbs.find(".owl-item.active").first().index();
    var end = slider_gallery_thumbs.find(".owl-item.active").last().index();

    if (current > end) {
      slider_gallery_thumbs.data("owl.carousel").to(current, 100, true);
    }
    if (current < start) {
      slider_gallery_thumbs
        .data("owl.carousel")
        .to(current - onscreen, 100, true);
    }
  }

  function syncPosition2(el) {
    if (syncedSecondary) {
      var number = el.item.index;
      slider_gallery.data("owl.carousel").to(number, 100, true);
    }
  }
  slider_gallery_thumbs.on("click", ".owl-item", function (e) {
    e.preventDefault();
    var number = $(this).index(); 
    slider_gallery.data("owl.carousel").to(number, 300, true);
  });
};

export const select2 = function (x, template) {
  template = template || "filter-search-template";
  x = x || ".select-2";
  const inp = $(x).select2({
    dir: "rtl",
    dropdownCssClass: "bigdrop",
    language: "fa",
    minimumResultsForSearch: -1,
    theme: "filter-search-template",
  });
  inp.on("select2:open", function (e) {
    if (e.target.closest(".form-group") !== null) {
      e.target.closest(".form-group").classList.remove("has-danger");
      if (e.target.closest(".form-group").querySelector(".pristine-error")) {
        e.target
          .closest(".form-group")
          .querySelector(".pristine-error")
          .remove();
      }
    }
  });
  return inp;
};

export const parseRequestUrl = () => {
  const url = CURL.toLowerCase().split("/");
  const urlSearchParams = new URLSearchParams(window.location.search);
  const params = Object.fromEntries(urlSearchParams.entries());
  let method = "index";
  let id = "";
  if (url.length > 1) {
    method = url[1];
  }
  if (url.length > 2) {
    id = url.length > 3 ? url.slice(2) : url[2];
  }
  return {
    class: url[0],
    method,
    params,
    id,
  };
};

export const dorpdownList = () => {
  const dropDownItems = document.querySelectorAll(".dorpdown-list_sub > a");
  for (let index = 0; index < dropDownItems.length; index++) {
    dropDownItems[index].addEventListener("click", function (e) {
      e.preventDefault();
      this.classList.toggle("active");
    });
  }
};

export function slugValidation(table, btn) {
  if (document.querySelector("#inp_slug") != null) {
    document.querySelector("#inp_slug").addEventListener("blur", function (e) {
      const _this = this;
      this.value = this.value
        .trim()
        .toLowerCase()
        .replaceAll(" ", "-")
        .replaceAll(/[^a-z,-]+/g, "");

      var postalRGEX = /^([a-z])([a-z]|[-]){1,150}$/;
      this.value = this.value.toLowerCase();
      if (postalRGEX.test(_this.value)) {
        fetchApi("util/slug_validation", "GET", {
          params: {
            data: {
              slug: _this.value,
              table,
              id: document.querySelector("#inp_id").value,
            },
          },
        })
          .then(async (res) => {
            if (res.status == 0) {
              _this.focus();
              document.querySelector("#" + btn).setAttribute("disabled", true);
              toast("e", "اسلاگ نامعتبر(تکراری)");
              _this.closest(".form-group").classList.add("has-danger");
              _this.closest(".form-group").classList.remove("has-success");
              _this.classList.add("form-control-danger");
              _this.classList.remove("form-control-success");
            } else {
              document.querySelector("#" + btn).removeAttribute("disabled");
              _this.closest(".form-group").classList.remove("has-danger");
              _this.closest(".form-group").classList.add("has-success");
              _this.classList.remove("form-control-danger");
              _this.classList.add("form-control-success");
            }
          })
          .catch((err) => {
            toast("e");
          });
      } else {
        document.querySelector("#" + btn).setAttribute("disabled", true);
        toast("e", "اسلاگ نامعتبر(کاراکتر غیر مجاز)");
        _this.closest(".form-group").classList.add("has-danger");
        _this.closest(".form-group").classList.remove("has-success");
        _this.classList.add("form-control-danger");
        _this.classList.remove("form-control-success");
      }
    });
  }
}

export const loading = (selector, show) => {
  let element; 
  if (typeof selector === "string") {
    element = document.querySelector(selector);
  } else if (typeof selector.target !== "undefined") {
    element = selector.target;
  } else {
    element = selector;
  }
  element.classList.add("position-relative");
  if (show) {
    if (element.disabled) {
      element.disabled = true;
    }
    let innerHTML =
      '<div class="spinner-container" style="position: absolute;left: 0;width: 100%;top: 0;height: 100%;display: flex;align-items: center;justify-content: center;background-color: rgba(255,255,255,.5);border-radius: 8px;"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>';
    element.classList.add("position-relative");
    element.insertAdjacentHTML("beforeend", innerHTML);
  } else {
    if (element) {
      element.disabled = false;
    }
    element.querySelector(".spinner-container")
      ? element.querySelector(".spinner-container").remove()
      : false;
  }
};

export const copyToClipboard = (text) => {
  if (navigator.clipboard && navigator.clipboard) {
    return navigator.clipboard.writeText(text);
  } else if (window.clipboardData && window.clipboardData.setData) {
    return window.clipboardData.setData("Text", text);
  } else if (
    document.queryCommandSupported &&
    document.queryCommandSupported("copy")
  ) {
    var textarea = document.createElement("textarea");
    textarea.textContent = text;
    textarea.style.position = "fixed";
    textarea.style.display = "none";
    document.body.appendChild(textarea);
    textarea.select();
    try {
      return document.execCommand("copy");
    } catch (ex) {
      console.warn("Copy to clipboard failed.", ex);
    } finally {
      document.body.removeChild(textarea);
    }
  }
};

export const cardLoading = (show, el) => {
  if (!show) {
    el.querySelector(".spinner-container")
      ? el.querySelector(".spinner-container").remove()
      : false;
  } else {
    const div = document.createElement("div");
    div.setAttribute("class", "spinner-container");
    div.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`;
    el.appendChild(div);
  }
};

export const log = (msg, type) => {
  var styles = [
    "background: #0F111A",
    "border-radius: 5px",
    "font-family: Dana-Medium, tahoma, sans-serif",
    "padding: 10px",
    "font-weight: bold",
  ].join(";");
  const defMsg = {
    0: langs.msgConnectionError,
    e: langs.msgConnectionError,
    s: langs.SuccessSubmit,
  };
  type = type || 0;
  let color;
  msg = msg || defMsg[type];
  if (type === "e") {
    color = "#ff5370";
  } else if (type === "w") {
    color = "#ffcb6b";
  } else if (type === "s") {
    color = "#c3e88d";
  } else {
    color = "#ff5370";
  }
  console.log("%c" + msg, "color:" + color + ";" + styles);
};

export const toast = (type, msg) => {
  console.log(document.getElementById("liveToast"));
  if (document.getElementById("liveToast") == null) {
    const div = document.createElement("div");
    div.setAttribute(
      "class",
      ` toast-container `
    );
    div.innerHTML = `
        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header pt-1">
                <button type="button" class="btn-close m-0" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body slign-items-start">
              <div class="flex-column d-flex w-100">
                <strong class="ms-auto toast-title"></strong>
                <div class="toast-msg"></div>
                <div class="progress mt-2">
                  <div class="progress-bar" ></div>
                </div>
              </div>
            </div>
        </div>`;
    document.body.appendChild(div);
  }
  let title;
  const defMsg = {
    0: langs.msgConnectionError,
    e: langs.msgConnectionError,
    n: "ابتدا وارد حساب کاربری خود شوید",
    s: langs.SuccessSubmit,
  };
  msg = msg || defMsg[type];
  if (!toastInstance) {
    toastEl = document.getElementById("liveToast");
    toastInstance = new bootstrap.Toast(toastEl, {
      autohide: true, 
      delay: 4000,
    });
    toastEl.addEventListener('hidden.bs.toast', function () {
      document.querySelector('.toast-container').classList.remove('active');
    })
  }
  if (type == 0) {
    title = langs.error;
    msg = langs.msgConnectionError;
    toastEl.setAttribute("class", "toast toast-danger");
  } else if (type === "e") {
    title = langs.error;
    toastEl.setAttribute("class", "toast toast-danger");
  } else if (type === "w") {
    toastEl.setAttribute("class", "toast toast-warning");
    title = langs.warning;
  } else if (type === "s") {
    toastEl.setAttribute("class", "toast toast-success");
    title = langs.success;
  } else if (type === "n") {
    toastEl.setAttribute("class", "toast toast-danger");
    title = "ورود به حساب کاربری";
  }
  toastEl.querySelector(".toast-title").innerText = title;
  toastEl.querySelector(".toast-msg").innerText = msg;
  toastInstance.show();
  document.querySelector('.toast-container').classList.add('active');
};

export const alertBox = (type, message, id) => {
  let classType = "";
  let icon = "";
  message = message || langs.msgConnectionError;
  switch (type) {
    case "i": //info
      classType = "info";
      icon = `<i class="me-1 d-fex fas fa-info-circle  "></i>`;
      break;
    case "w": //warning
      classType = "warning";
      icon = `<i class="me-1 d-fex fas fa-exclamation-triangle"></i>`;
      break;
    case "s": //success
      classType = "success";
      icon = `<i class="me-1 d-fex fas fa-check-circle"></i>`;
      break;
    case "e": //error
      classType = "danger";
      icon = `<i class="me-1 d-fex fas fa-exclamation-triangle"></i>`;
      break;
    default:
      classType = "success";
      icon = `<i class="me-1 d-fex fas fa-check-circle"></i>`;
      break;
  }
  setTimeout(() => {
    var alertNode = document.querySelector(".alert");
    var alertInstance = new bootstrap.Alert(alertNode);
    if (alertInstance) {
      alertInstance.close();
    }
  }, 4000);
  if (id) {
    const div = document.createElement("div");
    div.setAttribute(
      "class",
      `alert my-2 d-flex align-items-center alert-${classType}`
    );
    div.setAttribute("role", "alert");
    div.innerHTML = `${icon} ${message}`;
    document.getElementById(id).appendChild(div);
    return true;
  } else {
    return `
            <div class="alert my-2 d-flex align-items-center alert-${classType}" role="alert">
                ${icon}
                ${message}
            </div>
        `;
  }
};

/*
 * Created     : Wed Aug 17 2022 11:29:33 AM
 * Author      : Chavoshi Mojtaba
 * Description : create slider with thumbnails (splide library)
 * return      : silder
 */
export const sliderWithThumbnail = (options) => {
  var thumbnails = document.querySelectorAll(
    options.selector + " + .thumbnails .thumbnail"
  );
  if (thumbnails.length > 0) {
    const defOptions = {
      selector: ".splide",
      width: "100%",
      height: 500,
      pagination: false,
      cover: true,
      ...options,
    };
    var splide = new Splide(defOptions.selector, defOptions);

    var current;

    for (var i = 0; i < thumbnails.length; i++) {
      initThumbnail(thumbnails[i], i);
    }

    function initThumbnail(thumbnail, index) {
      thumbnail.addEventListener("click", function () {
        splide.go(index);
      });
    }
    splide.on("mounted move", function () {
      var thumbnail = thumbnails[splide.index];
      if (thumbnail) {
        if (current) {
          current.classList.remove("is-active");
        }

        thumbnail.classList.add("is-active");
        current = thumbnail;
      }
    });
    splide.mount();
  }
}; 
/*
 * Created     : Thu Aug 18 2022 1:49:53 PM
 * Author      : Chavoshi Mojtaba
 * Description : Description
 * return      : array
 */

export function formatNumber(n) {
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

export function scrollTop() {
  window.scrollTo({ top: 0, behavior: "smooth" });
}

export function changeViewOnResize() {
  window.addEventListener("resize", function windowResize() {
    var typingTimer;
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
      if (document.documentElement.clientWidth < 768) {
        if (!document.body.classList.contains("mobile-view")) {
          location.reload();
          return;
        }
      } else {
        if (document.body.classList.contains("mobile-view")) {
          location.reload();
          return;
        }
      }
    }, 700);
  });
}

export function mainSearchBar() {
  let typingTimer;
  if (document.getElementById("main-q")) {
    document.getElementById("main-q").addEventListener("input", function (e) {
      e.preventDefault();
      document.querySelector(".header-search-form").classList.add("active");
      const _this = this;
      clearTimeout(typingTimer);
      typingTimer = setTimeout(() => {
        if (_this.value.length > 2) {
          loading("#main-q-submit-btn", 1);
          fetchApi("util/search", "GET", {
            params: { data: { q: _this.value } },
          })
            .then(async (res) => {
              let html = "";
              if (res.status) {
                const data = Object.keys(res.data);
                if (data.length > 0) {
                  for (let index = 0; index < data.length; index++) {
                    const element = res.data[data[index]];
                    switch (element.type) {
                      case "tag":
                        html += `
                                                <li>
                                                    <a class="dropdown-item rounded-1  px-2" href="${
                                                      HOST +
                                                      "search/" +
                                                      element.slug
                                                    }-tag">
                                                        ${element.title}
                                                    </a>
                                                </li>
                                                `;
                        break;
                      case "category":
                        html += `
                                                <li>
                                                    <a class="dropdown-item rounded-1  px-2" href="${
                                                      HOST +
                                                      "search/" +
                                                      element.slug
                                                    }">
                                                        ${element.title}
                                                    </a>
                                                </li>
                                                `;
                        break;
                      case "product":
                        html += `
                                                <li>
                                                    <a class="dropdown-item rounded-1  px-2" href="${
                                                      HOST + "p/" + element.slug
                                                    }">
                                                        <img src="${
                                                          HOST + element.img
                                                        }" alt="">
                                                        ${element.title}
                                                        <span class="text-primary pe-1">
                                                            مشاهده محصول
                                                        </span>
                                                    </a>
                                                </li>
                                                `;
                        break;
                      case "designer":
                        html += `
                                                <li>
                                                    <a class="dropdown-item rounded-1  px-2" href="${
                                                      HOST +
                                                      "designers/" +
                                                      element.slug
                                                    }">
                                                        <img src="${
                                                          HOST + element.img
                                                        }" alt="">
                                                        ${element.full_name}
                                                        <span class="text-primary">
                                                            مشاهده پروفایل
                                                        </span>
                                                    </a>
                                                </li>
                                                `;
                        break;
                      default:
                        html += `
                                                <li>
                                                    <a class="dropdown-item rounded-1  px-2" href="${
                                                      HOST +
                                                      "search/" +
                                                      element.slug
                                                    }">
                                                        ${element.title}
                                                    </a>
                                                </li>
                                                `;
                        break;
                    }
                  }
                } else {
                  html = ` <li>
                                        <span class="p-2 d-block">
                                        نتیجه ای یافت نشد.
                                        </span>
                                    </li>`;
                }
                document
                  .querySelector(".search-header-result")
                  .classList.add("d-flex");

                document.querySelector("#main-q_result").innerHTML = html;
              }
            })
            .catch((err) => {
              toast("e");
            })
            .finally((res) => {
              loading("#main-q-submit-btn", 0);
            });
        }
      }, 700);
    });
    $(window).click(function () {
      document
        .querySelector(".search-header-result")
        .classList.remove("d-flex");
      document.querySelector(".header-search-form").classList.remove("active");
    });

    $(".header-search-form").click(function (event) {
      event.stopPropagation();
    });
  }
}

export const currency = function () {
  $(".currency").on({
    click: function () {
      if ($(this).val().length <= 1) {
        $(this).val("");
      }
    },
    keyup: function () {
      formatCurrency($(this));
    },
    blur: function () {
      formatCurrency($(this), "blur");
    },
  });

  function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function formatCurrency(input, blur) {
    // appends $ to value, validates decimal side
    // and puts cursor back in right position.

    // get input value
    var input_val = input.val();

    // don't validate empty input
    if (input_val === "") {
      return;
    }

    // original length
    var original_len = input_val.length;

    // initial caret position
    var caret_pos = input.prop("selectionStart");

    // check for decimal
    if (input_val.indexOf(".") >= 0) {
      // get position of first decimal
      // this prevents multiple decimals from
      // being entered
      var decimal_pos = input_val.indexOf(".");

      // split number by decimal point
      var left_side = input_val.substring(0, decimal_pos);
      var right_side = input_val.substring(decimal_pos);

      // add commas to left side of number
      left_side = formatNumber(left_side);

      // validate right side
      right_side = formatNumber(right_side);

      // On blur make sure 2 numbers after decimal
      if (blur === "blur") {
        right_side += "00";
      }

      // Limit decimal to only 2 digits
      right_side = right_side.substring(0, 2);

      // join number by .
      input_val = "$" + left_side + "." + right_side;
    } else {
      // no decimal entered
      // add commas to number
      // remove all non-digits
      input_val = formatNumber(input_val);
      input_val = input_val;
    }

    // send updated string to input
    input.val(input_val);

    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
  }
};

export function formatCurrency(input = null, value = 0) {
  const orgInputVal = value === 0 ? input.value : value;
  if (orgInputVal === "") {
    return;
  }
  let input_val = formatNumber(orgInputVal);
  // input_val =  " ریال "+input_val;
  input.value = input_val;
  return orgInputVal;
}

export function isMobile() {
  return document.body.classList.contains("mobile-view") ? true : false;
}
