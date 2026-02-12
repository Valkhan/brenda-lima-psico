# 🚀 Início Rápido - Site Brenda Lima

## Para Visualizar o Site (Sem Email)

1. Abra o arquivo `index.html` no navegador
2. Explore todas as seções
3. O formulário redirecionará para WhatsApp

## Para Usar o Sistema de Email

### Passo 1: Configurar PHP

Edite o arquivo `enviar-formulario.php` nas linhas 14-16:

```php
define('EMAIL_DESTINO', 'brenda@exemplo.com');        // Seu email
define('EMAIL_REMETENTE', 'noreply@seudominio.com');  // Email do servidor
```

### Passo 2: Testar Localmente

#### Windows:
```powershell
php -S localhost:8000
```

#### Mac/Linux:
```bash
php -S localhost:8000
```

Depois acesse: http://localhost:8000

### Passo 3: Testar o Email

1. Abra: http://localhost:8000/teste-email.html
2. Clique em "Enviar Teste"
3. Verifique sua caixa de entrada

⚠️ **Nota:** Em localhost, emails podem não funcionar sem configuração SMTP.

### Passo 4: Colocar Online

#### Opção A: Hospedagem Compartilhada
- Faça upload via FTP
- Funciona em: HostGator, Locaweb, UOL Host, etc.
- Email geralmente funciona automaticamente

#### Opção B: Cloud/VPS
- Configure servidor web (Apache/Nginx)
- Instale PHP 7.4+
- Configure SMTP (opcional mas recomendado)

## 📋 Checklist Pré-Lançamento

- [ ] Emails configurados no PHP
- [ ] Teste enviado e recebido com sucesso
- [ ] Pasta `logs/` tem permissão de escrita
- [ ] SSL/HTTPS configurado
- [ ] Imagens profissionais substituídas
- [ ] Informações de contato verificadas
- [ ] Google Analytics instalado (opcional)
- [ ] Testado em mobile e desktop
- [ ] Formulário testado em navegadores diferentes

## 🆘 Problemas Comuns

### "Email não está chegando"
1. Verifique a pasta de SPAM
2. Confirme que configurou EMAIL_DESTINO
3. Teste com email diferente
4. Veja logs do servidor PHP
5. Use SMTP em vez de mail()

### "Erro 500 ao enviar"
1. Verifique permissões da pasta `logs/`
2. Confirme que PHP está instalado
3. Veja error_log do PHP
4. Teste o PHP isoladamente

### "Formulário não responde"
1. Abra Console do navegador (F12)
2. Veja erros na aba Console
3. Veja requisições na aba Network
4. Confirme que arquivo PHP existe

## 📚 Documentação Completa

- **README.md** - Visão geral do projeto
- **CONFIGURACAO-EMAIL.md** - Guia detalhado de email/SMTP
- **referencia/instrucoes.md** - Briefing original

## 🔗 Links Úteis

- **Testar Email:** teste-email.html
- **Site Principal:** index.html
- **PHP Backend:** enviar-formulario.php
- **Logs:** logs/contatos.log

## 💡 Dicas Pro

1. **Use SMTP** em produção (SendGrid, Mailgun, etc.)
2. **Backup regular** da pasta logs/
3. **Adicione reCAPTCHA** para evitar spam
4. **Monitor emails** enviados e taxa de entrega
5. **Configure alertas** para falhas de envio

---

**Pronto para começar?** 🎉

1. Configure os emails
2. Teste localmente
3. Faça upload para servidor
4. Teste em produção
5. Pronto! ✅

**Precisa de ajuda?** Consulte CONFIGURACAO-EMAIL.md para instruções detalhadas.
