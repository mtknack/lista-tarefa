# 📝 Lista de Tarefas

Aplicação web simples de gerenciamento de tarefas construída com [Laravel](https://laravel.com/), utilizando **SQLite** como banco de dados e **Railway** para deploy. Inclui funcionalidades de **CRUD** e reordenação de tarefas via **drag-and-drop**.

🔗 [Acesse a aplicação online](https://lista-tarefa-production.up.railway.app/)  
📁 [Repositório no GitHub](https://github.com/mtknack/lista-tarefa)

---

## 🚀 Funcionalidades

- ✅ Criar, editar e excluir tarefas
- ✅ Marcar tarefas como concluídas
- ✅ Ordenar tarefas com drag-and-drop
- ✅ Persistência da ordem no banco de dados
- ✅ Validação de dados no backend
- ✅ Deploy fácil com Docker + Railway

---

## ⚙️ Requisitos

- Docker
- Composer

---

## 🛠️ Instalação local

## bash
# Clone o repositório
git clone https://github.com/mtknack/lista-tarefa.git
cd lista-tarefa

# Crie o banco de dados
mkdir -p database && touch database/database.sqlite

# Rode com Docker
docker build -t lista-tarefas .
docker run -p 9000:9000 lista-tarefas

# Acesse http://localhost:9000

---

# 📦 Estrutura do banco
O projeto utiliza SQLite. O banco fica localizado em:

- /database/database.sqlite

Certifique-se de que o arquivo existe e tenha permissão de escrita. As migrations são executadas automaticamente no build.

---

# 🐳 Deploy no Railway
1 - Crie um projeto no Railway.
2 - Conecte seu repositório GitHub.
3 - Use o Dockerfile já incluso neste projeto.
4 - Configure as variáveis de ambiente:
    4.1 - DB_CONNECTION=sqlite
    4.2 - DB_DATABASE=/app/database/database.sqlite
    4.3 - SESSION_DRIVER=database
    
---

# 🧰 Tecnologias utilizadas

- Laravel 11
- PHP 8.2
- SQLite
- JavaScript (SortableJS)
- Docker
- Railway

# 📂 Organização do projeto
- app/Models/Tarefa.php – Model principal
- app/Http/Controllers/TarefaController.php – Lógica de CRUD e ordenação
- resources/views/ – Views com layout básico + sortable
- routes/web.php – Rotas do sistema
- Dockerfile – Build para container Laravel com SQLite

# 📝 Licença
Este projeto está sob a licença MIT.
