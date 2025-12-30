---
name: Portfolio
description: Il codice sorgente del mio portfolio.
github: https://github.com/gspataro/Portfolio
link: https://giuseppespataro.it
version: '3.0'
category: website
published: 2022-06-15
technologies:
    - PHP
    - TailwindCSS
    - HTML
    - Javascript
---

Il mio portfolio è un esempio concreto dei miei esperimenti. Si tratta di un sito web statico e ho colto questa sfida per poter creare un generatore interamente
personalizzato. Questo generatore converte i dati markdown/json in delle pagine HTML applicandole a dei template definiti.

## Il generatore

Creare un generatore di siti web statici può sembrare semplice, ma in realtà è una sfida piuttosto complessa. La prima difficoltà è stata gestire in maniera flessibile i dati e cercare di rendere il processo il più automatico possibile. Nel mio caso ho deciso di creare un file che faccia da template per la generazione del sito, il blueprint.json. In questo file definisco la struttura principale del sito e il modo in cui devono essere generate le pagine.

## Sviluppo

Una volta stabilita quale doveva essere la struttura del file blueprint.json, il prossimo passo era creare il resto dell'applicativo. Un ulteriore complicazione è derivata dal fatto che ho deciso di non seguire il principio del "non reinventare la ruota". L'applicativo è stato costruito interamente da zero e utilizzando i componenti <a href="{{url('webdev_project.cli')}}">CLI</a> e <a href="{{url('webdev_project.dependencyinjection')}}">Dependency Injection</a>.
Questa scelta è stata voluta per darmi l'opportunità di imparare qualcosa di nuovo sia sugli applicativi eseguibili da riga di comando che sui generatori
statici.

## Tecnologie e pacchetti di terze parti

Il generatore e il sito web vero e proprio sono stati realizzati utilizzando PHP, HTML, TailwindCSS e Javascript.

Per quanto riguarda PHP, ho utilizzato alcuni pacchetti di terze parti:

- <a href="https://github.com/nunomaduro/collision" target="_blank">nunomaduro/collision</a> - Gestione degli errori per applicazioni CLI
- <a href="https://github.com/twigphp/Twig" target="_blank">twig/twig</a> - Template engine
- <a href="https://github.com/thephpleague/commonmark" target="_blank">league/commonmark</a> - Interprete markdown
- <a href="https://github.com/symfony/yaml" target="_blank">symfony/yaml</a> - Interprete YAML
- <a href="https://github.com/tempestphp/highlight" target="_blank">tempest/highlight</a> - Highlighter per gli snippet di codice
