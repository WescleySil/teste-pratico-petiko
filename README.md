# 🚀 Sistema de Gerenciamento de Tarefas e Usuários

<div align="center">

![Tecnologias](https://skillicons.dev/icons?i=vue,ts,laravel,docker,mysql,tailwind)

</div>

## 📋 Sobre o Projeto

Este é um sistema fullstack que combina um backend robusto em Laravel com um frontend moderno em Vue.js, oferecendo uma experiência completa de gerenciamento de tarefas e usuários.

### 🎯 Principais Funcionalidades

- ✨ Interface moderna e responsiva com Tailwind CSS
- 👥 Gerenciamento completo de usuários
- ✅ Sistema de tarefas com filtros avançados
- 🔍 Busca e filtros em tempo real
- 🔐 Autenticação segura

## 🛠️ Tecnologias Utilizadas

### Backend

- 🏗️ Laravel (PHP)
- 🎲 MySQL
- 🐳 Docker
- 📝 API RESTful

### Frontend

- ⚡ Vue.js 3
- 📘 TypeScript
- 🎨 Tailwind CSS
- 🔄 Vue Router

## 🚀 Como Executar o Projeto

### Pré-requisitos

- 🐳 Docker e Docker Compose
- 📦 Git

### 🔧 Instalação

1. **Clone o repositório**

```bash
git clone https://github.com/WescleySil/teste-pratico-petiko.git
cd teste-pratico-petiko
```

2. **Backend (API)**

```bash
cd api
docker-compose up -d
```

> ⚠️ Aguarde alguns instantes para que o container do Laravel configure tudo automaticamente

3. **Frontend**

```bash
cd ../front
docker-compose up -d
```

### 📍 Acessando o Sistema

- 🔗 Frontend: http://localhost:5173
- 🔗 API: http://localhost:8000

## 🧪 Executando os Testes

O projeto inclui testes automatizados para garantir a qualidade do código. Para executar os testes:

```bash
# Acesse o container do Laravel
docker exec -it laravel bash

# Execute todos os testes
php artisan test

# ou para ver os testes com mais detalhes
php artisan test --testdox
```

Os testes incluem:
- ✅ Testes de Feature para APIs
- ✅ Testes de autenticação
- ✅ Testes de CRUD de usuários e tarefas

> 💡 Os testes são executados em um banco de dados separado para não afetar seus dados de desenvolvimento

## 🌐 Estrutura do Projeto

```
teste-pratico-petiko/
├── api/                # Backend Laravel
│   ├── app/
│   ├── database/
│   ├── tests/         # Testes automatizados
│   └── routes/
└── front/             # Frontend Vue.js
    ├── src/
    ├── components/
    └── views/
```

## 🎮 Usando o Sistema

1. 🚪 Acesse o sistema através do navegador: http://localhost:5173

### 🔑 Credenciais de Acesso

#### Usuário Administrador

- 📧 Email: admin@admin.com
- 🔒 Senha: 12345678
- 👤 Username: admin

#### Usuário Padrão

- 📧 Email: testuser@test.com
- 🔒 Senha: 12345678
- 👤 Username: testuser

2. 📝 Gerencie usuários e tarefas através da interface intuitiva
3. 🔍 Use os filtros para encontrar informações específicas
4. ✨ Aproveite a experiência fluida e responsiva

## 🚀 Implementações Futuras Planejadas

### 📤 Importação em Massa de Tarefas
- 📋 Sistema de importação via CSV com layout padronizado
- 🔄 Job assíncrono para processamento das importações
- 📝 Template CSV disponível para download
- 📊 Relatório de sucesso/falha na importação

### 🔔 Sistema de Notificações
- 📅 Notificações automáticas para tarefas vencidas
- 🎯 Implementação usando Laravel Events e Listeners
- 📨 Endpoint dedicado para gerenciamento de notificações
- 📱 Interface em tempo real para visualização de notificações

> ℹ️ Estas funcionalidades foram planejadas mas não implementadas devido a restrições de tempo do projeto.

---

<div align="center">

⭐️ Feito com 💙 por [Wescley Silva](https://github.com/WescleySil) ⭐️

</div>
