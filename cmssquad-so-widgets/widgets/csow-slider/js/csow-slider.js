/**
 * CMSSquad slider - frontend script
 * Dependencies: jQuery, TweenMax, imagesLoaded
 * @author CMSSquad
 */

;(function ($) {
    'use strict';

    $(document).ready(function(){

        $('.csow-slider').each(function () {

            var $this = $(this);

            // get this slider wrapper
            $this.wrapper = $this.closest('.csow-slider-wrapper');

            // slider option: show navigation or not
            $this.showNav = !!$this.data('show-nav');

            // slider option: autoplay
            $this.autoplay = !!$this.data('autoplay');

            // slider option: slider type (standard, fullscreen, ..)
            $this.sliderType = $this.data('type');

            // slider option: each slide's timeout before moving to the next slide
            $this.timeout = $this.data('timeout');

            // slider option: pause slide transition on mouse hover
            $this.pauseHover = !!$this.data('pause-hover');

            // starting slide position
            $this.currentSlideIdx = -1;

            // get all slider items
            $this.slideItems = $this.find('.csow-slider-item');

            // nav
            $this.navs = $this.find('.csow-slider-nav');

            // how many of them slider items
            $this.slideCount = $this.slideItems.length;

            // pointer to slider setInterval()
            $this.slideInterval = false;

            // store slide state
            $this.sliderState = "not-started"; // not-started, playing, paused

            /**
             * Initiate slider
             */
            $this.init = function () {

                // do slide when all images (and background images) are fully loaded.
                $this.imagesLoaded({background: true}, function () {

                    $this.setSliderDimension();

                    // call the first slide
                    $this.goToNext();

                    // start slider automatically when autoplay is true
                    if ($this.autoplay) {
                        // then re-call it repeatedly
                        $this.resumeSlider();
                    }

                    // handle on cursor over
                    if ($this.pauseHover) {
                        $this.mouseHandler();
                    }

                    // handle next-prev navigation button
                    if ($this.showNav) {
                        $this.navHandler();
                    }
                });

                $(window).resize(function () {
                    $this.setSliderDimension();
                });
            };

            /**
             * Animate this slider from prevSlideIdx to nextSlideIdx
             * @param nextSlideIdx
             * @param prevSlideIdx
             */
            $this.animateSlide = function (nextSlideIdx, prevSlideIdx) {
                var nextSlide = $this.slideItems.eq(nextSlideIdx);

                if (prevSlideIdx > -1) {
                    var prevSlide = $this.slideItems.eq(prevSlideIdx);
                    TweenMax.to(prevSlide, 0.8, {
                        alpha: 0, onComplete: function () {
                            prevSlide.removeClass('active').css({
                                'visibility': 'hidden'
                            });
                        }
                    });
                }

                nextSlide.addClass('active').css({
                    'visibility': 'visible'
                });
                TweenMax.to(nextSlide, 0.8, {alpha: 1});
            };

            /**
             * Start this slider
             */
            $this.resumeSlider = function () {
                //repeat every timeout ms
                $this.slideInterval = setInterval($this.goToNext, $this.timeout);
                $this.sliderState = "playing";
            };

            /**
             * Pause slider
             */
            $this.pauseSlider = function () {
                clearInterval($this.slideInterval);
                $this.sliderState = "paused";
            };

            /**
             * Go to prev slide
             */
            $this.goToPrev = function () {
                $this.pauseSlider();

                var prevSlideIdx = $this.currentSlideIdx;
                $this.currentSlideIdx--;
                if ($this.currentSlideIdx < 0) {
                    $this.currentSlideIdx = $this.slideCount - 1;
                }

                $this.animateSlide($this.currentSlideIdx, prevSlideIdx);
            };

            /**
             * Go to next slide
             */
            $this.goToNext = function () {
                $this.pauseSlider();

                var prevSlideIdx = $this.currentSlideIdx;

                // set and show currentSlide
                $this.currentSlideIdx++;

                // reset slide if reach max
                if ($this.currentSlideIdx >= $this.slideCount) {
                    $this.currentSlideIdx = 0;
                }

                // animate
                $this.animateSlide($this.currentSlideIdx, prevSlideIdx);
            };

            /**
             * What happens on screen resize
             */
            $this.onResize = function () {
                setTimeout(function () {
                    $this.setSliderDimension();

                }, 300);

            };

            /**
             * Set slider dimension or layout, based on slider type..
             */
            $this.setSliderDimension = function () {

                if ($this.sliderType === 'standard') {
                    var highest = 0;
                    $this.slideItems.each(function () {
                        var height = $(this).outerHeight();
                        if (height > highest) {
                            highest = height;
                        }
                    });

                    $this.height(highest);
                }
                else if ($this.sliderType === 'fullscreen') {
                    // set the slider dimension to fit the browser width/height
                    var $wrapper = $this.parent(),
                        $sliderItems = $this.find('.csow-slider-item, .slider-fullscreen-image'),
                        $filler = $this.wrapper.find('.csow-slider-filler');

                    // set filler to fill the screen first
                    $filler.css({
                        height: $(window).height()
                    });

                    $sliderItems.css({
                        height: $(window).height()
                    });

                    // get window dimension here, because when filler is set - a scrollbar might appear making vW not relevant anymore
                    var wW = $(window).width(),
                        wH = $(window).height();

                    $wrapper.css({
                        width: wW,
                        height: wH,
                        left: -1 * $filler.offset().left
                    });
                } // end of fullscreen type

            };

            /**
             * Animate and show nav buttons
             */
            $this.showNav = function () {
                $this.navs.show();
                TweenMax.to($this.navs, 0.4, {alpha: 1});

            };

            /**
             * Animate and hide nav buttons
             */
            $this.hideNav = function () {
                TweenMax.to($this.navs, 0.4, {
                    alpha: 0, onComplete: function () {
                        $this.navs.hide();
                    }
                });
            };

            /**
             * Handle pause on mouseoover
             */
            $this.mouseHandler = function () {
                $this.mouseenter(function () {
                    $this.pauseSlider();
                });

                $this.mouseleave(function () {
                    $this.resumeSlider();
                });
            };

            /**
             * Handle nav buttons behavior
             */
            $this.navHandler = function () {
                $this.mouseenter(function () {
                    $this.showNav();
                });

                $this.mouseleave(function () {
                    $this.hideNav();
                });

                $this.navs.click(function (e) {
                    e.preventDefault();

                    var clickedBtn = $(this);

                    if (clickedBtn.hasClass('prev')) {
                        $this.goToPrev();
                    }
                    else if (clickedBtn.hasClass('next')) {
                        $this.goToNext();
                    }

                    return false;

                });

            };

            // init() the slider
            $this.init();

        });

    });

})(jQuery);