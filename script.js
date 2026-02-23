// ==========================================
// MODAL CUSTOMIZADO (substitui alert/confirm)
// ==========================================

const Modal = {
    overlay: null,
    
    init() {
        this.overlay = document.getElementById('modalCustom');
    },
    
    show({ type = 'info', title, message, buttons = [], onClose = null }) {
        if (!this.overlay) this.init();
        
        const iconEl = document.getElementById('modalIcon');
        const titleEl = document.getElementById('modalTitle');
        const messageEl = document.getElementById('modalMessage');
        const buttonsEl = document.getElementById('modalButtons');
        
        // Definir ícone baseado no tipo (usando imagens)
        const icons = {
            warning: { class: 'icon-warning', img: 'img/warning.png' },
            error: { class: 'icon-error', img: 'img/error.png' },
            success: { class: 'icon-success', img: 'img/success.png' },
            question: { class: 'icon-question', img: 'img/question.png' },
            info: { class: 'icon-info', img: 'img/info.png' }
        };
        
        const iconConfig = icons[type] || icons.info;
        iconEl.className = 'modal-icon ' + iconConfig.class;
        iconEl.innerHTML = '<img src="' + iconConfig.img + '" alt="' + type + '">';
        
        titleEl.textContent = title;
        messageEl.innerHTML = message;
        
        // Limpar e criar botões
        buttonsEl.innerHTML = '';
        buttons.forEach(btn => {
            const button = document.createElement('button');
            button.className = 'modal-btn ' + (btn.class || 'modal-btn-primary');
            button.textContent = btn.text;
            button.addEventListener('click', () => {
                this.hide();
                if (btn.onClick) btn.onClick();
            });
            buttonsEl.appendChild(button);
        });
        
        // Mostrar modal
        this.overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Fechar ao clicar fora
        this.overlay.onclick = (e) => {
            if (e.target === this.overlay) {
                this.hide();
                if (onClose) onClose();
            }
        };
        
        // Fechar com ESC
        document.addEventListener('keydown', this.handleEsc);
    },
    
    hide() {
        if (this.overlay) {
            this.overlay.style.display = 'none';
            document.body.style.overflow = '';
        }
        document.removeEventListener('keydown', this.handleEsc);
    },
    
    handleEsc(e) {
        if (e.key === 'Escape') Modal.hide();
    },
    
    // Atalhos úteis
    alert(title, message, onOk = null) {
        this.show({
            type: 'warning',
            title,
            message,
            buttons: [{ text: 'OK', onClick: onOk }]
        });
    },
    
    error(title, message, onOk = null) {
        this.show({
            type: 'error',
            title,
            message,
            buttons: [{ text: 'Entendi', onClick: onOk }]
        });
    },
    
    success(title, message, onOk = null) {
        this.show({
            type: 'success',
            title,
            message,
            buttons: [{ text: 'OK', onClick: onOk }]
        });
    },
    
    confirm(title, message, onConfirm, onCancel = null) {
        this.show({
            type: 'question',
            title,
            message,
            buttons: [
                { text: 'Sim', class: 'modal-btn-primary', onClick: onConfirm },
                { text: 'Não', class: 'modal-btn-secondary', onClick: onCancel }
            ]
        });
    },
    
    whatsappConfirm(title, message, whatsappUrl) {
        this.show({
            type: 'question',
            title,
            message,
            buttons: [
                { 
                    text: 'Abrir WhatsApp', 
                    class: 'modal-btn-whatsapp', 
                    onClick: () => window.open(whatsappUrl, '_blank') 
                },
                { text: 'Agora não', class: 'modal-btn-secondary' }
            ]
        });
    }
};

// ==========================================
// MENU MOBILE
// ==========================================

const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
const navMenu = document.querySelector('.nav-menu');

