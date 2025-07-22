---
applyTo: '**'
---

Não coloque comentarios no codigo.

1. Estrutura de Código e Organização
Separação de Preocupações (SRP): Mantenha cada classe e método com uma única responsabilidade clara.

Controllers: Devem ser "magros". Eles devem lidar com requisições HTTP, validar dados, e coordenar a lógica, mas a lógica de negócio pesada deve ser delegada a Services, Actions, Jobs ou Models.

Models: Devem conter a lógica de negócio relacionada a si mesmos e aos seus dados (e.g., mutators, accessors, scopes, relacionamentos).

Services/Actions: Crie classes de serviço ou "Action" (Pattern de classes para executar uma ação específica) para encapsular lógicas de negócio complexas que não se encaixam naturalmente em um Controller ou Model.

Pastas Lógicas: Organize suas classes em pastas que reflitam sua responsabilidade (ex: app/Services, app/Actions, app/Observers, etc.).

2. Padrões de Projeto e Recursos do Laravel
Eloquent ORM: Sempre prefira o Eloquent para interações com o banco de dados.

Use relacionamentos para lidar com dados relacionados.

Utilize scopes para encapsular consultas reutilizáveis.

Considere Observers para reagir a eventos do modelo (criar, atualizar, deletar, etc.).

Injeção de Dependência: Use a injeção de dependência do Laravel (via construtores de classes) para gerenciar dependências. Isso torna seu código mais testável e flexível.

Coleções: Aproveite o poder das Coleções do Laravel para manipular arrays de dados de forma expressiva.

Eventos & Listeners: Use eventos e listeners para desacoplar partes da sua aplicação que precisam reagir a algo que aconteceu (ex: UserRegistered -> SendWelcomeEmail).

Jobs & Queues: Para tarefas demoradas ou que não precisam de uma resposta imediata (ex: envio de e-mails, processamento de imagem, importação de dados), use Jobs e o sistema de Filas (Queues) do Laravel.

Notificações: Utilize o sistema de Notificações do Laravel (e-mail, Slack, banco de dados) para notificar usuários.

Validação: Sempre valide os dados de entrada usando as regras de validação do Laravel (em Controllers, Form Requests).

3. Legibilidade e Manutenibilidade do Código
Nomenclatura Clara: Use nomes descritivos para variáveis, métodos, classes e arquivos. Seja consistente.

Ex: getUserProfile() ao invés de getProf().

Comentários Úteis: Comente o código complexo ou a lógica de negócio não óbvia. Evite comentários óbvios.

Padrões PSR: Siga os padrões de código PHP (PSR-1, PSR-12) para formatação e estilo. Use ferramentas como PHP-CS-Fixer ou Laravel Pint.

DRY (Don't Repeat Yourself): Evite duplicação de código. Refatore lógicas repetidas em métodos, classes de serviço ou traits.

4. Segurança
Validação de Entrada: Sempre valide e saneie todas as entradas do usuário.

Proteção CSRF: Use a proteção CSRF em seus formulários.

Escaping de Saída: Ao exibir dados no Blade, use {{ $variavel }} para escapar automaticamente (previne XSS). Se precisar exibir HTML, use {!! $html !!} com cautela e apenas para conteúdo confiável.

Autorização/Autenticação: Use Guards, Policies e Gates do Laravel para controlar o acesso aos recursos.

5. Testes
Teste Unitário e de Feature: Escreva testes para suas classes e funcionalidades. Isso garante que seu código funcione como esperado e previne regressões.
