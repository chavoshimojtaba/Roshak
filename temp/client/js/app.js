import { signature } from "../../util/util";
import { Router } from "./Router";
import { changeViewOnResize, mainSearchBar } from "./util/util";
Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};
changeViewOnResize();
window.addEventListener('load', function () {
    mainSearchBar(); 
    $('.btn-dropdown-login-user ')
        .on('show.bs.dropdown', function (event) {  
            if ($(this).hasClass('menu-nav')) { 
                $('.inline-menu .nav-item').css('z-index', '1000')
                $(this).css('position', 'relative')
            }
            $(this).css('position', 'relative')
            $(this).css('z-index', '1000')
            $('body').append('<div class="dropdown-cover-body"></div>')
        })
        .on('hide.bs.dropdown', function (event) { 
            $(this).css('z-index', '1')
            $('.dropdown-cover-body').remove()
            if ($(this).hasClass('menu-nav')) { 
                $('.inline-menu .nav-item').css('z-index', '1') 
            }
        }) 
        if (document.querySelector('.logout-btn') !== null) { 
            var myModal = new bootstrap.Modal(document.getElementById('modalexit'), {
                keyboard: false
            }) 
            $('.logout-btn').on('click', function () { 
                myModal.show()
            }); 
            $('.modal-logout-btn').on('click', function () { 
                document.location.href = HOST+'out';
            });
        }
        if (document.querySelector('.hamburger#offCanvas') !== null) { 
            $('.hamburger#offCanvas').on('click', function () {
                $('.nav-offcanvas').addClass('open');
                $('.offcanvas-overlay').addClass('on');
            });
            setTimeout(() => { 
                $('.hamburger #offCanvasClose,.offcanvas-overlay').on('click', function () { 
                    $('.nav-offcanvas').removeClass('open');
                    $('.offcanvas-overlay').removeClass('on');
                });
            }, 2000);
        }
    Router()
    signature();
});

