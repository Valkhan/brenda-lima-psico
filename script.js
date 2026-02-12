// ==========================================
// MENU MOBILE
// ==========================================

const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
const navMenu = document.querySelector('.nav-menu');

if (mobileMenuBtn) {
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
            alert('Por favor, aceite os termos da LGPD para continuar.');
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
                mensagemSucesso.innerHTML = '<p>✓ ' + data.mensagem + '</p>';
                mensagemSucesso.style.display = 'block';
                mensagemSucesso.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                // Limpar formulário
                formContato.reset();

                // Oferecer opção de WhatsApp
                if (data.whatsapp) {
                    setTimeout(() => {
                        if (confirm('Deseja também enviar uma mensagem via WhatsApp?')) {
                            window.open(data.whatsapp, '_blank');
                        }
                    }, 1500);
                }

                // Esconder mensagem após 10 segundos
                setTimeout(() => {
                    mensagemSucesso.style.display = 'none';
                }, 10000);
            } else {
                // Erro no envio
                alert('Erro: ' + data.mensagem + '\n\nVamos redirecioná-lo para o WhatsApp.');
                if (data.whatsapp) {
                    window.open(data.whatsapp, '_blank');
                }
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao enviar formulário. Redirecionando para WhatsApp...');
            
            // Fallback para WhatsApp
            const nome = document.getElementById('nome').value;
            const mensagemWhatsApp = criarMensagemWhatsApp({
                nome: nome,
                telefone: document.getElementById('telefone').value,
                email: document.getElementById('email').value,
                tipoAtendimento: document.getElementById('tipoAtendimento').value,
                idadePaciente: document.getElementById('idadePaciente').value,
// Função removida - agora integrada no handler do formulário           });
            
            const numeroWhatsApp = '5511990186911';
            const urlWhatsApp = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(mensagemWhatsApp)}`;
            window.open(urlWhatsApp, '_blank');
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
    const tipoAtendimentoTexto = dados.tipoAtendimento === 'adulto' ? 'Atendimento Adulto' : 'Atendimento Infantil';
    const diagnosticoTexto = {
        'sim': 'Sim',
        'nao': 'Não',
        'parcial': 'Parcial/Em investigação'
    }[dados.possuiDiagnostico];

    const horarioTexto = {
        'manha': 'Manhã (8h-12h)',
        'tarde': 'Tarde (12h-18h)',
        'noite': 'Noite (18h-21h)'
    }[dados.melhorHorario];

    return `*Pré-Agendamento - Site*

*Nome:* ${dados.nome}
*Telefone:* ${dados.telefone}
*E-mail:* ${dados.email}

*Tipo de Atendimento:* ${tipoAtendimentoTexto}
*Idade do Paciente:* ${dados.idadePaciente} anos

*Motivo Principal:*
${dados.motivoPrincipal}

*Possui Diagnóstico Prévio:* ${diagnosticoTexto}
*Melhor Horário para Contato:* ${horarioTexto}`;
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
// PREVENÇÃO DE ENVIO MÚLTIPLO
// ==========================================

let enviandoFormulario = false;

if (formContato) {
    formContato.addEventListene/ CONTROLE DE IDADE DO PACIENTE
// ==========================================

const tipoAtendimentoSelect = document.getElementById('tipoAtendimento');
const idadePacienteInput = document.getElementById('idadePaciente');

if (tipoAtendimentoSelect && idadePacienteInput) {
    tipoAtendimentoSelect.addEventListener('change', function() {
        if (this.value === 'infantil') {
            idadePacienteInput.setAttribute('max', '17');
            if (parseInt(idadePacienteInput.value) > 17) {
                idadePacienteInput.value = '';
            }
        } else if (this.value === 'adulto') {
            idadePacienteInput.setAttribute('max', '120');
            if (parseInt(idadePacienteInput.value) < 18) {
                idadePacienteInput.value = '';
            }
        }
    });
}

// ==========================================
// AJUSTE AUTOMÁTICO DE ALTURA DO TEXTAREA
// ==========================================

const textarea = document.getElementById('motivoPrincipal');

if (textarea) {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
}

// ==========================================
// LOG DE INICIALIZAÇÃO
// ==========================================

console.log('🧠 Site Brenda Lima - Neuropsicóloga');
console.log('✅ JavaScript carregado com sucesso');
console.log('📱 WhatsApp: +55 11 99018-6911');
