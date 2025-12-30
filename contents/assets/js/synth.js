export function initSynth() {
    const maxGain = 1;
    const muteControl = document.getElementById('keyboard-mute');
    const keyboard = document.getElementById('keyboard');
    const keys = keyboard.getElementsByClassName('key');

    const audioContext = new AudioContext();
    audioContext.suspend();
    const oscList = [];

    // Setup gain node
    const mainGainNode = audioContext.createGain();

    mainGainNode.connect(audioContext.destination);
    mainGainNode.gain.value = maxGain;

    mainGainNode.gain.setValueAtTime(0, audioContext.currentTime);
    mainGainNode.gain.linearRampToValueAtTime(0.2, audioContext.currentTime + 0.5);

    // Prepare wave form
    const sineTerms = new Float32Array([0, 0, 0, 0, 0, 0, 0, 0]);
    const cosineTerms = new Float32Array([0, 0.5, 0, 0.25, 0, 0.125, 0, 0.0625]);
    const waveForm = audioContext.createPeriodicWave(cosineTerms, sineTerms);

    // Secret combination
    const correctCombination = ['C4', 'D#4', 'G4', 'B4'];
    let currentCombination = [];

    // Prevent drag
    let currentClickedKey = null;

    /**
     * Listen to mute control
     */

    muteControl.addEventListener('click', function (e) {
        e.preventDefault();

        if (muteControl.dataset.muted === 'false') {
            audioContext.suspend();
            muteControl.dataset.muted = 'true';

            muteControl.getElementsByClassName('')

            return;
        }

        audioContext.resume();
        muteControl.dataset.muted = 'false';
    });

    /**
     * Try combination
     *
     * @param note
     * @return void
     */

    function tryCombination(note) {
        let position = currentCombination.length;

        if (note !== correctCombination[position]) {
            currentCombination = [];
            return;
        }

        currentCombination.push(note);

        if (currentCombination.length === correctCombination.length) {
            setInterval(() => window.location.replace('/darkside'), 500);
        }
    }

    /**
     * Play a tone
     *
     * @param frequency
     * @return void
     */

    function playTone(frequency)
    {
        const oscillator = audioContext.createOscillator();
        oscillator.connect(mainGainNode);

        mainGainNode.gain.value = maxGain / oscList.length;

        oscillator.setPeriodicWave(waveForm);
        oscillator.frequency.value = frequency;
        oscillator.start();

        return oscillator;
    }

    /**
     * Note pressed
     *
     * @param key
     * @return void
     */

    function pressNote(key)
    {
        if (!key.dataset.pressed) {
            oscList[key.dataset['name']] = playTone(key.dataset.frequency);
            key.dataset.pressed = true;
            tryCombination(key.dataset['name']);
        }
    }

    /**
     * Note realeased
     *
     * @param key
     * @return void
     */

    function releaseNote(key)
    {
        if (key.dataset && key.dataset.pressed) {
            if (oscList[key.dataset['name']]) {
                oscList[key.dataset['name']].stop();
                delete oscList[key.dataset['name']];
                delete key.dataset.pressed;
            }
        }
    }

    /**
     * Click press note
     *
     * @param e
     * @return void
     */

    function clickPressNote(e) {
        if (e.buttons & 1) {
            e.stopPropagation();

            if (currentClickedKey !== null && e.target !== currentClickedKey) {
                releaseNote(currentClickedKey);
            }

            currentClickedKey = e.target;
            pressNote(e.target);
        }
    }

    /**
     * Click release note
     *
     * @param e
     * @return void
     */

    function clickReleaseNote(e) {
        releaseNote(e.target);
    }

    for (let i = 0; i < 9; i++) {
        oscList[i] = {};
    }

    // Register events for each note
    for (const key of keys) {
        key.addEventListener('mousedown', clickPressNote, false);
        key.addEventListener('mouseup', clickReleaseNote, false);
        key.addEventListener('mouseover', clickPressNote, false);
        key.addEventListener('mouseleave', clickReleaseNote, false);

        key.addEventListener('touchstart', function (e) {
            pressNote(e.target);
        }, false);
        key.addEventListener('touchend', function (e) {
            releaseNote(e.target);
        }, false);
    }

    // Listen for key presses
    window.addEventListener('keydown', function (e) {
        const key = keyboard.querySelector('.key[data-shortcut="' + e.key + '"');

        if (!key) {
            return;
        }

        pressNote(key);
    }, false);

    window.addEventListener('keyup', function (e) {
        const key = keyboard.querySelector('.key[data-shortcut=' + e.key);

        if (!key) {
            return;
        }

        releaseNote(key);
    });
};
