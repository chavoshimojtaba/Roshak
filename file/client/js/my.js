

/* $(document).ready(function () {
    Dropzone.autoDiscover = false;
    var previewNode = document.querySelector('#template');
    previewNode.id = '';
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);
    const cardCreview = document.querySelector('#card-preview');
    const startBtn = document.querySelector('#actions .start');
    const cancelBtn = document.querySelector('#actions .cancel');

    const token = 111111;
    var myDropzone = new Dropzone('div#previews', {
        url: 'file',
        method: 'POST',
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        headers: {
            'Authorization':`Bearer ${token}`
        },
        createImageThumbnails: true,
        acceptedFiles:'*.png',
        autoQueue: false,
        maxFiles: 8,
        previewTemplate: previewTemplate,
        previewsContainer: '#previews',
        clickable: '.fileinput-button',
        success: function (file, response) {
            file.previewElement
                .querySelector('.upload-done')
                .classList.remove('d-none');
            setTimeout(() => {
                // file.previewElement.remove();
            }, 1000);
        },
        error: function (file, response) {
            if (file.previewElement) {
                // file.previewElement.remove();
            }
        },
        reset() {
            startBtn.classList.add('d-none');
            cardCreview.classList.add('d-none');
            cancelBtn.classList.add('d-none');

        },
    });

    myDropzone.on('addedfile', function (file) {
        startBtn.classList.remove('d-none');
        cardCreview.classList.remove('d-none');
        cancelBtn.classList.remove('d-none');
        file.previewElement.querySelector('.dz-type').innerHTML = file.type;
        const validImageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!validImageTypes.includes(file.type)) {
            file.previewElement.querySelector('img').src = '../images/icons/document.svg';
        }
        file.previewElement.querySelector('.start').onclick = function () {
            myDropzone.enqueueFile(file);
        };
        $('[data-toggle="tooltip"]').tooltip()

    });

    myDropzone.on('sending', function (file, xhr, formData) {

        formData.append(
            'title',
            file.previewElement.querySelector('.file_title').value
        );
        formData.append(
            'alt',
            file.previewElement.querySelector('.file_alt').value
        );
        file.previewElement
            .querySelector('.start')
            .setAttribute('disabled', 'disabled');
    });
    myDropzone.on('queuecomplete', function (progress) {
        startBtn.classList.add('d-none');
        cardCreview.classList.add('d-none');
        cancelBtn.classList.add('d-none');
    });
    startBtn.onclick = function () {
        myDropzone.enqueueFiles(
            myDropzone.getFilesWithStatus(Dropzone.ADDED)
        );
    };
    cancelBtn.onclick = function () {
        myDropzone.removeAllFiles(true);
    };
    stepFormWizard(5);

}); */
$(function () {

    return
    $('.home-slider').owlCarousel({
        items: 1,
        margin: 5,
        rtl: true,
        autoplay: true,
        nav: true,
        loop: true,
        navText: [
            "<i class='icon icon-big-angle-right text-dark'></i>",
            "<i class='icon icon-big-angle-left text-dark'></i>",
        ],
    })

    $('.widget-brands').owlCarousel({
        items: 5,
        margin: 50,
        rtl: true,
        autoplay: true,
        nav: true,
        loop: true,
        navText: [
            '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="16" viewBox="0 0 31 22" fill="none"><path d="M19.6495 3.73514C18.9886 3.07425 18.9886 2.00274 19.6495 1.34186C20.3104 0.68097 21.3819 0.68097 22.0428 1.34186L30.5043 9.8034C31.1652 10.4643 31.1652 11.5358 30.5043 12.1967L22.0428 20.6582C21.3819 21.3191 20.3104 21.3191 19.6495 20.6582C18.9886 19.9973 18.9886 18.9258 19.6495 18.2649L25.2221 12.6923H2.23076C1.29612 12.6923 0.538452 11.9347 0.538452 11C0.538452 10.0654 1.29612 9.30773 2.23076 9.30773H25.2221L19.6495 3.73514Z" fill="#00C6E1"/></svg>',
            '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="16" viewBox="0 0 31 22" fill="none"><path d="M11.3505 3.73514C12.0114 3.07425 12.0114 2.00274 11.3505 1.34186C10.6896 0.68097 9.6181 0.68097 8.95721 1.34186L0.495674 9.8034C-0.165213 10.4643 -0.165213 11.5358 0.495674 12.1967L8.95721 20.6582C9.6181 21.3191 10.6896 21.3191 11.3505 20.6582C12.0114 19.9973 12.0114 18.9258 11.3505 18.2649L5.77791 12.6923H28.7692C29.7039 12.6923 30.4615 11.9347 30.4615 11C30.4615 10.0654 29.7039 9.30773 28.7692 9.30773H5.77791L11.3505 3.73514Z" fill="#00C6E1"/></svg>',
        ],
    })

    $('.owl-video-card').owlCarousel({
        items: 4,
        margin: 0,
        rtl: true,
        autoplay: true,
        nav: true,
        dots: true,
        loop: true,
        navText: [
            '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="15" viewBox="0 0 23 15" fill="none"><path opacity="0.4" d="M16.5094 5.98503L21.0773 5.58105C22.1024 5.58105 22.9336 6.4203 22.9336 7.45541C22.9336 8.49052 22.1024 9.32976 21.0773 9.32976L16.5094 8.92578C15.7052 8.92578 15.0532 8.26744 15.0532 7.45541C15.0532 6.64201 15.7052 5.98503 16.5094 5.98503Z" fill="#33D1E7"/><path d="M1.16918 6.05835C1.24057 5.98626 1.50729 5.68158 1.75785 5.42858C3.21942 3.84395 7.03568 1.25278 9.03204 0.459782C9.33513 0.333284 10.1016 0.0639646 10.5125 0.0449219C10.9045 0.0449219 11.279 0.136055 11.6359 0.315601C12.0818 0.567238 12.4374 0.964415 12.6341 1.43232C12.7594 1.75605 12.9561 2.72859 12.9561 2.74627C13.1514 3.80859 13.2578 5.53604 13.2578 7.44575C13.2578 9.26298 13.1514 10.9197 12.9911 11.9997C12.9736 12.0187 12.7769 13.2252 12.5627 13.6387C12.1707 14.395 11.4042 14.8629 10.5839 14.8629H10.5125C9.97769 14.8452 8.85423 14.376 8.85423 14.3596C6.96429 13.5666 3.23828 11.1006 1.74033 9.46157C1.74033 9.46157 1.31735 9.0399 1.13415 8.77739C0.848572 8.39925 0.705782 7.93134 0.705782 7.46344C0.705782 6.94112 0.866084 6.45553 1.16918 6.05835Z" fill="#33D1E7"/></svg>',
            '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="15" viewBox="0 0 23 15" fill="none"><path opacity="0.4" d="M7.12732 5.98503L2.55939 5.58105C1.53427 5.58105 0.703125 6.4203 0.703125 7.45541C0.703125 8.49052 1.53427 9.32976 2.55939 9.32976L7.12732 8.92578C7.93152 8.92578 8.5835 8.26744 8.5835 7.45541C8.5835 6.64201 7.93152 5.98503 7.12732 5.98503Z" fill="#33D1E7"/><path d="M22.4675 6.05835C22.3961 5.98626 22.1294 5.68158 21.8789 5.42858C20.4173 3.84395 16.601 1.25278 14.6047 0.459782C14.3016 0.333284 13.5351 0.0639646 13.1242 0.0449219C12.7322 0.0449219 12.3578 0.136055 12.0008 0.315601C11.5549 0.567238 11.1993 0.964415 11.0026 1.43232C10.8773 1.75605 10.6807 2.72859 10.6807 2.74627C10.4853 3.80859 10.3789 5.53604 10.3789 7.44575C10.3789 9.26298 10.4853 10.9197 10.6456 11.9997C10.6631 12.0187 10.8598 13.2252 11.074 13.6387C11.466 14.395 12.2325 14.8629 13.0528 14.8629H13.1242C13.659 14.8452 14.7825 14.376 14.7825 14.3596C16.6724 13.5666 20.3984 11.1006 21.8964 9.46157C21.8964 9.46157 22.3194 9.0399 22.5026 8.77739C22.7881 8.39925 22.9309 7.93134 22.9309 7.46344C22.9309 6.94112 22.7706 6.45553 22.4675 6.05835Z" fill="#33D1E7"/></svg>',
        ],
    })

    $('.owl-radio').owlCarousel({
        items: 3,
        margin: 0,
        rtl: true,
        autoplay: true,
        nav: true,
        dots: true,
        loop: true,
        navText: [
            '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="15" viewBox="0 0 23 15" fill="none"><path opacity="0.4" d="M16.5094 5.98503L21.0773 5.58105C22.1024 5.58105 22.9336 6.4203 22.9336 7.45541C22.9336 8.49052 22.1024 9.32976 21.0773 9.32976L16.5094 8.92578C15.7052 8.92578 15.0532 8.26744 15.0532 7.45541C15.0532 6.64201 15.7052 5.98503 16.5094 5.98503Z" fill="#33D1E7"/><path d="M1.16918 6.05835C1.24057 5.98626 1.50729 5.68158 1.75785 5.42858C3.21942 3.84395 7.03568 1.25278 9.03204 0.459782C9.33513 0.333284 10.1016 0.0639646 10.5125 0.0449219C10.9045 0.0449219 11.279 0.136055 11.6359 0.315601C12.0818 0.567238 12.4374 0.964415 12.6341 1.43232C12.7594 1.75605 12.9561 2.72859 12.9561 2.74627C13.1514 3.80859 13.2578 5.53604 13.2578 7.44575C13.2578 9.26298 13.1514 10.9197 12.9911 11.9997C12.9736 12.0187 12.7769 13.2252 12.5627 13.6387C12.1707 14.395 11.4042 14.8629 10.5839 14.8629H10.5125C9.97769 14.8452 8.85423 14.376 8.85423 14.3596C6.96429 13.5666 3.23828 11.1006 1.74033 9.46157C1.74033 9.46157 1.31735 9.0399 1.13415 8.77739C0.848572 8.39925 0.705782 7.93134 0.705782 7.46344C0.705782 6.94112 0.866084 6.45553 1.16918 6.05835Z" fill="#33D1E7"/></svg>',
            '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="15" viewBox="0 0 23 15" fill="none"><path opacity="0.4" d="M7.12732 5.98503L2.55939 5.58105C1.53427 5.58105 0.703125 6.4203 0.703125 7.45541C0.703125 8.49052 1.53427 9.32976 2.55939 9.32976L7.12732 8.92578C7.93152 8.92578 8.5835 8.26744 8.5835 7.45541C8.5835 6.64201 7.93152 5.98503 7.12732 5.98503Z" fill="#33D1E7"/><path d="M22.4675 6.05835C22.3961 5.98626 22.1294 5.68158 21.8789 5.42858C20.4173 3.84395 16.601 1.25278 14.6047 0.459782C14.3016 0.333284 13.5351 0.0639646 13.1242 0.0449219C12.7322 0.0449219 12.3578 0.136055 12.0008 0.315601C11.5549 0.567238 11.1993 0.964415 11.0026 1.43232C10.8773 1.75605 10.6807 2.72859 10.6807 2.74627C10.4853 3.80859 10.3789 5.53604 10.3789 7.44575C10.3789 9.26298 10.4853 10.9197 10.6456 11.9997C10.6631 12.0187 10.8598 13.2252 11.074 13.6387C11.466 14.395 12.2325 14.8629 13.0528 14.8629H13.1242C13.659 14.8452 14.7825 14.376 14.7825 14.3596C16.6724 13.5666 20.3984 11.1006 21.8964 9.46157C21.8964 9.46157 22.3194 9.0399 22.5026 8.77739C22.7881 8.39925 22.9309 7.93134 22.9309 7.46344C22.9309 6.94112 22.7706 6.45553 22.4675 6.05835Z" fill="#33D1E7"/></svg>',
        ],
    })

    $('.owl-video').owlCarousel({
        items: 4,
        margin: 0,
        rtl: true,
        autoplay: true,
        nav: true,
        dots: true,
        loop: true,
        navText: [
            '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="15" viewBox="0 0 23 15" fill="none"><path opacity="0.4" d="M16.5094 5.98503L21.0773 5.58105C22.1024 5.58105 22.9336 6.4203 22.9336 7.45541C22.9336 8.49052 22.1024 9.32976 21.0773 9.32976L16.5094 8.92578C15.7052 8.92578 15.0532 8.26744 15.0532 7.45541C15.0532 6.64201 15.7052 5.98503 16.5094 5.98503Z" fill="#33D1E7"/><path d="M1.16918 6.05835C1.24057 5.98626 1.50729 5.68158 1.75785 5.42858C3.21942 3.84395 7.03568 1.25278 9.03204 0.459782C9.33513 0.333284 10.1016 0.0639646 10.5125 0.0449219C10.9045 0.0449219 11.279 0.136055 11.6359 0.315601C12.0818 0.567238 12.4374 0.964415 12.6341 1.43232C12.7594 1.75605 12.9561 2.72859 12.9561 2.74627C13.1514 3.80859 13.2578 5.53604 13.2578 7.44575C13.2578 9.26298 13.1514 10.9197 12.9911 11.9997C12.9736 12.0187 12.7769 13.2252 12.5627 13.6387C12.1707 14.395 11.4042 14.8629 10.5839 14.8629H10.5125C9.97769 14.8452 8.85423 14.376 8.85423 14.3596C6.96429 13.5666 3.23828 11.1006 1.74033 9.46157C1.74033 9.46157 1.31735 9.0399 1.13415 8.77739C0.848572 8.39925 0.705782 7.93134 0.705782 7.46344C0.705782 6.94112 0.866084 6.45553 1.16918 6.05835Z" fill="#33D1E7"/></svg>',
            '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="15" viewBox="0 0 23 15" fill="none"><path opacity="0.4" d="M7.12732 5.98503L2.55939 5.58105C1.53427 5.58105 0.703125 6.4203 0.703125 7.45541C0.703125 8.49052 1.53427 9.32976 2.55939 9.32976L7.12732 8.92578C7.93152 8.92578 8.5835 8.26744 8.5835 7.45541C8.5835 6.64201 7.93152 5.98503 7.12732 5.98503Z" fill="#33D1E7"/><path d="M22.4675 6.05835C22.3961 5.98626 22.1294 5.68158 21.8789 5.42858C20.4173 3.84395 16.601 1.25278 14.6047 0.459782C14.3016 0.333284 13.5351 0.0639646 13.1242 0.0449219C12.7322 0.0449219 12.3578 0.136055 12.0008 0.315601C11.5549 0.567238 11.1993 0.964415 11.0026 1.43232C10.8773 1.75605 10.6807 2.72859 10.6807 2.74627C10.4853 3.80859 10.3789 5.53604 10.3789 7.44575C10.3789 9.26298 10.4853 10.9197 10.6456 11.9997C10.6631 12.0187 10.8598 13.2252 11.074 13.6387C11.466 14.395 12.2325 14.8629 13.0528 14.8629H13.1242C13.659 14.8452 14.7825 14.376 14.7825 14.3596C16.6724 13.5666 20.3984 11.1006 21.8964 9.46157C21.8964 9.46157 22.3194 9.0399 22.5026 8.77739C22.7881 8.39925 22.9309 7.93134 22.9309 7.46344C22.9309 6.94112 22.7706 6.45553 22.4675 6.05835Z" fill="#33D1E7"/></svg>',
        ],
    })





    $('.profile-slider-mobile').owlCarousel({
        items: 2,
        margin: 15,
        rtl: true,
        autoplay: true,
        dots: false,
        nav: true,
        responsive: {
            0: {
                items: 2,
            },
        },
        navText: [
            "<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
            "<i class='d-block icon fs-4 icon-arrow-left4'></i>",
        ],
    })
    $('.cooperate-slide').owlCarousel({
        items: 1,
        margin: 15,
        rtl: true,
        autoplay: true,
        dots: false,
        nav: true,
        responsive: {
            0: {
                items: 1,
            },
            1200: {
                items: 3,
            },
        },
        navText: [
            "<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
            "<i class='d-block icon fs-4 icon-arrow-left4'></i>",
        ],
    })

    $('.widget-tags-mobile ').owlCarousel({
        items: 3,
        margin: 15,
        rtl: true,
        autoplay: true,
        dots: false,
        nav: false,
        responsive: {
            0: {
                items: 3,
            },
            1200: {
                items: 6,
            },
        },
        navText: [
            "<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
            "<i class='d-block icon fs-4 icon-arrow-left4'></i>",
        ],
    })

    $('.plans-slide').owlCarousel({
        items: 1,
        center: true,
        loop: true,
        margin: -18,
        rtl: true,
        autoplay: true,
        dots: false,
        nav: false,
        responsive: {
            0: {
                items: 1,
            },
            1200: {
                items: 3,
            },
        },
        navText: [
            "<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
            "<i class='d-block icon fs-4 icon-arrow-left4'></i>",
        ],
    })

    $('.cooperate-faq-item').owlCarousel({
        items: 2,
        margin: 15,
        rtl: true,
        autoplay: true,
        dots: false,
        nav: true,
        responsive: {
            0: {
                items: 2,
            },
            1200: {
                items: 3,
            },
        },
        navText: [
            "<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
            "<i class='d-block icon fs-4 icon-arrow-left4'></i>",
        ],
    })

    $('.card-about-slider').owlCarousel({
        items: 2,
        margin: 15,
        rtl: true,
        autoplay: true,
        dots: false,
        nav: true,
        responsive: {
            0: {
                items: 1,
            },
            425: {
                items: 2,
            },
            1200: {
                items: 3,
            },
        },
        navText: [
            "<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
            "<i class='d-block icon fs-4 icon-arrow-left4'></i>",
        ],
    })


   /*  $('.slider-galeria').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        asNavFor: '.slider-galeria-thumbs',
    });
    $('.slider-galeria-thumbs').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.slider-galeria',
        vertical: true,
        arrows: false,
        responsive: [
        {
            breakpoint: 768,
            settings: {
                vertical: false,
            }
          },
        ],
        focusOnSelect: true,
        verticalSwiping: true,
    }); */
     $('.toggle-content').click(function () {
        let wrapper = $(this).closest('.more-wrapper')
        wrapper.toggleClass('overlay-bg')
        $(this).children('.icon-arrow-down-14').toggleClass('rotate')
        wrapper.parent().find('.content-hidden').toggleClass('show')
    })
   //event for search dropdowns
    const dropdownElement = $('.search-header-result')
    const dropdownCategory = $('.category-dropdown')
    const buttonSearch = $('#tmp-search-id')
    const buttonState = $('#tmp-state-id, .custom-select')
    buttonSearch
        .on('click', function (e) {
            dropdownCategory.removeClass('show')
            dropdownElement.addClass('show')
        })
        .on('blur', function () {
            dropdownElement.removeClass('show')
        })

    //event for city dropdowns
    buttonState.on('click', function () {
        $(this).blur()
        dropdownCategory.removeClass('show')
        dropdownElement.removeClass('show')
        $(this).next().addClass('show')
    })
    dropdownCategory.on('mouseleave', function () {
        dropdownCategory.removeClass('show')
    })

    //lazy load for card data
    $('.owl-item img,.card img,.image-list img,#blog-widget img').unveil(300)

    //initital popover
    const popoverTriggerList = document.querySelectorAll(
        '[data-bs-toggle="popover"]'
    )
    const popoverList = [...popoverTriggerList].map(
        (popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl)
    )

    $('.btn-dropdown-login-user, .menu-nav')
        .on('show.bs.dropdown', function (event) {
            $(this).closest('.nav-item').addClass('active')
            console.log($(this).closest('.nav-item'));
            $(this).css('z-index', '1000')
            $('body').append('<div class="dropdown-cover-body"></div>')
        })
        .on('hide.bs.dropdown', function (event) {
            $(this).css('z-index', '1')
            $(this).closest('.nav-item').removeClass('active')
            $('.dropdown-cover-body').remove()
        });

    $('.grid-dropdown').select2({
        dir: 'rtl',
        language: 'fa',
        minimumResultsForSearch: -1,
        theme: 'grid-dropdown-template',
    })
    return;
    $('#filter-option').select2({
        dir: 'rtl',
        language: 'fa',
        minimumResultsForSearch: -1,
        theme: 'filter-search-template',
    })
    $('#search-product').select2({
        dir: 'rtl',
        language: 'fa',
        minimumResultsForSearch: -1,
        theme: 'default-template',
    })
   /*  $('.select-2').select2({
        dir: 'rtl',
        language: 'fa',
        minimumResultsForSearch: -1,
    }) */
    $('#header-search-product').select2({
        dir: 'rtl',
        language: 'fa',
        minimumResultsForSearch: -1,
        theme: 'header-search-template',
    })
    $('#filter-search-product').select2({
        dir: 'rtl',
        language: 'fa',
        minimumResultsForSearch: -1,
        theme: 'filter-search-template',
    })

    $('.filter-search-designer').select2({
        dir: 'rtl',
        language: 'fa',
        minimumResultsForSearch: -1,
        theme: 'filter-search-template',
    })

    const $filterSort = $('.filter-sort')
    $filterSort.find('input').on({
        click: function () {
            $filterSort.addClass('active')
        },
        blur: function () {
            $filterSort.removeClass('active')
        },
    })

    $('.menu-item')
        .on('click', function (e) {
            e.stopPropagation()
            let subMenu = $(this).data('children')
            $(subMenu).removeClass('d-none')
        })
        .on('blur', function () {
            let subMenu = $(this).data('children')
            $(subMenu).addClass('d-none')
        })
    $('.multi-step-form .nav-item')
        .on('click', function (e) {
            e.stopPropagation()
            $('.multi-step-form .nav-item').removeClass('active')
            $(this).addClass('active')
        })

    $('.filter-tag').on('click', function () {
        $(this).remove()
    })

    $('.remove-basket-item').on('click', function (e) {
        $(this).parent().parent().remove()
    })

    //comment respose event
    $('.btn-response-comment').on('click', function (e) {
        $(this).next('.send-response').removeClass('d-none')
        $(this).addClass('d-none')
    })

})
var steps = 1;
var currentStep = 1;
var updateProgressBar;

