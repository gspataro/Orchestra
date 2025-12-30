export function initAbout() {
    const experiences = document.getElementById('experiences');
    const viewMoreButton = document.getElementById('experiences-more');

    viewMoreButton.addEventListener('click', function (e) {
        e.preventDefault();

        if (experiences.dataset.expanded !== undefined) {
            delete experiences.dataset.expanded;
        } else {
            experiences.dataset.expanded = '';
        }
    });
}
