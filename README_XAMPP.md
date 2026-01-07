# Como Visualizar o Site no XAMPP

## Passo 1: Copiar o projeto para o XAMPP

1. Localize a pasta `htdocs` do XAMPP (geralmente em `C:\xampp\htdocs`)
2. Copie a pasta `cipa` para dentro de `htdocs`
   - O caminho final deve ser: `C:\xampp\htdocs\cipa\`

## Passo 2: Configurar o Banco de Dados

1. Abra o **phpMyAdmin** (acesse: http://localhost/phpmyadmin)
2. Crie um novo banco de dados chamado: `projetocipat3`
3. Importe o script SQL do banco de dados (se você tiver um arquivo .sql)
   - Ou crie as tabelas manualmente conforme o diagrama ERD

### Tabelas necessárias (baseado no diagrama):
- `usuario`
- `eleicao`
- `candidato`
- `lista_candidatos`
- `voto`
- `branco_nulo`
- `documentos`

## Passo 3: Configurar a Conexão

Edite o arquivo `cipa/utils/Conexao.php` e verifique as configurações:

```php
static string $servidor = "127.0.0.1"; // ou "localhost"
static string $usuario = "root";
static string $password = ""; // Deixe vazio se não tiver senha no MySQL
static string $port = "3306";
static string $dbname = "projetocipat3";
```

## Passo 4: Iniciar os Serviços

1. Abra o **XAMPP Control Panel**
2. Inicie o **Apache**
3. Inicie o **MySQL**

## Passo 5: Acessar o Site

Abra seu navegador e acesse:

- **Página inicial**: http://localhost/cipa/
- **Login**: http://localhost/cipa/views/Login.php
- **Lista de usuários**: http://localhost/cipa/views/ListarTabela.php
- **Eleições**: http://localhost/cipa/views/Eleicoes.php
- **Cronograma**: http://localhost/cipa/views/Cronograma.php

## Estrutura de URLs

- Login: `http://localhost/cipa/views/Login.php`
- Dashboard: `http://localhost/cipa/views/Dashboard.php`
- Votar: `http://localhost/cipa/views/Votar.php?id={id_eleicao}`
- Candidatar-se: `http://localhost/cipa/views/Candidatar.php?id={id_eleicao}`
- Cronograma: `http://localhost/cipa/views/Cronograma.php?id={id_eleicao}`
- Documentos (ADM): `http://localhost/cipa/views/Documentos.php`
- Ata de Resultados: `http://localhost/cipa/views/AtaResultados.php?id={id_eleicao}`

## Criar Diretórios Necessários

Crie os seguintes diretórios dentro de `cipa`:

```
cipa/
  ├── uploads/
  │   ├── candidatos/
  │   └── documentos/
```

## Primeiro Acesso

1. Acesse o sistema de login
2. Para criar um usuário administrador, você precisará:
   - Acessar diretamente o banco de dados e inserir um usuário manualmente
   - OU criar uma página temporária de cadastro de admin
   - OU usar o phpMyAdmin para inserir um usuário diretamente na tabela `usuario`

## Nota sobre Permissões

Certifique-se de que a pasta `uploads` tenha permissões de escrita para que o sistema possa fazer upload de fotos e documentos.

## Troubleshooting

- **Erro de conexão com banco**: Verifique se o MySQL está rodando e se as credenciais estão corretas
- **Página em branco**: Verifique os logs de erro do PHP (em `C:\xampp\php\logs\php_error_log`)
- **Upload não funciona**: Verifique permissões da pasta `uploads`

