document.addEventListener('DOMContentLoaded', function () {
    initHeader();

    function initHeader()
    {
        const header = document.getElementById('header');
        const navbarToggler = document.getElementById('navbar-toggle');

        if (!header || !navbarToggler) {
            return;
        }

        navbarToggler.onclick = function (e) {
            e.preventDefault();

            if (header.dataset.open === 'false') {
                header.dataset.open = 'true';
                document.body.classList.add('hamburger-open');
            } else {
                header.dataset.open = 'false';
                document.body.classList.remove('hamburger-open');
            }
        };
    }

    const customCursor = document.createElement('div');
    customCursor.style.display = 'none';
    customCursor.classList.add('cursor');
    document.body.append(customCursor);

    let isTouch = false;

    document.addEventListener('touchstart', function (e) {
        isTouch = true;

        customCursor.style.display = 'none';
    }, {
        passive: true
    });

    document.addEventListener('mousemove', function (e) {
        isTouch = false;
    });

    document.addEventListener('pointermove', function (e) {
        if (isTouch) {
            return;
        }

        const xpos = e.clientX - (customCursor.clientWidth / 2);
        const ypos = e.clientY - (customCursor.clientHeight / 2);

        customCursor.style.display = 'block';

        customCursor.style.top = ypos + 'px';
        customCursor.style.left = xpos + 'px';
    });

    document.addEventListener('mouseover', function (e) {
        if (isTouch) {
            return;
        }

        if (
            e.target.tagName.toLowerCase() !== 'a' &&
            e.target.closest('a') === null &&
            e.target.tagName.toLowerCase() !== 'button' &&
            e.target.closest('button') === null) {
            return;
        }

        customCursor.classList.add('hover');
    });

    document.addEventListener('mouseout', function (e) {
        if (isTouch) {
            return;
        }

        if (
            e.target.tagName.toLowerCase() !== 'a' &&
            e.target.closest('a') === null &&
            e.target.tagName.toLowerCase() !== 'button' &&
            e.target.closest('button') === null) {
            return;
        }

        customCursor.classList.remove('hover');
    });

    document.addEventListener('mousedown', function () {
        if (isTouch) {
            return;
        }

        customCursor.classList.add('click');
    });

    document.addEventListener('mouseup', function () {
        if (isTouch) {
            return;
        }

        customCursor.classList.remove('click');
    });
});
