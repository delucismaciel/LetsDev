# FIXI - Plataforma Marketplace de Serviços

![Status do Projeto: Em Desenvolvimento](https://img.shields.io/badge/status-em_desenvolvimento-yellowgreen.svg)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-11.x-orange.svg)
![Licença](https://img.shields.io/badge/Licen%C3%A7a-MIT-green.svg)

Uma plataforma robusta construída em Laravel para conectar clientes (Pessoa Física e Jurídica) aos melhores prestadores de serviço em diversas categorias, como construção, estética, manutenção, TI e mais.

## 🚀 Sobre o Projeto

O FIXI nasceu para solucionar um problema central do mercado brasileiro: a dificuldade em encontrar e contratar profissionais qualificados de forma segura e eficiente. A nossa missão é ser a ponte de confiança entre quem precisa de um serviço e quem sabe executá-lo com maestria.

Este projeto é um marketplace *multi-tenant* (ou multi-provedor) que visa centralizar a demanda e a oferta de serviços, gerenciando desde a busca e agendamento até o pagamento seguro (com *escrow*), garantindo a satisfação de ambas as partes.


## To do List:

### MVP

* [ ] Implementar a busca inteligente de profissionais por categoria e localização (Direto na model).
* [ ] Implementar o sistema de solicitação de orçamento.
* [ ] Implementar o pagamento seguro integrado asaas.
* [ ] Implementar o sistema de avaliação e ranqueamento de profissionais.
* [ ] Implementar a interface de cadastro de profissionais e clientes.
* [ ] Implementar a interface de administração de profissionais e clientes.
* [ ] Implementar a interface de administração de categorias e subcategorias de serviços.
* [ ] Implementar a interface de visualização de pagamentos.
* [ ] Implementar a API para os profissionais e clientes.

### N8N

* [ ] Implementar o agendamento direto no google agenda do profissional e do cliente, através do pedido da plataforma.
* [ ] Implementar o sistema de solicitação de orcamento.
* [ ] Implementar o sistema de avaliação e ranqueamento de profissionais.
* [ ] Implementar o sistema de busca de profissionais por categoria e localização.
* [ ] Implementar o sistema de avisos e notificações.
* [ ] Implementar chat interno no site.


### Categorias de Serviço Planejadas

* **Construção e Reforma:** Pedreiros, pintores, eletricistas, arquitetos, encanadores.
* **Estética e Bem-Estar:** Cabeleireiros, manicures, esteticistas, massoterapeutas.
* **Manutenção:** Reparos residenciais, montagem de móveis, manutenção de eletrodomésticos.
* **Tecnologia e TI:** Suporte técnico, instalação de software, aulas de informática.
* (Entre outros...)

## ✨ Principais Funcionalidades

O sistema é dividido em três grandes pilares:

### 👤 Para Clientes (PF e PJ)

* Busca inteligente de profissionais por categoria e geolocalização.
* Sistema de solicitação de orçamento.
* Agendamento direto pela plataforma.
* Pagamento seguro integrado (via Pagar.me, Stripe, etc.) com sistema de *escrow* (o profissional só recebe após a confirmação do serviço).
* Sistema de avaliação e ranqueamento de profissionais.
* Chat em tempo real para comunicação.

### 🛠️ Para Prestadores de Serviço

* Perfil profissional completo com portfólio, descrição de serviços e áreas de atendimento.
* Dashboard para gerenciamento de pedidos e orçamentos.
* Gestão de agenda e disponibilidade.
* Carteira digital para controle de recebimentos.
* Sistema de notificações sobre novos serviços.

### ⚙️ Painel Administrativo

* Gerenciamento completo de usuários (clientes e prestadores).
* Validação e verificação de documentos de profissionais.
* Gestão de categorias e subcategorias de serviços.
* Moderação de pagamentos e disputas.
* Dashboard com métricas e KPIs da plataforma.

## 💻 Tecnologias Utilizadas

Este projeto está sendo construído com o ecossistema TALL + Laravel, focado em alta produtividade e performance.

* **Backend:** Laravel 12 (PHP 8.2+)
* **Frontend:** Blade
* **Banco de Dados:** MySQL (ou PostgreSQL)
* **Filas e Cache:** Redis
* **Containerização:** Docker
* **Testes de API:** Postman

## 🚀 Como Começar (Ambiente de Desenvolvimento)

Siga os passos abaixo para configurar o ambiente de desenvolvimento localmente.

### Pré-requisitos

* PHP 8.2+
* Composer
* Um servidor de banco de dados (MySQL)

### Instalação 

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/delucismaciel/LetsDev.git](https://github.com/delucismaciel/LetsDev.git)
    cd seu-repositorio
    ```

2.  **Copie o arquivo de ambiente:**
    ```bash
    cp .env.example .env
    ```
    *(Não se esqueça de configurar suas variáveis de ambiente no arquivo `.env`, especialmente `DB_HOST`, `DB_DATABASE`, etc.)*

3.  **Instale as dependências:**
    ```bash
    composer install
    php artisan key:generate
    php artisan optimize
    php artisan migrate --seed
    ```
4.  **Pronto!** A aplicação estará disponível em `http://localhost:8000`.

## 🤝 Como Contribuir

Este projeto é aberto para contribuições. Se você deseja contribuir:

1.  Faça um **Fork** deste repositório.
2.  Crie uma nova **Branch**: `git checkout -b feature/sua-feature-incrivel`
3.  Faça **Commit** das suas mudanças: `git commit -m 'Adiciona funcionalidade X'`
4.  Envie para a sua Branch: `git push origin feature/sua-feature-incrivel`
5.  Abra um **Pull Request**.

## 📄 Licença

Este projeto está licenciado sob a Licença MIT. Veja o arquivo [LICENSE](LICENSE.md) para mais detalhes.

---

**Fixi - Plataforma Marketplace de Serviços** - Conectando quem precisa com quem sabe fazer.