if (mobileMenuBtn && navMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenuBtn.classList.toggle('active');
        navMenu.classList.toggle('active');
    });

    // Fechar menu ao clicar em um link
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenuBtn.classList.remove('active');
            navMenu.classList.remove('active');
        });
    });
    
    // Fechar menu ao clicar fora
    document.addEventListener('click', (e) => {
        if (!mobileMenuBtn.contains(e.target) && !navMenu.contains(e.target)) {
            mobileMenuBtn.classList.remove('active');
            navMenu.classList.remove('active');
        }
    });
}

// ==========================================
// SCROLL SUAVE E NAVEGAÇÃO ATIVA
// ==========================================

// Adiciona classe ativa no link do menu conforme scroll
const sections = document.querySelectorAll('section[id]');
const navLinksAll = document.querySelectorAll('.nav-menu a[href^="#"]');

function activateMenuLink() {
    let scrollY = window.pageYOffset;

    sections.forEach(current => {
        const sectionHeight = current.offsetHeight;
        const sectionTop = current.offsetTop - 150;
        const sectionId = current.getAttribute('id');

        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            navLinksAll.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${sectionId}`) {
                    link.classList.add('active');
                }
            });
        }
    });
}

window.addEventListener('scroll', activateMenuLink);

// ==========================================
// HEADER SCROLL EFFECT
// ==========================================

const header = document.querySelector('.header');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;

    if (currentScroll > 100) {
        header.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
    } else {
        header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
    }

    lastScroll = currentScroll;
});

// ==========================================
// FORMULÁRIO DE CONTATO
// ==========================================

let enviandoFormulario = false;
const formContato = document.getElementById('preAgendamento');
const mensagemSucesso = document.getElementById('mensagemSucesso');

if (formContato) {
    formContato.addEventListener('submit', function(e) {
        e.preventDefault();

        // Prevenir envio múltiplo
        if (enviandoFormulario) {
            return;
        }

        // Validar LGPD
        if (!document.getElementById('lgpd').checked) {
            Modal.alert(
                'Termos LGPD',
                'Por favor, aceite os termos da LGPD para continuar com o pré-agendamento.'
            );
            return;
        }

        // Iniciar envio
        enviandoFormulario = true;
        const btnEnviar = formContato.querySelector('button[type="submit"]');
        const textoOriginal = btnEnviar.textContent;
        btnEnviar.textContent = 'Enviando...';
        btnEnviar.disabled = true;

        // Coletar dados do formulário
        const formData = new FormData(formContato);

        // Enviar via AJAX
        fetch('enviar-formulario.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                // Mostrar mensagem de sucesso
                mensagemSucesso.innerHTML = '<p>' + data.mensagem + '</p>';
                mensagemSucesso.style.display = 'block';
                mensagemSucesso.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                // Limpar formulário
                formContato.reset();

                // Oferecer opção de WhatsApp
                if (data.whatsapp) {
                    setTimeout(() => {
                        Modal.whatsappConfirm(
                            'Conectar pelo WhatsApp?',
                            'Deseja enviar uma mensagem diretamente pelo WhatsApp para agilizar o contato?',
                            data.whatsapp
                        );
                    }, 1500);
                }

                // Esconder mensagem após 10 segundos
                setTimeout(() => {
                    mensagemSucesso.style.display = 'none';
                }, 10000);
            } else {
                // Erro no envio
                Modal.show({
                    type: 'error',
                    title: 'Ops! Algo deu errado',
                    message: data.mensagem + '<br><br>Mas não se preocupe, você pode entrar em contato pelo WhatsApp.',
                    buttons: [
                        { 
                            text: '💬 Ir para WhatsApp', 
                            class: 'modal-btn-whatsapp', 
                            onClick: () => { if (data.whatsapp) window.open(data.whatsapp, '_blank'); }
                        },
                        { text: 'Fechar', class: 'modal-btn-secondary' }
                    ]
                });
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            
            // Fallback para WhatsApp
            const nome = document.getElementById('nome').value;
            const mensagemWhatsApp = criarMensagemWhatsApp({
                nome: nome,
                telefone: document.getElementById('telefone').value,
                email: document.getElementById('email').value,
                idade: document.getElementById('idade').value,
                motivoBusca: document.getElementById('motivoBusca').value,
                possuiDiagnostico: document.getElementById('possuiDiagnostico').value,
                disponibilidade: document.getElementById('disponibilidade').value,
            });
            
            const numeroWhatsApp = '5511990186911';
            const urlWhatsApp = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(mensagemWhatsApp)}`;
            
            Modal.show({
                type: 'error',
                title: 'Erro de Conexão',
                message: 'Não foi possível enviar o formulário.<br><br>Clique no botão abaixo para continuar pelo WhatsApp.',
                buttons: [
                    { 
                        text: '💬 Continuar no WhatsApp', 
                        class: 'modal-btn-whatsapp', 
                        onClick: () => window.open(urlWhatsApp, '_blank')
                    }
                ]
            });
        })
        .finally(() => {
            // Restaurar botão
            enviandoFormulario = false;
            btnEnviar.textContent = textoOriginal;
            btnEnviar.disabled = false;
        });
    });
}

