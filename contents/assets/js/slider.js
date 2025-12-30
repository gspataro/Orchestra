import Swiper from "swiper";
import { Navigation } from "swiper/modules";

new Swiper('.swiper.slider-services', {
    modules: [Navigation],
    slidesPerView: 1,
    spaceBetween: window.getComputedStyle(document.body).getPropertyValue('--spacing-sm'),
    navigation: {
        prevEl: '.swiper-button-prev',
        nextEl: '.swiper-button-next'
    },
    breakpoints: {
        744: {
            slidesPerGroup: 2,
            slidesPerView: 2
        },
        1280: {
            slidesPerGroup: 3,
            slidesPerView: 3
        }
    }
});

new Swiper('.swiper.slider-blog', {
    modules: [Navigation],
    slidesPerView: 1,
    spaceBetween: window.getComputedStyle(document.body).getPropertyValue('--spacing-sm'),
    navigation: {
        prevEl: '.swiper-button-prev',
        nextEl: '.swiper-button-next'
    },
    breakpoints: {
        744: {
            slidesPerGroup: 2,
            slidesPerView: 2
        },
        1280: {
            slidesPerGroup: 3,
            slidesPerView: 3
        }
    }
});
