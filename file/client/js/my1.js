$(function () {




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
            $(this).css('z-index', '1000')
            $('body').append('<div class="dropdown-cover-body"></div>')
        })
        .on('hide.bs.dropdown', function (event) {
            $(this).css('z-index', '1')
            $('.dropdown-cover-body').remove()
        })

    $('#search-product').select2({
        dir: 'rtl',
        language: 'fa',
        minimumResultsForSearch: -1,
        theme: 'search-template',
    })
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

    $('.desktop-grid-wrapper').masonry({
        itemSelector: '.grid-item',
    })
    $('.mobile-grid-wrapper').masonry({
        itemSelector: '.grid-item',
    })
    $('.desktop-grid-wrapper2').masonry({
        itemSelector: '.grid-item',
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

    $('.filter-tag').on('click', function () {
        $(this).remove()
    })
})