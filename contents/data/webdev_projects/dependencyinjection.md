---
name: Dependency Injection
description: Un container PHP per la gestione semplificata del boot di un applicativo tramite l'uso della dependency injection.
github: https://github.com/gspataro/DependencyInjection
version: '1.0.0'
category: component
published: 2022-07-05
technologies:
    - PHP
---

GSpataro/DependencyInjection è un componente PHP per la gestione delle dipendenze degli oggetti.

## Sviluppo

Questo componente nasce dal voler apprendere il funzionamento di un container per la gestione della dependency injection. Per semplificazione
questo container non risolve automaticamente le dipendenze, ma bisogna creare una mappa manualmente.
La principale fonte di ispirazione è stato il Service Provider del framework Laravel.

Le sfide che mi si sono poste davanti sono:

- Creazione di una sintassi semplice
- Gestione del boot dell'applicativo
