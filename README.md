# API Restful - Pastelaria

## Sobre o Projeto

Esta API Restful oferece funcionalidades para gerenciar Clientes, Produtos e Pedidos, com suporte completo para operações CRUDL (Criar, Ler, Atualizar, Deletar e Listar). O sistema foi desenvolvido com PHP 8.2 e Laravel 11, utilizando Docker para facilitar a configuração e a execução do ambiente.

-   Clientes nome, e-mail, telefone, data de nascimento, endereço, complemento, bairro, cep, data de cadastro;
-   Produtos nome, preço, foto;
-   Pedidos código do cliente, código do produto, data da criação;

**Autor:** Gustavo Ribeiro Bailo Silva

## Requisitos

-   **PHP**: 8.2
-   **Laravel**: 11
-   **Docker**: Certifique-se de que o Docker está instalado e em execução.

## Como Rodar a Aplicação

Siga os passos abaixo para configurar e rodar a aplicação:

1. **Build da Aplicação**:

    ```bash
    docker-compose build
    ```

2. **Iniciar os containers:**

    ```bash
    docker-compose up -d
    ```

3. **Executar as migrações do banco de dados:**

    ```bash
    php artisan migrate
    ```

4. **Popular o banco de dados com dados iniciais:**

    ```bash
    php artisan db:seed
    ```

## API - Rotas

### Clientes

| Método | Endpoint              | Descrição                         |
| ------ | --------------------- | --------------------------------- |
| GET    | `/client/list`        | Lista todos os clientes           |
| POST   | `/client/create`      | Cria um novo cliente              |
| GET    | `/client/detail/{id}` | Detalhes de um cliente específico |
| PUT    | `/client/detail/{id}` | Atualiza um cliente específico    |
| DELETE | `/client/delete/{id}` | Deleta um cliente específico      |

### Produtos

| Método | Endpoint               | Descrição                         |
| ------ | ---------------------- | --------------------------------- |
| GET    | `/product/list`        | Lista todos os produtos           |
| POST   | `/product/create`      | Cria um novo produto              |
| GET    | `/product/detail/{id}` | Detalhes de um produto específico |
| PUT    | `/product/detail/{id}` | Atualiza um produto específico    |
| DELETE | `/product/delete/{id}` | Deleta um produto específico      |

### Pedidos

| Método | Endpoint             | Descrição                        |
| ------ | -------------------- | -------------------------------- |
| GET    | `/order/list`        | Lista todos os pedidos           |
| POST   | `/order/create`      | Cria um novo pedido              |
| GET    | `/order/detail/{id}` | Detalhes de um pedido específico |
| PUT    | `/order/detail/{id}` | Atualiza um pedido específico    |
| DELETE | `/order/delete/{id}` | Deleta um pedido específico      |

## Testes Unitários

Para executar os testes unitários, utilize o seguinte comando:

```bash
php artisan test tests/Unit
```
