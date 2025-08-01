(function ($) {
    "use strict";

    /*[ Load page ]
    ===========================================================*/
    $(".animsition").animsition({
        inClass: 'fade-in',
        outClass: 'fade-out',
        inDuration: 1500,
        outDuration: 800,
        linkElement: '.animsition-link',
        loading: true,
        loadingParentElement: 'html',
        loadingClass: 'animsition-loading-1',
        loadingInner: '<div class="loader05"></div>',
        timeout: false,
        timeoutCountdown: 5000,
        onLoadEvent: true,
        browser: ['animation-duration', '-webkit-animation-duration'],
        overlay: false,
        overlayClass: 'animsition-overlay-slide',
        overlayParentElement: 'html',
        transition: function(url) { window.location.href = url; }
    });
    
    /*[ Back to top ]
    ===========================================================*/
    var windowH = $(window).height()/2;

    $(window).on('scroll', function() {
        if ($(this).scrollTop() > windowH) {
            $("#myBtn").css('display', 'flex');
        } else {
            $("#myBtn").css('display', 'none');
        }
    });

    $('#myBtn').on("click", function() {
        $('html, body').animate({scrollTop: 0}, 300);
    });

    /*[ Fixed Header ]
    ===========================================================*/
    var headerDesktop = $('.container-menu-desktop');
    var wrapMenu = $('.wrap-menu-desktop');
    var posWrapHeader = $('.top-bar').length > 0 ? $('.top-bar').height() : 0;

    /*[ Quick View Modal ]
    ===========================================================*/
    $('.js-show-modal1').on('click', function(e) {
        e.preventDefault();
        var $product = $(this).closest('.js-product-block');
        var imgSrc = $product.find('.js-product-img img').attr('src');
        var name = $product.find('.js-product-name').text();
        var price = $product.find('.js-product-price').text();
        var desc = $product.find('.js-product-desc').text();

        var $modal = $('.js-modal1');
        var $slick = $modal.find('.slick3');
        
        if ($slick.hasClass('slick-initialized')) {
            $slick.slick('unslick');
        }
        
        $slick.html('<div class="item-slick3" data-thumb="'+imgSrc+'"><div class="wrap-pic-w pos-relative"><img src="'+imgSrc+'" alt="IMG-PRODUCT"></div></div>');
        
        setTimeout(function() {
            $slick.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                dots: true,
                arrows: true,
                asNavFor: '.wrap-slick3-dots'
            });
        }, 100);
        
        $modal.find('.js-name-detail').text(name);
        $modal.find('.mtext-106.cl2').text(price);
        $modal.find('.stext-102.cl3').text(desc);
        $modal.addClass('show-modal1');
    });

    /*[ Menu mobile ]
    ===========================================================*/
    $('.btn-show-menu-mobile').on('click', function() {
        $(this).toggleClass('is-active');
        $('.menu-mobile').slideToggle();
    });

    var arrowMainMenu = $('.arrow-main-menu-m');

    for (var i = 0; i < arrowMainMenu.length; i++) {
        $(arrowMainMenu[i]).on('click', function() {
            $(this).parent().find('.sub-menu-m').slideToggle();
            $(this).toggleClass('turn-arrow-main-menu-m');
        });
    }

    $(window).resize(function() {
        if ($(window).width() >= 992) {
            if ($('.menu-mobile').css('display') == 'block') {
                $('.menu-mobile').css('display', 'none');
                $('.btn-show-menu-mobile').toggleClass('is-active');
            }

            $('.sub-menu-m').each(function() {
                if ($(this).css('display') == 'block') {
                    $(this).css('display', 'none');
                    $(arrowMainMenu).removeClass('turn-arrow-main-menu-m');
                }
            });
        }
    });

    /*[ Show/hide modal search ]
    ===========================================================*/
    $('.js-show-modal-search').on('click', function() {
        $('.modal-search-header').addClass('show-modal-search');
        $(this).css('opacity', '0');
    });

    $('.js-hide-modal-search').on('click', function() {
        $('.modal-search-header').removeClass('show-modal-search');
        $('.js-show-modal-search').css('opacity', '1');
    });

    $('.container-search-header').on('click', function(e) {
        e.stopPropagation();
    });

    /*[ Isotope ]
    ===========================================================*/
    var $topeContainer = $('.isotope-grid');
    var $filter = $('.filter-tope-group');

    $filter.each(function() {
        $filter.on('click', 'button', function() {
            var filterValue = $(this).attr('data-filter');
            $topeContainer.isotope({filter: filterValue});
        });
    });

    $(window).on('load', function() {
        var $grid = $topeContainer.each(function() {
            $(this).isotope({
                itemSelector: '.isotope-item',
                layoutMode: 'fitRows',
                percentPosition: true,
                animationEngine: 'best-available',
                masonry: {
                    columnWidth: '.isotope-item'
                }
            });
        });
    });

    var isotopeButton = $('.filter-tope-group button');

    $(isotopeButton).each(function() {
        $(this).on('click', function() {
            isotopeButton.removeClass('how-active1');
            $(this).addClass('how-active1');
        });
    });

    /*[ Filter/Search product ]
    ===========================================================*/
    $('.js-show-filter').on('click', function() {
        $(this).toggleClass('show-filter');
        $('.panel-filter').slideToggle(400);

        if ($('.js-show-search').hasClass('show-search')) {
            $('.js-show-search').removeClass('show-search');
            $('.panel-search').slideUp(400);
        }
    });

    $('.js-show-search').on('click', function() {
        $(this).toggleClass('show-search');
        $('.panel-search').slideToggle(400);

        if ($('.js-show-filter').hasClass('show-filter')) {
            $('.js-show-filter').removeClass('show-filter');
            $('.panel-filter').slideUp(400);
        }
    });

    /*[ Cart ]
    ===========================================================*/
    $('.js-show-cart').on('click', function() {
        $('.js-panel-cart').addClass('show-header-cart');
    });

    $('.js-hide-cart').on('click', function() {
        $('.js-panel-cart').removeClass('show-header-cart');
    });

    /*[ Sidebar ]
    ===========================================================*/
    $('.js-show-sidebar').on('click', function() {
        $('.js-sidebar').addClass('show-sidebar');
    });

    $('.js-hide-sidebar').on('click', function() {
        $('.js-sidebar').removeClass('show-sidebar');
    });

    /*[ +/- num product ]
    ===========================================================*/
    $('.btn-num-product-down').on('click', function() {
        var numProduct = Number($(this).next().val());
        if (numProduct > 0) $(this).next().val(numProduct - 1);
    });

    $('.btn-num-product-up').on('click', function() {
        var numProduct = Number($(this).prev().val());
        $(this).prev().val(numProduct + 1);
    });

    /*[ Rating ]
    ===========================================================*/
    $('.wrap-rating').each(function() {
        var item = $(this).find('.item-rating');
        var rated = -1;
        var input = $(this).find('input');
        $(input).val(0);

        $(item).on('mouseenter', function() {
            var index = item.index(this);
            item.removeClass('zmdi-star').addClass('zmdi-star-outline');
            item.slice(0, index + 1).removeClass('zmdi-star-outline').addClass('zmdi-star');
        });

        $(item).on('click', function() {
            rated = item.index(this);
            $(input).val(rated + 1);
        });

        $(this).on('mouseleave', function() {
            item.removeClass('zmdi-star').addClass('zmdi-star-outline');
            item.slice(0, rated + 1).removeClass('zmdi-star-outline').addClass('zmdi-star');
        });
    });

    /*[ Show modal1 - Alternative ]
    ===========================================================*/
    $('.js-show-modal1').on('click', function(e) {
        e.preventDefault();
        var block = $(this).closest('.block2');
        var imgSrc = block.find('.block2-pic img').attr('src');
        var nome = block.find('.js-name-b2').text().trim();
        var preco = block.find('.stext-105.cl3').text().trim();
        var descricao = block.find('.stext-102.cl3').text().trim();

        var modal = $('.js-modal1');
        var slick3 = modal.find('.wrap-slick3 .slick3');
        
        if (slick3.hasClass('slick-initialized')) {
            slick3.slick('unslick');
        }
        
        slick3.html('<div class="item-slick3" data-thumb="'+imgSrc+'"><div class="wrap-pic-w pos-relative"><img src="'+imgSrc+'" alt="IMG-PRODUCT"><a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04" href="'+imgSrc+'"><i class="fa fa-expand"></i></a></div></div>');
        
        setTimeout(function() {
            slick3.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                dots: true,
                arrows: true,
                asNavFor: '.wrap-slick3-dots'
            });
        }, 100);
        
        modal.find('.js-name-detail').text(nome);
        modal.find('.mtext-106.cl2').text(preco);
        modal.find('.stext-102.cl3').text(descricao);
        modal.addClass('show-modal1');
    });

    $('.js-hide-modal1').on('click', function() {
        $('.js-modal1').removeClass('show-modal1');
    });

})(jQuery);