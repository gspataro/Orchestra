function playerTimeFormat(seconds) {
    seconds = Math.floor(seconds);

    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;

    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
};

function initPlayer() {
    const players = document.getElementsByClassName('player');
    let playing = null;

    if (!players) {
        return;
    }

    Array.prototype.forEach.call(players, (player) => {
        const audio = player.querySelector('audio');
        const playButton = player.querySelector('[data-control=play]');
        const stopButton = player.querySelector('[data-control=stop]');

        audio.addEventListener('pause', function () {
            delete player.dataset.playing;
        });

        audio.addEventListener('play', function () {
            player.dataset.playing = 'true';
        });

        playButton.addEventListener('click', function (e) {
            e.preventDefault();

            if (playing !== null && playing !== audio) {
                playing.pause();
            }

            if (audio.paused) {
                audio.play();
                playing = audio;

                return;
            }

            audio.pause();
        });

        stopButton.addEventListener('click', function (e) {
            e.preventDefault();

            if (playing !== null && playing === audio) {
                playing = null;
            }

            audio.currentTime = 0;
            audio.pause();
        });
    });
}

initPlayer();