function criarMensagemWhatsApp(dados) {
    const diagnosticoTexto = dados.possuiDiagnostico === 'sim' ? 'Sim' : 'Não';

    return `*Pré-Agendamento - Site*

*Nome:* ${dados.nome}
*Telefone:* ${dados.telefone}
*E-mail:* ${dados.email}
*Idade:* ${dados.idade} anos

*Motivo da Busca:*
${dados.motivoBusca || 'Não informado'}

*Possui Diagnóstico Prévio:* ${diagnosticoTexto}
*Disponibilidade:* ${dados.disponibilidade || 'Não informada'}`;
}

function mostrarMensagemSucesso() {
    mensagemSucesso.style.display = 'block';
    mensagemSucesso.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// ==========================================
// MÁSCARA DE TELEFONE
// ==========================================

const inputTelefone = document.getElementById('telefone');

if (inputTelefone) {
    inputTelefone.addEventListener('input', function(e) {
        let valor = e.target.value.replace(/\D/g, '');
        
        if (valor.length <= 11) {
            if (valor.length <= 10) {
                // (00) 0000-0000
                valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
            } else {
                // (00) 00000-0000
                valor = valor.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
            }
        }
        
        e.target.value = valor;
    });
}

// ==========================================
// ANIMAÇÃO DE FADE IN AO SCROLL
// ==========================================

const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observar elementos para animação
const elementsToAnimate = document.querySelectorAll('.card, .step, .sobre-content, .hero-content');
elementsToAnimate.forEach(el => observer.observe(el));

// ==========================================
// SMOOTH SCROLL PARA NAVEGAÇÃO
// ==========================================

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
            const offsetTop = targetElement.offsetTop - 80;
            
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    });
});

// ==========================================
// VALIDAÇÃO DE FORMULÁRIO
// ==========================================

const inputs = document.querySelectorAll('.form-group input, .form-group select, .form-group textarea');

inputs.forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value.trim() === '' && this.hasAttribute('required')) {
            this.style.borderColor = '#8B2635';
        } else {
            this.style.borderColor = '#F5F1ED';
        }
    });

    input.addEventListener('focus', function() {
        this.style.borderColor = '#8B2635';
    });
});

// ==========================================
// AJUSTE AUTOMÁTICO DE ALTURA DO TEXTAREA
// ==========================================

const motivoBuscaTextarea = document.getElementById('motivoBusca');

if (motivoBuscaTextarea) {
    motivoBuscaTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
}

const disponibilidadeTextarea = document.getElementById('disponibilidade');

if (disponibilidadeTextarea) {
    disponibilidadeTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
}

// ==========================================
// LOG DE INICIALIZAÇÃO
// ==========================================

console.log('Site Brenda Lima - Psicóloga Comportamental');
console.log('JavaScript carregado com sucesso');
console.log('WhatsApp: +55 11 99018-6911');
