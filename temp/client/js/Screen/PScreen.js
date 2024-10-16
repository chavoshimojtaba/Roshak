import { fetchApi } from "../api";
import {
  Authorization,
  alertBox,
  loading,
  owlCarousel,
  owlCarouselGallery,
  toast,
} from "../util/util";
import { SubmitForm } from "../components/_Forms";
import { Pagination } from "../components/_Pagination";
import { productActionBtns } from "../util/util_product";

export const PScreen = {
  render() {
    const member = Authorization();
    productActionBtns();
    const pub_id = document.querySelector("#pub_id").value;
    Pagination.Init(document.getElementById("pager"), {
      size: document.getElementById("pager").getAttribute("data-total"),
      limit: 10,
      page: document.getElementById("pager").getAttribute("data-page"),
      clickHandler: (p) => {
        window.open(
          window.location.href.split("?")[0] +
            "?page=" +
            p +
            "#main-comment-form",
          "_self"
        );
      },
      step: 3,
    });
    if (document.querySelector("#submit-main-form") != null) {
      document
        .querySelector("#submit-main-form")
        .addEventListener("click", function (e) {
          SubmitForm(e, {
            hasFile: true,
            validation: false,
            values: { pid: 0, pbid: pub_id },
            id: "main-comment-form",
            api: {
              POST: "comment",
            },
            onSuccess: function () {
              document.querySelector("#main-comment-form").reset();
              document.querySelector(".alert-box").classList.remove("d-none");
              setTimeout(() => {
                document.querySelector(".alert-box").classList.add("d-none");
              }, 3000);
            },
          });
        });
    }
    var _els = document.querySelectorAll(".input-rate input");
    for (var index = 0; index < _els.length; index++) {
      _els[index].addEventListener("click", function (e) {
        const id = document.querySelector("#pub_id").value;
        const rate = e.currentTarget.value;
        loading(".rating-box", 1);
        fetchApi("member/rating", "POST", { body: { id, rate: rate } })
          .then((res) => {
            if (res.status) {
              for (let m = 0; m < _els.length; m++) {
                if (_els[m].value > rate) {
                  _els[m].classList.remove("active");
                } else {
                  _els[m].checked = false;
                  _els[m].classList.add("active");
                }
              }
              document.querySelector("#rating-data").innerText = res.data.text;
            } else {
              throw new Error(res.msg);
            }
          })
          .catch((err) => {
            toast(err.message, "e");
          })
          .finally(() => {
            loading(".rating-box", 0);
          });
      });
    }
    const rate = document
      .querySelector(".input-rate")
      .getAttribute("data-rating");
    if (parseInt(rate) > 0) {
      let arr = [];
      for (var i = -1, l = _els.length; ++i !== l; arr[i] = _els[i]);
      const _elss = arr.reverse();
      for (var index = 0; index < parseInt(rate); index++) {
        _elss[index].checked = false;
        _elss[index].classList.add("active");
      }
    }
    function commentReplyHandler() {
      var _els = document.getElementsByClassName("submit-reply");
      for (var index = 0; index < _els.length; index++) {
        _els[index].addEventListener("click", function (e) {
          e.preventDefault();
          const _this = this;
          const pid = this.getAttribute("data-id");
          if (this.closest("div").querySelector("input") != null) {
            SubmitForm(e, {
              hasFile: false,
              validation: true,
              values: { pid, pbid: pub_id },
              id: this.closest(".send-response").id,
              api: {
                POST: "comment",
              },
              onSuccess: function () {
                _this.closest(".actions-container").innerHTML = alertBox(
                  "s",
                  "نظر با موفقیت ثبت شد و در صورت تایید مدیریت در سایت منتشر میشود."
                );
              },
              onError: function () {
                toast(0);
              },
            });
          }
        });
      }
    }
    var _likeEls = document.getElementsByClassName("like-comment");
    for (var m = 0; m < _likeEls.length; m++) {
      _likeEls[m].addEventListener("click", function (e) {
        e.preventDefault();
        member.then((res) => {
          if (res.s) {
            const cid = this.getAttribute("data-id");
            loading(e, 1);
            fetchApi("comment/like", "POST", { body: { cid } })
              .then((res) => {
                if (res.status) {
                  const span = document.querySelector(".likes-" + cid);
                  span.innerText =
                    span.innerText > 0 ? parseInt(span.innerText) + 1 : 1;
                }
              })
              .catch((err) => {
                toast(err.message, "e");
              })
              .finally(() => {
                loading(e, 0);
              });
          } else {
            toast("n");
          }
        });
      });
    }
    member.then((res) => {
      console.log(res);
      if (res.s) {
        const pic = HOST + res.p;
        var _els = document.getElementsByClassName("btn-comment-reply");
        for (var index = 0; index < _els.length; index++) {
          _els[index].addEventListener("click", function (e) {
            e.preventDefault();
            const parent = this.closest(".actions-container");
            const pid = parent.getAttribute("data-id");
            parent.innerHTML = ` <form class="send-response" id="comment-${pid}">
                                <div class="d-flex bg-secondary bg-opacity-10 py-2 mt-2 px-3 rounded-3 comment-message">
                                    <img width="45px" height="45px" class="rounded-pill border border-2 border-secondary border-opacity-25" src="${pic}" alt="">
                                    <div class="form-group w-100 ps-4">
                                        <input type="text" name="text" class=" h-100 form-control reply-text bg-body bg-opacity-10 border-0 mx-3" required placeholder="متن پیام خود را وارد کنید">
                                    </div> 
                                    <a class="btn btn-primary submit-reply d-flex align-items-center rounded-3 px-4" data-id="${pid}">
                                        ارسال
                                    </a>
                                </div>
                            </form>
                        `;
            commentReplyHandler();
          });
        }
      }
    });

    // $('.owl-item img,.card img,.image-list img').unveil(300)
    // $("img").unveil();
    owlCarousel(".favorite-slider", {
      autoWidth: !0,
    });

    document.addEventListener("click", (e) => {
      if (!e.target.closest(".owl-item")) {
        document
          .querySelector(".search-header-result")
          .classList.remove("show");
      }
    });
    owlCarouselGallery();
    if (document.querySelector("#submit-order") != null) {
      document
        .getElementById("submit-order")
        .addEventListener("click", function (e) {
          e.preventDefault();
          document.location.href = this.getAttribute("data-link");
        });
    }
  },
};
