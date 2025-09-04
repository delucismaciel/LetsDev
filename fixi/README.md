# FIXI - Plataforma Marketplace de Serviços

![Status do Projeto: Em Desenvolvimento](https://img.shields.io/badge/status-em_desenvolvimento-yellowgreen.svg)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-11.x-orange.svg)
![Licença](https://img.shields.io/badge/Licen%C3%A7a-MIT-green.svg)

Uma plataforma robusta construída em Laravel para conectar clientes (Pessoa Física e Jurídica) aos melhores prestadores de serviço em diversas categorias, como construção, estética, manutenção, TI e mais.

## 🚀 Sobre o Projeto

O FIXI nasceu para solucionar um problema central do mercado brasileiro: a dificuldade em encontrar e contratar profissionais qualificados de forma segura e eficiente. A nossa missão é ser a ponte de confiança entre quem precisa de um serviço e quem sabe executá-lo com maestria.

Este projeto é um marketplace *multi-tenant* (ou multi-provedor) que visa centralizar a demanda e a oferta de serviços, gerenciando desde a busca e agendamento até o pagamento seguro (com *escrow*), garantindo a satisfação de ambas as partes.

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

* **Backend:** Laravel 11 (PHP 8.2+)
* **Frontend:** Blade + Livewire 3 (ou Inertia.js + Vue/React)
* **Banco de Dados:** MySQL (ou PostgreSQL)
* **Filas e Cache:** Redis
* **Servidores:** Nginx
* **Containerização:** Docker (via Laravel Sail)
* **Testes:** PHPUnit / Pest

## 🚀 Como Começar (Ambiente de Desenvolvimento)

Siga os passos abaixo para configurar o ambiente de desenvolvimento localmente.

### Pré-requisitos

* PHP 8.2+
* Composer
* Node.js (NPM ou Yarn)
* Um servidor de banco de dados (MySQL/PostgreSQL)
* (Recomendado) Docker Desktop

### Instalação (Usando Laravel Sail - Docker)

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/seu-usuario/seu-repositorio.git](https://github.com/seu-usuario/seu-repositorio.git)
    cd seu-repositorio
    ```

2.  **Copie o arquivo de ambiente:**
    ```bash
    cp .env.example .env
    ```
    *(Não se esqueça de configurar suas variáveis de ambiente no arquivo `.env`, especialmente `DB_HOST`, `DB_DATABASE`, etc.)*

3.  **Instale as dependências do Composer via Sail:**
    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd)":/var/www/html \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs
    ```

4.  **Pronto!** A aplicação estará disponível em `http://localhost`.

## 🤝 Como Contribuir

Este projeto é [insira o status: privado, aberto a contribuições, etc.]. Se você deseja contribuir:

1.  Faça um **Fork** deste repositório.
2.  Crie uma nova **Branch**: `git checkout -b feature/sua-feature-incrivel`
3.  Faça **Commit** das suas mudanças: `git commit -m 'Adiciona funcionalidade X'`
4.  Envie para a sua Branch: `git push origin feature/sua-feature-incrivel`
5.  Abra um **Pull Request**.

## 📄 Licença

Este projeto está licenciado sob a Licença MIT. Veja o arquivo [LICENSE](LICENSE.md) para mais detalhes.

---

**Fixi - Plataforma Marketplace de Serviços** - Conectando quem precisa com quem sabe fazer.
