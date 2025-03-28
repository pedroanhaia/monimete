```markdown
# Projeto de Monitoramento Meteorológico - MoniMete 🌦️

## Descrição do Projeto 📖

O **Projeto MoniMete** tem como objetivo criar uma solução robusta para o monitoramento meteorológico,
permitindo a coleta de dados via satélite e integração com APIs confiáveis.

Essa solução foi idealizada para fornecer dados climáticos históricos e em tempo real,
atendendo às necessidades de análise e previsão de produtores rurais e equipes de pesquisa.

O sistema abrange desde a recepção de dados via satélite utilizando antena APT
configurada em Raspberry Pi, até a visualização interativa de dashboards com gráficos e mapas.

---

## Recursos Principais ✨

- **Recepção de dados via satélite** utilizando antenas APT.
- **Integração com APIs meteorológicas confiáveis**, como o CPTEC.
- **Visualização histórica e em tempo real** de dados meteorológicos em dashboards.
- **Armazenamento e organização de dados históricos**.
- **Integração com mapas interativos** para análise por regiões.
- Código modular e escalável para facilitar futuras integrações.

---

## Colaboração no Projeto 🤝

Este é um **projeto colaborativo** e está aberto para contribuições! 🎉

### Como Contribuir:
1. Faça um **fork** deste repositório.
2. Crie uma nova branch para suas alterações:
   ```bash
   git checkout -b minha-alteracao
   ```
3. Faça suas alterações no código, garantindo que ele siga as boas práticas.
4. Teste suas alterações.
5. Envie suas alterações para o repositório remoto:
   ```bash
   git push origin minha-alteracao
   ```
6. Abra um **Pull Request (PR)** detalhando suas mudanças.

### Processo de Revisão:
- Todas as alterações enviadas via Pull Request serão revisadas antes de serem integradas à versão final do projeto.
- As revisões garantirão a **compatibilidade com o sistema existente** e a **qualidade do código**.

---

## Políticas do Projeto 🛠️

- **Alterações são bem-vindas!** Desde que estejam alinhadas aos objetivos do projeto.
- O código pode ser alterado para melhorar a **integração com sistemas existentes**.
- Toda contribuição será atribuída ao colaborador em nossos logs e agradecimentos.

---

## Pré-requisitos e Instalação ⚙️

### Ferramentas Necessárias:
- **XAMPP**: Para configurar o ambiente local.
- **Composer**: Para gerenciar dependências do projeto.
- **Raspberry Pi 4** ou superior: Para configuração da antena.
- **Antena APT**: Para recepção de dados via satélite.

### Instalação:
1. Clone este repositório:
   ```bash
   git clone https://github.com/pedroanhaia/monimete.git
   ```
2. Configure o ambiente de desenvolvimento:
   - Instale o [XAMPP](https://www.apachefriends.org/pt_br/index.html) e configure o servidor Apache.
   - Instale o [Composer](https://getcomposer.org/) e configure as dependências do projeto.
3. Siga os manuais para configurar a antena e a integração com a Raspberry Pi.

---

## Tecnologias Utilizadas 🛠️

- **PHP** (Framework CakePHP) para o desenvolvimento do back-end.
- **JavaScript** com AJAX para comunicação e interação com a interface.
- **APIs meteorológicas** para coleta de dados externos.
- **MySQL** para armazenamento de dados históricos.
- **LucidChart** para modelagem de banco de dados.
- **Raspberry Pi** e antenas para recepção de dados via satélite.

---

## Roadmap do Projeto 🚀

| Data         | Etapa                                       | Status        |
|--------------|---------------------------------------------|---------------|
| 24/10/2024   | Levantamento de requisitos e arquitetura    | ✔️ Concluído  |
| 31/10/2024   | Modelagem do banco de dados                | ✔️ Concluído  |
| 07/11/2024   | Configuração do ambiente de desenvolvimento | ✔️ Concluído  |
| 14/11/2024   | Criação de back-office e estrutura inicial  | ✔️ Concluído  |
| 21/11/2024   | Desenvolvimento da integração com APIs      | ⏳ Em Progresso |
| 28/11/2024   | Configuração e testes da antena             | ⏳ Em Progresso |
| 05/12/2024   | Implementação de dashboards                | 🚧 Pendente   |

---

## Contato 📬

Se você tiver dúvidas ou sugestões sobre o projeto, entre em contato:

- E-mail: pasantos1@ucs.br
- GitHub: pedroanhaia (https://github.com/pedroanhaia)

Contribua para tornar este projeto ainda mais incrível! 🚀
