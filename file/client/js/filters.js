// var nanobar = new Nanobar({
//     id: 'nanobar',
//     target: document.getElementById('top')
// });

window.filtersViewModel = {
    serviceType: '',

    cityId: ko.observable(null),

    areas: ko.observableArray([]),

    areaId: ko.observable(null),

    name: ko.observable(''),

    firstLoadPage: ko.observable(true),

    // noUiSlider
    dimensionsSlider: null,

    dimensions: ko.observable({
        min: 100,
        max: 150000,
        min_default: 100,
        max_default: 150000,
    }),

    monitorresolutionSlider: null,

    monitorresolution: ko.observable({
        min: 380,
        max: 2024,
        min_default: 0,
        max_default: 380,
    }),

    data: ko.observable([]),

    pageHeaderTitle: ko.observable(),

    paginator: ko.observable(''),

    description: ko.observable({
        title: '',
        content: '',
    }),

    tags: ko.observableArray(),

    statusSell: ko.observable(false),

    vicinity: ko.observable(false),

    vicinityError: ko.observable(false),

    f_gender: ko.observable(),

    f_type: ko.observable(),

    services: ko.observableArray(),

    selectedService: ko.observable(),

    isSelected: ko.observable(false),

    statusSellTag: ko.observable({
        type: 'status_sell',
        value: 1,
        title: 'فروش فعال طرح پیچ',
    }),

    nanobarInterval: null,

    nanobarProgress: 0,

    attributesGroups: ko.observableArray(),

    viewAttributes: ko.pureComputed({
        read: function () {
            if (filtersViewModel.attributeSearch().length > 0) {
                var attributes = []
                $.each(filtersViewModel.attributes(), function (index, attr) {
                    if (
                        attr.name.indexOf(filtersViewModel.attributeSearch()) >=
                        0
                    ) {
                        attributes.push(attr)
                    }
                })
                return attributes
            } else {
                return filtersViewModel.attributes()
            }
        },
    }),

    selectedAttributes: ko.observableArray(),

    moreAttributes: ko.observable(false),

    attributeSearch: ko.observable(''),

    hasDefaultAttributes: ko.observable(false),

    afterFirstRequest: ko.observable(false),

    beforeState: [],

    afterState: [],

    loading: ko.observable(false),

    pageTitle: ko.observable(''),

    /**
     * [results, filters]
     */
    view: ko.observable('results'),

    /**
     * Track state for browsing history
     */
    state: null,

    sortList: ko.observableArray([]),

    sortedBy: ko.observableArray(),

    defaultSort: 'views',

    startEditMode: function () {
        this.beforeState = this.tags.slice(0)
    },

    discardEditMode: function () {
        this.tags(this.beforeState)
        this.beforeState = []
    },

    submitEditMode: function () {
        this.beforeState = []
    },

    showFilters: function () {
        this.startEditMode()

        $('body').addClass('fixed')

        filtersViewModel.view('filters')

        resizeModalFilter()
    },

    discardFilters: function () {
        this.discardEditMode()
        this.setFiltersFromTags()

        $('body').removeClass('fixed')
        filtersViewModel.view('results')
    },

    setFiltersFromTags: function () {
        let that = this

        // Reset all filters
        var remainingFilters = [
            'name',
            'city',
            'area',
            'gender',
            'type',
            'service',
            'vicinity',
            'saens_resolution',
            'dimensions',
        ]

        this.selectedAttributes.removeAll()

        var t = this.tags().slice(0)
        $.each(t, function (index, tag) {
            switch (tag.type) {
                case 'name':
                    that.name(tag.value)
                    remainingFilters.splice(remainingFilters.indexOf('name'), 1)
                    break

                case 'city':
                    that.cityId(tag.value)
                    remainingFilters.splice(remainingFilters.indexOf('city'), 1)
                    break

                case 'area':
                    that.refreshAreas(function () {
                        that.areaId(tag.value)
                    })
                    remainingFilters.splice(remainingFilters.indexOf('area'), 1)
                    break

                case 'gender':
                    that.f_gender(tag.value)
                    remainingFilters.splice(
                        remainingFilters.indexOf('gender'),
                        1
                    )
                    break

                case 'type':
                    that.f_type(tag.value)
                    remainingFilters.splice(remainingFilters.indexOf('type'), 1)
                    break

                case 'service':
                    that.selectedService(tag.service_id)
                    remainingFilters.splice(
                        remainingFilters.indexOf('service'),
                        1
                    )
                    break

                case 'attr':
                    that.selectedAttributes.push(tag.attribute_id)
                    break

                case 'vicinity':
                    that.vicinity(true)
                    $('#city').prop('disabled', true)
                    remainingFilters.splice(
                        remainingFilters.indexOf('vicinity'),
                        1
                    )
                    break

                case 'saens_resolution':
                    that.monitorresolutionSlider.noUiSlider.set([
                        tag.min + 6,
                        tag.max + 6,
                    ])
                    remainingFilters.splice(
                        remainingFilters.indexOf('saens_resolution'),
                        1
                    )
                    break

                case 'dimensions':
                    that.dimensionsSlider.noUiSlider.set([tag.min, tag.max])
                    remainingFilters.splice(
                        remainingFilters.indexOf('dimensions'),
                        1
                    )
                    break
            }
        })

        $.each(remainingFilters, function (index, tag) {
            switch (tag) {
                case 'name':
                    that.name('')
                    break

                case 'city':
                    that.cityId('all')
                    break

                case 'area':
                    that.refreshAreas()
                    break

                case 'gender':
                    that.f_gender('all')
                    break

                case 'type':
                    that.f_type('all')
                    break

                case 'service':
                    that.selectedService('')
                    break

                case 'vicinity':
                    that.vicinity(false)
                    break

                case 'saens_resolution':
                    that.monitorresolutionSlider.noUiSlider.set([
                        that.monitorresolution().min_default,
                        that.monitorresolution().max_default,
                    ])
                    break

                case 'dimensions':
                    that.dimensionsSlider.noUiSlider.set([
                        that.dimensions().min_default,
                        that.dimensions().max_default,
                    ])
                    break
            }
        })
    },

    setFilters: function () {
        this.submitEditMode()
        filtersViewModel.submitFilter()

        $('body').removeClass('fixed')
        filtersViewModel.view('results')
    },

    submitFilter: function () {
        $('.search-box').removeClass('has-result')

        this.updateName()

        this.request(this.buildUrl(), true)

        $('#filters').modal('hide')

        this.vicinityError(false)
    },

    buildUrl: function () {
        var baseUrl = filtersViewModel.buildBaseUrl()

        baseUrl = filtersViewModel.buildCityUrl(baseUrl)
        baseUrl = filtersViewModel.buildAttrUrl(baseUrl)
        return filtersViewModel.buildQueryString(baseUrl)
    },

    buildBaseUrl: function () {
        return window.BASE_URL + '/' + filtersViewModel.printService()
    },

    printService: function () {
        switch (filtersViewModel.selectedService()) {
            case 0:
                return 'data'
                break
            case 1:
                return 'courses'
                break
            case 2:
                return 'packs'
                break
            case 3:
                return 'massages'
                break
            case 4:
                return 'turkish-baths'
                break
            case 5:
                return 'waterparks'
                break
            case 6:
                return 'amusements'
                break
            default:
                return 'search'
        }
    },

    buildCityUrl: function (baseUrl) {
        if (filtersViewModel.cityId() && filtersViewModel.cityId() !== 'all') {
            baseUrl += '/' + filtersViewModel.cityId()
        } else if (filtersViewModel.selectedAttributes().length == 1) {
            baseUrl += '/all'
        }

        return baseUrl
    },

    buildAttrUrl: function (baseUrl) {
        // If only one attribute is selected, add it to URL
        if (filtersViewModel.selectedAttributes().length !== 1) return baseUrl

        var selectedAttrId = filtersViewModel.selectedAttributes()[0]

        var selectedAttrObj
        $.each(filtersViewModel.attributesGroups(), function (index, groups) {
            selectedAttrObj = groups.attributes.find(
                (e) => e.attribute_id == selectedAttrId
            )

            if (typeof selectedAttrObj !== 'undefined') return false
        })

        baseUrl += '/attr/' + selectedAttrObj.url
        return baseUrl
    },

    buildQueryString: function (baseUrl) {
        var query = []

        // if (filtersViewModel.cityId() && filtersViewModel.cityId() !== 'all') {
        //     query = filtersViewModel.addToQuery('city', filtersViewModel.cityId() ,query);
        // }

        // if (filtersViewModel.areaId() && filtersViewModel.areaId() !== 'all') {
        //     query = filtersViewModel.addToQuery('area', filtersViewModel.areaId() ,query);
        // }

        // if (filtersViewModel.name().length > 0) {
        //     query = filtersViewModel.addToQuery('name', filtersViewModel.name() ,query);
        // }

        query = filtersViewModel.collectTags(query)

        let sort = this.getSort()
        if (sort.length > 0) {
            query.push(sort)
        }

        return query.length > 0 ? baseUrl + '?' + query.join('&') : baseUrl
    },

    collectTags: function (query) {
        var lengthOfAttributes = filtersViewModel.lengthOfAttributes()
        var attrs = ''
        $.each(filtersViewModel.tags(), function (index, tag) {
            if (tag.ignore_query === true) return

            if (
                typeof tag.queryStringType !== 'undefined' &&
                tag.queryStringType == 'array'
            ) {
                switch (tag.type) {
                    case 'attr':
                        if (lengthOfAttributes > 1) {
                            if (attrs.length == 0)
                                attrs = 'attrs=' + encodeURIComponent(tag.value)
                            else attrs += ',' + encodeURIComponent(tag.value)
                            break
                        }
                }
            } else if (typeof tag.query !== 'undefined') {
                query.push(tag.query)
            } else {
                var tagType = tag.type
                if (tagType == 'name') tagType = 'q'

                query.push(
                    encodeURIComponent(tagType) +
                        '=' +
                        encodeURIComponent(tag.value)
                )
            }
        })

        if (attrs.length > 0) query.push(attrs)

        return query
    },

    lengthOfAttributes: function () {
        return filtersViewModel.tags().filter((obj) => obj.type === 'attr')
            .length
    },

    addToQuery: function (name, value, query) {
        query.push(name + '=' + value)
        return query
    },

    request: function (url, pushState, done) {
        filtersViewModel.requestStart()
        if (filtersViewModel.notSupportPushState())
            return filtersViewModel.refreshPage(url)

        if (pushState) {
            filtersViewModel.pushState(url)
        }

        $.ajax({
            url: url,
            dataType: 'json',
            type: 'GET',
            cache: false,
            success: function (data) {
                filtersViewModel.requestFinish()
                filtersViewModel.changeTitle(data)
                filtersViewModel.viewRender(data)

                if (typeof done !== 'undefined') done(data)

                filtersViewModel.afterFirstRequest(true)
            },
            erorr: function () {
                return filtersViewModel.refreshPage(url)
            },
        })
    },

    /**
     * @source Modernizr
     */
    notSupportPushState: function () {
        var ua = navigator.userAgent

        // We only want Android 2 and 4.0, stock browser, and not Chrome which identifies
        // itself as 'Mobile Safari' as well, nor Windows Phone (issue #1471).
        if (
            (ua.indexOf('Android 2.') !== -1 ||
                ua.indexOf('Android 4.0') !== -1) &&
            ua.indexOf('Mobile Safari') !== -1 &&
            ua.indexOf('Chrome') === -1 &&
            ua.indexOf('Windows Phone') === -1 &&
            // Since all documents on file:// share an origin, the History apis are
            // blocked there as well
            location.protocol !== 'file:'
        ) {
            return true
        }

        // Return the regular check
        return !(window.history && 'pushState' in window.history)
    },

    refreshPage: function (url) {
        window.location = url
    },

    pushState: function (url) {
        this.saveCurrentState()

        window.history.pushState(this.state, null, url)
    },

    changeTitle: function (data) {
        document.title = data.title
        filtersViewModel.pageTitle(data.subject)
    },

    viewRender: function (data) {
        filtersViewModel.data(data.data)
        filtersViewModel.paginator(data.paginator)

        if (data.description_content) {
            $('#static-description').remove()
            filtersViewModel.description({
                title: data.description_title,
                content: data.description_content,
            })
        }

        $('html,body').scrollTop(0)
        $('.list-card .list-card-item').show()
    },

    requestStart: function () {
        this.loading(true)
        this.nanobarStart()

        this.description({
            title: '',
            content: '',
        })

        $('.page .category .container').prepend(
            '<div class="loading-overlay"></div>'
        )
    },

    requestFinish: function () {
        filtersViewModel.loading(false)
        filtersViewModel.nanobarFinish()

        $('.list-card .list-card-item.backend-render').remove()
        $('.pagination-container.backend-render').remove()
        $('.empty-state.backend-render').remove()

        $('.description-content.backend-render').remove()
    },

    nanobarStart: function () {
        // filtersViewModel.nanobarProgress = 20;
        // nanobar.go(filtersViewModel.nanobarProgress);
        // filtersViewModel.nanobarInterval = setInterval(function() {
        //     nanobar.go(filtersViewModel.nanobarProgress += 2);
        // }, 1500);
    },

    nanobarFinish: function () {
        // filtersViewModel.nanobarProgress = 100;
        // nanobar.go(filtersViewModel.nanobarProgress);
        // clearInterval(filtersViewModel.nanobarInterval);
    },

    updateName: function () {
        this.removeTagWhere(['type', 'name'])

        if (this.name() !== undefined && this.name().length > 0) {
            this.tags.push({
                type: 'name',
                value: this.name(),
                title: this.name(),
            })
        }
    },

    cityChange: function () {
        this.removeTagWhere(['type', 'city'])
        if (this.cityId() !== 'all') {
            this.tags.push({
                type: 'city',
                value: this.cityId(),
                title: $('#reg-city option:selected').text(),
                ignore_query: true,
            })
        }

        this.refreshAreas()
    },

    areaChange: function () {
        this.removeTagWhere(['type', 'area'])
        if (this.areaId() !== 'all') {
            this.tags.push({
                type: 'area',
                value: this.areaId(),
                title: $('#reg-area option:selected').text(),
            })
        }
    },

    refreshAreas: function (doneCallback) {
        let that = this

        if (this.cityId() == 'all') {
            this.areas(this.allAreaOption())
            return
        }

        $.ajax({
            url: window.BASE_URL + '/profile/areas',
            data: { city_id: this.cityId() },
            method: 'GET',
            dataType: 'json',
            cache: true,
        })
            .done(function (data) {
                if (!data.status) {
                    that.areas(that.allAreaOption())
                }
                that.areas(that.allAreaOption().concat(data.areas))
                that.areaId('all')

                if (doneCallback != undefined) doneCallback()
            })
            .fail(function (data) {
                that.areas(that.allAreaOption())
                return false
            })
    },

    allAreaOption: function () {
        return [{ name: 'همه مناطق', area_id: 'all' }]
    },

    vicinityChange: function () {
        var that = this
        if (this.vicinity()) {
            this.cityId('all')
            this.areaId('all')
            $('#reg-city').trigger('change') // Make sure area option is re-create
            $('#reg-area').trigger('change') // Make sure area option is re-create

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        that.updateVicinity(
                            position.coords.latitude,
                            position.coords.longitude,
                            false
                        )
                    },
                    function (error) {
                        that.vicinity(false).vicinityError(true)
                    }
                )
            } else {
                this.vicinity(false).vicinityError(true)
            }
        } else {
            this.vicinityError(false)
        }
    },

    updateVicinity: function (lat, lng, hasError) {
        this.removeTagWhere(['type', 'vicinity'])
        this.tags.push({
            type: 'vicinity',
            query: 'lat=' + lat + '&lng=' + lng,
            title: 'مجموعه‌های اطراف من',
        })
        this.vicinityError(!hasError)
    },

    dimensionsChange: function () {
        this.removeTagWhere(['type', 'dimensions'])

        if (
            this.dimensions().min_default == this.dimensions().min &&
            this.dimensions().max_default == this.dimensions().max
        )
            return

        this.tags.push({
            type: 'dimensions',
            query:
                'dimensions[min]=' +
                this.dimensions().min +
                '&dimensions[max]=' +
                this.dimensions().max,
            title:
                'از ' +
                this.dimensions().min.toString().toPersianDigits() +
                ' تا ' +
                this.dimensions().max.toString().toPersianDigits() +
                ' تومان',
            min: this.dimensions().min,
            max: this.dimensions().max,
        })
    },

    monitorresolutionChange: function () {
        this.removeTagWhere(['type', 'saens_resolution'])

        if (
            this.monitorresolution().min == 0 &&
            this.monitorresolution().max == 24
        )
            return

        this.tags.push({
            type: 'saens_resolution',
            query:
                'saens_resolution[min]=' +
                this.monitorresolution().min +
                '&saens_resolution[max]=' +
                this.monitorresolution().max,
            title:
                'از  ' +
                this.resolutionFormat(this.monitorresolution().min) +
                ' تا ' +
                this.resolutionFormat(this.monitorresolution().max),
            min: this.monitorresolution().min,
            max: this.monitorresolution().max,
        })
    },

    genderChange: function () {
        filtersViewModel.removeTagWhere(['type', 'gender'])
        filtersViewModel.f_gender($('.f_gender:checked').val())

        if (filtersViewModel.f_gender() !== 'all') {
            filtersViewModel.tags.push({
                type: 'gender',
                value: filtersViewModel.f_gender(),
                title: $('.f_gender:checked').closest('label').text().trim(),
            })
        }
    },

    statusSellChange: function () {
        if (filtersViewModel.statusSell()) {
            filtersViewModel.tags.push(filtersViewModel.statusSellTag())
        } else {
            filtersViewModel.removeTag(filtersViewModel.statusSellTag())
        }
    },

    typeChange: function (title, data, event) {
        filtersViewModel.removeTagWhere(['type', 'type'])
        if (filtersViewModel.f_type() !== 'all') {
            filtersViewModel.tags.push({
                type: 'type',
                value: filtersViewModel.f_type(),
                title: title,
            })
        }
    },

    removeTagWhere: function (fields, array) {
        array = typeof array !== 'undefined' ? array : false

        var find = true

        if (array) {
            $.each(filtersViewModel.tags(), function (index, tag) {
                $.each(filtersViewModel.tags(), function (index, tag) {
                    find = true
                    for (var i = 0; i < fields.length; i += 2) {
                        if (tag[fields[i]] !== fields[i + 1]) {
                            find = false
                        }
                    }

                    if (find) {
                        filtersViewModel.tags.splice(index, 1)
                        return false
                    }
                })
            })
        } else {
            $.each(filtersViewModel.tags(), function (index, tag) {
                find = true
                for (var i = 0; i < fields.length; i += 2) {
                    if (tag[fields[i]] !== fields[i + 1]) {
                        find = false
                    }
                }

                if (find) {
                    filtersViewModel.tags.splice(index, 1)
                    return false
                }
            })
        }
    },

    removeTag: function (tag, submit) {
        filtersViewModel.tags.remove(tag)
        switch (tag.type) {
            case 'name':
                filtersViewModel.name('')
                break

            case 'city':
                filtersViewModel.cityId('all')
                $('#reg-city').trigger('change')
                break

            case 'area':
                filtersViewModel.areaId('all')
                $('#reg-area').trigger('change')
                break

            case 'gender':
                filtersViewModel.f_gender('all')
                $('#all-genders').prop('checked', true)
                break

            case 'type':
                filtersViewModel.f_type('all')
                break

            case 'vicinity':
                filtersViewModel.vicinity(false)
                filtersViewModel.vicinityChange()
                break

            case 'service':
                filtersViewModel.selectedService('')
                filtersViewModel.serviceChange()
                break

            case 'attr':
                filtersViewModel.selectedAttributes.remove(tag.attribute_id)
                filtersViewModel.attributeChange()
                break

            case 'tag':
                break

            case 'saens_resolution':
                filtersViewModel.monitorresolutionSlider.noUiSlider.set([
                    filtersViewModel.monitorresolution().min_default + 6,
                    filtersViewModel.monitorresolution().max_default + 6,
                ])
                break

            case 'dimensions':
                filtersViewModel.dimensionsSlider.noUiSlider.set([
                    filtersViewModel.dimensions().min_default,
                    filtersViewModel.dimensions().max_default,
                ])
                break

            case 'status_sell':
                filtersViewModel.statusSell(false)
                filtersViewModel.removeTagWhere(['type', 'status_sell'])
                break
        }
        if (submit) filtersViewModel.submitFilter()
    },

    clearFilters: function (except = []) {
        var tags = filtersViewModel.tags().slice(0)
        $.each(tags, function (index, tag) {
            if (except.includes(tag.type)) {
                return false
            }

            filtersViewModel.removeTag(tag, false)
        })
    },

    clearFiltersAndSubmit: function () {
        this.clearFilters([])

        this.submitFilter()
    },

    init: function () {
        this.initdimensionsSlider()
        this.initmonitorresolutionSlider()
    },

    loadData: function () {
        var that = this
        var queryParameters = window.location.search.substring(1)
    },

    initdimensionsSlider: function () {
        let that = this

        this.dimensions().min = isNaN(parseInt($('#min-dimensions').text()))
            ? this.dimensions().min_default
            : parseInt($('#min-dimensions').text())
        this.dimensions().max = isNaN(parseInt($('#max-dimensions').text()))
            ? this.dimensions().max_default
            : parseInt($('#max-dimensions').text())

        this.dimensionsChange()

        this.dimensionsSlider = document.getElementById('dimensions-range')
        noUiSlider.create(this.dimensionsSlider, {
            start: [this.dimensions().min, this.dimensions().max],
            connect: true,
            step: 5000,
            direction: 'rtl',
            range: {
                min: this.dimensions().min_default,
                max: this.dimensions().max_default,
            },
        })

        this.dimensionsSlider.noUiSlider.on('change', function (values) {
            let min = parseInt(values[0])
            let max = parseInt(values[1])
            document.getElementById('min-dimensions').innerHTML = min
                .toString()
                .toPersianDigits()
            document.getElementById('max-dimensions').innerHTML = max
                .toString()
                .toPersianDigits()

            that.dimensions(
                $.extend(that.dimensions(), {
                    min: min,
                    max: max,
                })
            )
            that.dimensionsChange()
        })

        this.dimensionsSlider.noUiSlider.on('update', function (values) {
            let min = parseInt(values[0])
            let max = parseInt(values[1])
            document.getElementById('min-dimensions').innerHTML = min
                .toString()
                .toPersianDigits()
            document.getElementById('max-dimensions').innerHTML = max
                .toString()
                .toPersianDigits()
        })
    },

    initmonitorresolutionSlider: function () {
        let that = this

        this.monitorresolution().min = isNaN(
            parseInt($('#min-resolution').text())
        )
            ? this.monitorresolution().min_default
            : parseInt($('#min-resolution').text())
        this.monitorresolution().max = isNaN(
            parseInt($('#max-resolution').text())
        )
            ? this.monitorresolution().max_default
            : parseInt($('#max-resolution').text())

        this.monitorresolutionChange()

        this.monitorresolutionSlider =
            document.getElementById('resolution-range')
        noUiSlider.create(this.monitorresolutionSlider, {
            start: [
                this.monitorresolution().min + 6,
                this.monitorresolution().max + 6,
            ],
            connect: true,
            step: 1,
            direction: 'rtl',
            padding: [6, 6],
            range: {
                min: this.monitorresolution().min_default,
                max: this.monitorresolution().max_default + 12,
            },
        })

        this.monitorresolutionSlider.noUiSlider.on('change', function (values) {
            document.getElementById(
                'min-resolution'
            ).innerHTML = `<span class="ltr">${that.resolutionFormat(
                values[0]
            )}</span>`
            document.getElementById(
                'max-resolution'
            ).innerHTML = `<span class="ltr">${that.resolutionFormat(
                values[1]
            )}</span>`

            document.querySelector(
                '#resolution-range .noUi-handle-lower .noUi-touch-area'
            ).innerHTML = that.resolutionFormat(values[0])
            document.querySelector(
                '#resolution-range .noUi-handle-upper .noUi-touch-area'
            ).innerHTML = that.resolutionFormat(values[1])

            that.monitorresolution(
                $.extend(that.monitorresolution(), {
                    min: values[0] - 6,
                    max: values[1] - 6,
                })
            )
            that.monitorresolutionChange()
        })

        this.monitorresolutionSlider.noUiSlider.on('update', function (values) {
            document.getElementById(
                'min-resolution'
            ).innerHTML = `<span class="ltr">${that.resolutionFormat(
                values[0]
            )}</span>`
            document.getElementById(
                'max-resolution'
            ).innerHTML = `<span class="ltr">${that.resolutionFormat(
                values[1]
            )}</span>`

            document.querySelector(
                '#resolution-range .noUi-handle-lower .noUi-touch-area'
            ).innerHTML = that.resolutionFormat(values[0])
            document.querySelector(
                '#resolution-range .noUi-handle-upper .noUi-touch-area'
            ).innerHTML = that.resolutionFormat(values[1])
        })
    },

    resolutionFormat: function (value) {
        var toNumber = Number(value)

        if (toNumber < 100) {
            return `${toNumber}0`.toPersianDigits()
        } else {
            if (toNumber == 1300) return '1300'.toPersianDigits()

            return `${toNumber}00`.toPersianDigits()
        }
    },

    serviceChange: function () {
        if (this.firstLoadPage() == false) {
            var except = ['service']
            this.clearFilters(except)
        }

        filtersViewModel.refreshServiceTag()
        return true
    },

    refreshServiceTag: function () {
        filtersViewModel.removeTagWhere(['type', 'service'])

        if (filtersViewModel.selectedService() === '') return

        $.each(filtersViewModel.services(), function (index, service) {
            if (filtersViewModel.selectedService() === service.service_id) {
                filtersViewModel.tags.push({
                    type: 'service',
                    value: service.url,
                    title: service.name,
                    service_id: service.service_id,
                    ignore_query: true,
                })
            }
        })
    },

    attributeChange: function () {
        filtersViewModel.removeTagWhere(['type', 'attr'], true)
        $.each(filtersViewModel.attributesGroups(), function (index, groups) {
            $.each(groups.attributes, function (index, attr) {
                if (
                    filtersViewModel
                        .selectedAttributes()
                        .indexOf(attr.attribute_id) >= 0
                ) {
                    filtersViewModel.tags.push({
                        type: 'attr',
                        queryStringType: 'array',
                        value: attr.url,
                        title: attr.name,
                        attribute_id: attr.attribute_id,
                    })
                }
            })
        })
        return true
    },

    toggleMoreAttributes: function () {
        filtersViewModel.moreAttributes(!filtersViewModel.moreAttributes())
    },

    sort: function (s) {
        this.sortedBy(s)
        this.submitFilter()
    },

    getSort: function () {
        if (this.sortedBy().code == this.defaultSort) {
            return ''
        }

        return 'sort=' + this.sortedBy().code
    },

    getQueryStringValue: function (key) {
        return decodeURIComponent(
            window.location.search.replace(
                new RegExp(
                    '^(?:.*[&\\?]' +
                        encodeURIComponent(key).replace(/[\.\+\*]/g, '\\$&') +
                        '(?:\\=([^&]*))?)?.*$',
                    'i'
                ),
                '$1'
            )
        )
    },

    loadState: function () {
        var state = parseInt(sessionStorage.getItem('state'))
        if (typeof state !== 'number' || isNaN(state)) state = 0

        this.state = state
    },

    saveCurrentState: function () {
        var tags = JSON.parse(sessionStorage.getItem('tags'))
        if (tags === null) {
            tags = []
        }

        if (tags.length > 0) this.state++

        tags[this.state] = this.tags()
        sessionStorage.setItem('tags', JSON.stringify(tags))
        sessionStorage.setItem('state', this.state)
    },

    newState: function () {
        this.state++
    },

    setState: function (state) {
        this.state = state
    },
}

ko.applyBindings(filtersViewModel, document.getElementById('filters-page'))

filtersViewModel.init()