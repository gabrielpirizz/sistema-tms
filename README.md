# Sistema de Rastreamento de Entregas

Teste desenvolvido em Laravel para rastreamento de entregas por CPF do destinatário.

## Tecnologias Utilizadas

- **PHP**: ^8.2
- **Laravel Framework**: ^12.0
- **SQLite**: Banco de dados
- **PHPUnit**: ^11.5.3 (Testes)

## Pré-requisitos

Antes de iniciar, certifique-se de ter instalado em sua máquina:

- **PHP 8.2** ou superior
- **Composer** (gerenciador de dependências PHP)
- **Node.js 18+** e **npm** (para assets frontend)

## Instalação

### 1. Clone o repositório

```bash
git clone [URL_DO_REPOSITORIO]
cd teste-tecnico
```

### 2. Instale as dependências

```bash
composer install
```

### 3. Instale as dependências Node.js

```bash
npm install
```

### 4. Configure o ambiente

```bash
# Copie o arquivo de exemplo
cp .env.example .env

# Gere a chave da aplicação
php artisan key:generate
```

### 5. Configure o banco de dados

O projeto usa SQLite por padrão. O arquivo já está incluído em `database/database.sqlite`.

```bash
# Execute as migrações
php artisan migrate

# (Opcional) Execute os seeders se houver
php artisan db:seed
```

### 6. Compile os Assets

```bash
# Para desenvolvimento
npm run dev

# Para produção
npm run build
```

## Executando o Projeto

### Modo Desenvolvimento

```bash
# Inicie o servidor Laravel
php artisan serve

O projeto estará disponível em: `http://localhost:8000`

## Executando Testes

```bash
# Todos os testes
php artisan test

# Apenas testes unitários
php artisan test tests/Unit/

# Apenas testes de feature
php artisan test tests/Feature/


### Estrutura de Testes
- **Testes Unitários**: `tests/Unit/EntregaServiceTest.php`
- **Testes de Feature**: `tests/Feature/EntregaControllerTest.php`


## Funcionalidades

### Principais Rotas
- `GET /` - Página inicial (redirecionada para busca)
- `GET /entregas` - Formulário de busca por CPF
- `POST /entregas/buscar` - Busca entregas por CPF
- `GET /entregas/listar` - Lista todas as entregas
- `GET /entregas/detalhar/{id}` - Detalhes de uma entrega

### Validações
- **CPF**: Validação de formato e dígitos verificadores
- **Sanitização**: Remoção automática de caracteres especiais

### Fonte de Dados
- Arquivos JSON em `storage/app/private/`:
  - `API_LISTAGEM_ENTREGAS.json`
  - `API_LISTAGEM_TRANSPORTADORAS.json`

- Banco de Dados: SQLite (arquivo incluído)

## Solução de Problemas

### caso possívelmente o banco de Dados não seja encontrado
```bash
# Recrie o arquivo SQLite
touch database/database.sqlite
php artisan migrate:fresh
```

### Problema: Assets Não Carregam 
```bash

npm run build
php artisan config:clear
php artisan cache:clear
```

### Problema: Erro de Chave
```bash
# Regenere a chave da aplicação
php artisan key:generate
```