---
title: 'Note e codice: la storia dietro il mio sito web, sviluppo e design'
description: 'Dalla musica al codice: il processo creativo dietro lo sviluppo del mio sito web. Scopri come ho unito PHP, TailwindCSS e JavaScript in un design minimalista e interattivo.'
keywords: creare un sito web, sviluppo web, sito statico, web design minimalista, PHP, TailwindCSS, JavaScript, UX, interattività, SEO
categories:
 - dev
published: 2025-03-13
thumbnail:
    src: blog-note-codice.jpg
    alt: Scrivania con computer desktop e portatile
    caption: 'Foto di <a href="https://unsplash.com/photos/a-macbook-with-lines-of-code-on-its-screen-on-a-busy-desk-m_HRfLhgABo" target="_blank">Christopher Gower</a>'
---

Creare un sito web è un'impresa impegnativa: richiede uno studio attento sulla disposizione dei contenuti, sul design e deve riflettere l'identità di chi o cosa si sta rappresentando. Di solito, si tratta di siti web dedicati a un singolo prodotto o a una persona con una specifica attività. Ma cosa succede quando le attività da rappresentare sono molteplici?

[ARTICLE:TOC]

## Strutturare la navigazione per un sito web multi-attività

Essendo sia pianista che sviluppatore web, volevo realizzare uno spazio personale che mettesse in evidenza entrambe le mie attività. Tuttavia, la gestione della navigazione si stava rivelando complessa a causa di alcune differenze:

- Per lo sviluppo web, desideravo evidenziare sia le tecnologie che utilizzo sia i progetti realizzati e quelli in corso.
- Per la musica, invece, volevo mostrare la mia biografia e le mie esperienze professionali e formative.

Entrambe le attività dovevano avere lo stesso rilievo. Per risolvere il problema, ho scelto di mantenere i due mondi completamente separati, ognuno con una propria pagina dedicata. A volte la soluzione più semplice è quella più funzionale.

Per collegare le pagine, ho optato per una scheda riassuntiva su di me in homepage, con la possibilità di approfondire una delle due attività. Questo aiuta l'utente a comprendere rapidamente di cosa mi occupo e a navigare facilmente tra i contenuti.

## Scelta dello stile: minimalismo e coerenza visiva

La prima parola che mi è venuta in mente è stata: **minimalismo**. Volevo un sito web semplice, privo di distrazioni e ottimizzato per l'usabilità.

La scelta della **palette di colori** è stata ispirata dal pianoforte: nero della carcassa, avorio dei tasti, bronzo del telaio e rosso scuro dei feltri. L'obiettivo era ottenere uno stile simile a un foglio di carta appena stampato, privilegiando i toni avorio e nero per migliorare la leggibilità.

Per creare questa palette, ho deciso di cercare online dei servizi che estraessero i colori principali dalle foto. Personalmente ho utilizzato il generatore di palette di <a href="https://www.canva.com/colors/color-palette-generator/" target="_blank">Canva</a>. Per ottenere le tonalità che mi interessavano, ho ripetuto il processo più volte e con svariate foto. Una volta ottenuti dei colori che mi convincessero, ho continuato a sperimentare diverse tonalità su Figma.

Per i **font** volevo qualcosa che ricordasse la tipografia di un magazine, con dei titoli molto grandi e d’effetto. La selezione è così ricaduta su tre font differenti:

- **Oswald** per i testi più grandi
- **Geist** per le intestazioni
- **Roboto** per i testi principali

## Sviluppo del sito web: il backend

Ho deciso di realizzare un **sito web statico**, ideale per garantire velocità di caricamento e semplicità di gestione. Essendo l'unico a doverlo aggiornare, non trovavo utile appesantire il progetto con un CMS o un framework complesso.

Il **backend** è interamente scritto in PHP e utilizza il template engine **Twig** per la gestione delle views.

Ho creato un **generatore di siti statici in PHP**, utilizzando poche librerie di terze parti per mantenere il codice leggero ed efficiente. Questo generatore fa uso di un file chiamato **blueprint.json** che definisce lo schema di generazione delle pagine.

Nello specifico, ogni schema contiene alcuni parametri fondamentali:

- Il **template HTML** utilizzato
- Il **generatore** che stabilisce la strategia di generazione delle pagine
- Il **builder** che definisce come i contenuti generati vengono trasformati in pagine

Ho deciso di scrivere il generatore statico da zero perché volevo affrontare una nuova sfida. Potevo utilizzare uno dei tanti generatori esistenti, ma avrei eliminato tutto il divertimento. È una cosa che consiglio di fare ma solo per progetti personali, si tratta di una sfida abbastanza impegnativa.

## Il frontend: ottimizzazione e performance

Il **frontend** è costruito con **HTML, TailwindCSS e JavaScript**, garantendo un design moderno e responsive.

Grazie all’utilizzo di Twig è stato possibile creare un set di layout e componenti riutilizzabili, in questo modo ho ridotto il quantitativo di codice da mantenere al minimo indispensabile.

Ho deciso di utilizzare **TailwindCSS** per la sua filosofia utility-first, che mi permette di scrivere codice CSS ottimizzato senza dover cominciare completamente da zero.

Il **layout** è stato progettato per favorire la leggibilità, richiamando l'idea di un foglio di carta. L'unica eccezione è la homepage, sviluppata con uno scorrimento orizzontale per evocare l'effetto della tastiera di un pianoforte, creando un'esperienza immersiva.

Proprio lo scorrimento orizzontale della homepage ha richiesto uno studio specifico. Avrei potuto semplicemente convertire lo scrolling verticale in orizzontale, ma è una soluzione che non mi convinceva. L’altezza delle singole sezioni sarebbe stata limitata dalla grandezza del dispositivo, questo avrebbe creato problemi per la disposizione dei contenuti in mobile.

Ho optato quindi per mantenere sia lo scrolling verticale che orizzontale. Quello orizzontale è gestito suddividendo la homepage in una griglia. Ogni elemento della griglia occupa l’intero viewport orizzontale. Per gestire la navigazione tra una sezione e l’altra ho optato per tre modalità diverse, in modo da semplificare l’esperienza utente:

- Link con i nomi delle sezioni e delle frecce che indicano nel verso dello scroll
- Scroll orizzontale o drag nei dispositivi touch, gestito con la proprietà scroll snap di CSS
- Tasti freccia della tastiera

Per gestire i cambi di colore tra una sezione e l’altra, uno script JS si occupa di effettuare il setup impostando il colore dell’header e del footer.

## Interattività: un tocco creativo al sito web

Un aspetto distintivo del mio sito web è la sua **interattività**. Fin dall'inizio avevo un'idea fissa: volevo un pianoforte che suonasse. Così ho deciso di inserire in homepage un **sintetizzatore interattivo**, che permette agli utenti di suonare semplici melodie grazie a JavaScript.

## Conclusioni e prospettive future

Sono piuttosto soddisfatto del risultato finale del mio sito web. Certamente ci sono ancora margini di miglioramento, ma, a differenza di un documento stampato, un sito internet è una piattaforma dinamica in continua evoluzione.

Se sei interessato a degli approfondimenti, puoi navigare il codice sorgente su <a href="https://github.com/gspataro/Portfolio" target="_blank">Github</a>.

Questa è la mia **vetrina virtuale**, e voglio che sia il primo tassello di una lunga serie di revisioni e ottimizzazioni per migliorare l'esperienza utente e la mia presenza online.
