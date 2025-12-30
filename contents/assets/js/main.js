import './ui';
import './favicon';
import './slider';
import './player';

if (window.location.pathname === '/' || window.location.pathname === '/index.html') {
    import('./synth').then((module) => {
        module.initSynth();
    }).catch((error) => {
        console.error(`Failed to load synth module: ${error.message}`);
    });

    import('./homepage').then((module) => {
        module.init();
    }).catch((error) => {
        console.error(`Failed to load homepage module: ${error.message}`);
    });
}

if (window.location.pathname === '/chi-sono' || window.location.pathname === '/chi-sono.html') {
    import('./about').then((module) => {
        module.initAbout();
    }).catch((error) => {
        console.error(`Failed to load about module: ${error.message}`);
    });
}