function displayStep(stepNumber) {
    if (stepNumber >= 1 && stepNumber <= steps) {
        $(".step-" + currentStep).hide();
        $(".step-" + stepNumber).show();
        currentStep = stepNumber;
        stepFormWizard().updateProgressBar();
    }
}
function stepFormWizard(_steps) {
    steps = _steps;
    document.querySelectorAll('.step-circle')[0].classList.add('current');
    $(".next-step").click(function () {
        if (currentStep < steps) {
            $(".step").removeClass("d-block");
            document.querySelectorAll('.step-circle')[currentStep - 1].classList.add('active');
            document.querySelectorAll('.step-circle')[currentStep].classList.add('current');
            $(".step-" + currentStep).addClass("animate__animated animate__fadeOutLeft");
            currentStep++;
            $(".step").removeClass("animate__animated animate__fadeOutLeft").hide();
            $(".step-" + currentStep).addClass("animate__animated animate__fadeInRight d-block");
            updateProgressBar();
        }
        if ((currentStep) == steps) {
            document.querySelectorAll('.step-circle')[steps - 1].classList.add('active');
        }
    });
    $(".prev-step").click(function () {
        $(".step").removeClass("d-block");
        if (currentStep > 1) {
            console.log(currentStep);
            $(".step-circle").removeClass("current");

            document.querySelectorAll('.step-circle')[currentStep - 1].classList.remove('active');
            document.querySelectorAll('.step-circle')[currentStep - 2].classList.remove('active');
            document.querySelectorAll('.step-circle')[currentStep - 2].classList.add('current');
            $(".step-" + currentStep).addClass("animate__animated animate__fadeOutRight");
            currentStep--;
            $(".step").removeClass("animate__animated animate__fadeOutRight").hide();
            $(".step-" + currentStep).show().addClass("animate__animated animate__fadeInLeft");
            updateProgressBar();
        }
    });
    updateProgressBar = function () {
        var progressPercentage = ((currentStep - 1) / (steps - 1)) * 100;
        $(".progress-bar").css("width", progressPercentage + "%");
    }
}