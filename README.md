# 🧠 Site Brenda Lima - Neuropsicóloga

Website institucional profissional desenvolvido para Brenda Lima, Neuropsicóloga e Psicóloga Comportamental.

## 📋 Características

- **Design Sofisticado e Minimalista**: Baseado na identidade visual institucional
- **Paleta de Cores Autêntica**: Vermelho profundo, off-white, verde sálvia e tons terrosos
- **Totalmente Responsivo**: Adaptado para desktop, tablet e mobile
- **Funcionalidades Interativas**: Menu mobile, formulário validado, animações suaves
- **Sistema de Email PHP**: Envio automático de notificações por email
- **Integração WhatsApp**: Botão flutuante e envio direto de pré-agendamento
- **Sistema de Logs**: Backup automático de todos os contatos
- **SEO Otimizado**: Meta tags e estrutura semântica

## 🎨 Identidade Visual

### Cores Principais
- **Vermelho Profundo**: `#8B2635` - Cor institucional principal
- **Off-White**: `#FAF7F5` - Fundo base
- **Verde Sálvia**: `#9BA89B` - Elementos suaves
- **Terracota**: `#C77D6B` - Detalhes
- **Cinza Escuro**: `#3A3A3A` - Texto principal

### Tipografia
- **Títulos**: Satisfy (cursiva acolhedora)
- **Display**: Cormorant Garamond (subtítulos)
- **Corpo**: Inter (textos e navegação)

## 📂 Estrutura do Site

1. **Hero Section**: Apresentação com call-to-action
2. **Quando Procurar**: 6 situações que justificam buscar ajuda
3. **Avaliação Neuropsicológica**: Explicação detalhada do processo
4. **Psicoterapia**: Abordagem ABA e atuação com TEA
5. **Sobre Brenda**: Formação, especialização e filosofia de trabalho
6. **Formulário de Pré-Agendamento**: Estruturado com integração WhatsApp
7. **Footer**: Informações de contato e navegação

## 🚀 Como Usar

### Abrir o Site Localmente

Basta abrir o arquivo `index.html` no navegador de sua preferência:

```bash
# No Windows (PowerShell)
Start-Process index.html

# Ou simplesmente dê duplo clique no arquivo index.html
```

### Estrutura de Arquivos

```
vlk-brenda/
│      # Estrutura HTML principal
├── style.css                 # Estilos CSS completos
├── script.js                 # Funcionalidades JavaScript
├── enviar-formulario.php     # Backend PHP para emails
├── teste-email.html          # Página de teste do sistema
├── .htaccess                 # Configurações Apache
├── .gitignore                # Arquivos ignorados no Git
├── README.md                 # Documentação principal
├── CONFIGURACAO-EMAIL.md     # Guia de configuração de email
│
├── logs/                     # Logs de contatos (auto-criado)
│   └── contatos.log          # Backup de formulários
│
└── referencia/
    └── instrucoes.md      
    └── instrucoes.md   # Instruções originais do projeto
```

## ✨ Funcionalidades Implementadas

### Menu de Navegação
- Menu fixo no topo
- Menu mobile responsivo com animação hamburger
- Scroll suave entre seções
- Destaque visual da seção ativa

### Formulário de Contato
- Validação em tempo real
- Máscara automática para telefone
- **Envio por email com PHP** (notificação formatada em HTML)
- **Sistema de backup em logs** (todos os contatos salvos)
- Integração direta com WhatsApp (opção adicional)
- Mensagem de sucesso/erro
- Conformidade com LGPD
- Proteção contra envios múltiplos

### Interatividade
- Animações de fade-in ao scroll
- Efeitos hover em cards com imagens profissionais
- Cards com fotos do Unsplash (sem emojis)
- Botão flutuante do WhatsApp
- Transições suaves
- Loading states no formulário

### Responsividade
- Layout adaptável para todas as telas
- Menu mobile funcional
- Imagens e textos otimizados
- Formulário responsivo

## 📱 Contato

**WhatsApp**: +55 11 99018-6911  
**Instagram**: @brendalimapsi

## 🔧 Tecnologias Utilizadas

- HTML5 semântico
- CSS3 com Grid e Flexbox
- JavaScript ES6+ (AJAX/Fetch)
- **PHP 7.4+** (backend de formulário)
- Google Fonts (Satisfy, Cormorant Garamond, Inter)
- Unsplash (imagens profissionais)

## 📧 Sistema de Email

### Configuração Necessária

Antes de usar o formulário em produção, **VOCÊ PRECISA CONFIGURAR** o arquivo [enviar-formulario.php](enviar-formulario.php):

```php
define('EMAIL_DESTINO', 'SEU-EMAIL@AQUI.com');      // ⬅️ Altere aqui
define('EMAIL_REMETENTE', 'noreply@seudominio.com'); // ⬅️ Altere aqui
```

### Como Testar

1. Configure os emails no PHP
2. Abra [teste-email.html](teste-email.html) no navegador
3. Preencha o formulário de teste
4. Clique em "Enviar Teste"
5. Verifique sua caixa de entrada (e pasta de SPAM)

### Recursos do Sistema

✅ **Email HTML Responsivo** - Design profissional e elegante  
✅ **Validação Completa** - Dados sanitizados e validados  
✅ **Sistema de Logs** - Backup automático em `logs/contatos.log`  
✅ **Segurança** - Proteção contra XSS e injeção  
✅ **Fallback WhatsApp** - Se email falhar, redireciona  

📋 **Documentação completa:** [CONFIGURACAO-EMAIL.md](CONFIGURACAO-EMAIL.md)

## 📝 Observações

### Para Publicação
Para publicar o site em produção:

1. **Configurar Emails**: Editar `enviar-formulario.php` com emails reais
2. **Testar Sistema**: Usar `teste-email.html` para validar envios
3. **Hospedagem PHP**: Usar servidor com suporte a PHP 7.4+ e função `mail()`
4. **Domínio**: Configurar domínio personalizado (ex: brendalima.com.br)
5. **SSL/HTTPS**: Essencial para segurança e SEO
6. **Imagens**: Substituir placeholders por fotos reais
7. **SEO**: Google Analytics e Search Console
8. **Performance**: Otimizar imagens e minificar CSS/JS
9. **Backup**: Configurar backup automático da pasta `logs/`

### Substituir Imagens
Os elementos `.image-placeholder` devem ser substituídos por imagens reais:
- Foto profissional para a seção "Sobre"
- Imagens relacionadas aos serviços
- Manter a paleta de cores coerente nas fotos

### Customizações Futuras
- Blog/artigos sobre neuropsicologia
- Sistema de agendamento online integrado
- Depoimentos de pacientes
- Vídeos explicativos
- FAQ expandido

## 📄 Licença

Este projeto foi desenvolvido especificamente para Brenda Lima - Neuropsicóloga.  
Todos os direitos reservados © 2026

---

**Desenvolvido com** 🧠 **ciência, cuidado e atenção aos detalhes**