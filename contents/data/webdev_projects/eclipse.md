---
name: Eclipse
description: Concept per la homepage di un musicista/gruppo musicale utilizzando Vite come frontend build tool
github: https://github.com/gspataro/DependencyInjection
link: https://demo.giuseppespataro.it/eclipse
version: '1.0.0'
category: concept
published: 2025-04-16
technologies:
    - TailwindCSS
    - Vite
---

**Eclipse** è un concept per un template moderno di homepage pensato per musicisti o band, progettato con un focus sulla user experience e sulla performance.
La struttura si articola in tre sezioni chiave, ciascuna progettata per massimizzare l’engagement:

1. **Hero section** con **call-to-action** per l’ascolto immediato dell’ultimo album.

2. **Tour overview**, una panoramica del tour in corso

3. **Newsletter subscription box**, progettata per favorire la lead generation e fidelizzazione del pubblico.

La **hero section** è il cuore visivo del progetto, progettata per evocare l’atmosfera di un vero palco live. Lo sfondo dinamico riproduce un gioco di luci in movimento, simulando i classici **spotlight da concerto** alla ricerca del performer sul palco. Questo effetto è stato realizzato utilizzando interamente animazioni, clip-path, gradienti e filtri CSS, mantenendo un equilibrio tra impatto visivo e performance.

Per lo sviluppo dello UI layer ho scelto **Tailwind CSS**, sfruttando l’approccio utility-first per ottenere massima personalizzazione e controllo stilistico senza introdurre overhead strutturali. Ho utilizzato la versione ```^4.0.17```, beneficiando delle nuove feature come l’estensione nativa delle utility e la configurazione centralizzata direttamente nel file CSS principale.

Il build system si basa su **Vite**, selezionato per la sua velocità di compilazione e supporto out-of-the-box a moduli moderni. Ho integrato il plugin di TailwindCSS all’interno del processo di build, ottimizzando la generazione degli asset e sfruttando il **fast refresh** di Vite per un ciclo di sviluppo estremamente reattivo.
