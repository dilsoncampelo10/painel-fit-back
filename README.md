# Painel FIT Back

Uma API RESTful para usuários conseguirem acompanhar sua rotina de treinos, progresso e plano nutricional. 

---

## Funcionalidades

-   Autenticação de Usuários com Sanctum
-   Gerenciamento de Treinos (CRUD)
-   Gerenciamento de planos nutricionais


## 🚀 Tecnologias

- Laravel 12
- Laravel Sail (Docker)
- MySQL
- PHP 8.1+
- Sanctum (autenticação API)
- SoftDeletes (remoção lógica)
- Eloquent (ORM)

---

## 🛠️ Instalação

> Requisitos: Docker instalado.

```bash
    git clone https://github.com/dilsoncampelo10/painel-fit-back
    cd painel-fit-back
    cp .env.example .env
    ./vendor/bin/sail up -d
    ./vendor/bin/sail composer install
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan migrate
```



### A fazer:
- [ ] Testes de unidade
- [ ] Documentação com Swagger
- [ ] Criação de Pipeline