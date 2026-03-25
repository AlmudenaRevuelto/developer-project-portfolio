# Developer Project Portfolio

Personal developer portfolio built with **PHP, Docker, Twig and the GitHub API**.

This project acts as a **central hub for showcasing GitHub profile and repositories**.

The goal of this project is to serve as a **technical portfolio** with a lightweight MVC frontend that consumes public GitHub data.

---

# Project Overview

This application renders:

* GitHub user profile information on Home
* GitHub repositories on Projects

The frontend consumes the public GitHub API directly and renders the data using **Twig templates**.

---

# Tech Stack

### Frontend

* PHP
* Twig
* HTML
* CSS
* Vanilla JavaScript

### Data source

* GitHub REST API

### Infrastructure

* Docker
* Apache
* Git / GitHub

---

# Architecture

The project is divided into two main layers:

```
frontend
   ↓
GitHub API
```

---

### Frontend

```
frontend
 ├── public
 ├── src
 │   └── Controller
 ├── templates
 └── assets
```

Responsibilities:

* Controller → fetch data from GitHub service
* Service → call GitHub API and normalize responses
* Twig templates → render views
* CSS / JS → presentation and behaviour

---

# Running the Project

Requirements:

* Docker
* Docker Compose

Start the environment:

```
docker compose up --build
```

Application will be available at:

```
http://localhost:8000
```

---

# Development Goals

This project is intentionally built without large frameworks in order to:

* practise **clean MVC structure**
* learn **Docker based development**
* integrate **frontend templating with Twig**
* consume an external API cleanly from a PHP application

Future improvements will include:

* repository filtering
* pinned repositories
* project screenshots
* improved frontend interface
* better error handling for API rate limits

---

# Author

Almudena Revuelto
Software Developer
