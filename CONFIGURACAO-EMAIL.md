# 📧 Configuração de Email - Site Brenda Lima

## ⚠️ IMPORTANTE - CONFIGURAR ANTES DE USAR

O arquivo `enviar-formulario.php` precisa ser configurado com os emails corretos antes de funcionar em produção.

### 1️⃣ Editar o arquivo PHP

Abra o arquivo [enviar-formulario.php](enviar-formulario.php) e localize as linhas 14-16:

```php
define('EMAIL_DESTINO', 'contato@brendalima.com.br');     // ⬅️ ALTERE AQUI
define('EMAIL_ASSUNTO', 'Novo Pré-Agendamento - Site');
define('EMAIL_REMETENTE', 'noreply@brendalima.com.br');   // ⬅️ ALTERE AQUI
```

**Substitua pelos emails reais:**

- **EMAIL_DESTINO**: Email onde você receberá as notificações (ex: `brenda@gmail.com`)
- **EMAIL_REMETENTE**: Email do servidor (ex: `noreply@seudominio.com.br`)

### 2️⃣ Configuração do Servidor

#### Opção A: Usando função mail() do PHP (servidor compartilhado)

A função `mail()` do PHP geralmente funciona em hospedagens compartilhadas. Apenas configure os emails acima.

#### Opção B: Usando SMTP (recomendado para produção)

Para maior confiabilidade, use uma biblioteca SMTP como PHPMailer:

```bash
composer require phpmailer/phpmailer
```

Depois modifique o `enviar-formulario.php` para usar SMTP (Gmail, SendGrid, etc).

#### Opção C: Serviços de Email Transacional

Recomendado para produção profissional:

- **SendGrid** (gratuito até 100 emails/dia)
- **Mailgun** (gratuito até 5000 emails/mês)
- **Amazon SES** (muito barato)
- **Postmark** (confiável e rápido)

### 3️⃣ Teste Local

Para testar localmente (sem servidor web):

1. Instale PHP no seu computador
2. Execute: `php -S localhost:8000`
3. Acesse: `http://localhost:8000`

**Nota:** Emails não serão enviados em localhost sem configuração SMTP.

### 4️⃣ Estrutura de Logs

O sistema salva todos os contatos em:
```
logs/contatos.log
```

Cada linha é um JSON com os dados do formulário. Útil para backup e análise.

### 5️⃣ Segurança

✅ **Implementado:**
- Validação de dados (sanitização)
- Proteção contra XSS
- Validação de email e telefone
- Headers de segurança
- Log de requisições
- Rate limiting (recomendado adicionar)

⚠️ **Recomendações Adicionais:**
- Adicione CAPTCHA (Google reCAPTCHA v3)
- Configure SSL/HTTPS no servidor
- Limitar tentativas por IP
- Backup regular dos logs

### 6️⃣ Formato do Email

O email enviado é **HTML responsivo** com:

- ✨ Design elegante e profissional
- 📱 Responsivo (mobile-friendly)
- 🎨 Cores da identidade visual
- 💬 Botão direto para WhatsApp
- 📋 Todos os dados organizados
- ⏰ Data/hora do recebimento

### 7️⃣ Fluxo Completo

1. **Usuário preenche** o formulário no site
2. **JavaScript valida** os dados localmente
3. **AJAX envia** para `enviar-formulario.php`
4. **PHP valida** novamente (segurança)
5. **Email é enviado** formatado em HTML
6. **Log é salvo** no arquivo
7. **Resposta JSON** é enviada ao navegador
8. **Opção de WhatsApp** como complemento

### 8️⃣ Troubleshooting

**Emails não estão chegando?**

1. Verifique se o servidor permite `mail()`
2. Confira a pasta de SPAM
3. Verifique os logs do servidor PHP
4. Teste com um email diferente
5. Use SMTP em vez de mail()

**Erro 500 ao enviar?**

1. Verifique permissões da pasta `logs/`
2. Veja o log de erros do PHP
3. Confirme que o PHP está instalado
4. Teste o PHP isoladamente

**Formulário não enviando?**

1. Abra o Console do navegador (F12)
2. Vá na aba Network
3. Envie o formulário
4. Veja a resposta do servidor

### 9️⃣ Exemplo de Configuração SMTP

Se quiser usar Gmail SMTP, instale PHPMailer e modifique assim:

```php
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'seu-email@gmail.com';
$mail->Password = 'sua-senha-de-app';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('seu-email@gmail.com', 'Brenda Lima');
$mail->addAddress(EMAIL_DESTINO);
$mail->Subject = EMAIL_ASSUNTO;
$mail->msgHTML($corpoHtml);
$mail->send();
```

### 🔟 Próximos Passos

1. ✅ Configure os emails no PHP
2. ✅ Teste em ambiente de desenvolvimento
3. ✅ Adicione reCAPTCHA (opcional)
4. ✅ Configure SMTP para produção
5. ✅ Faça backup dos logs periodicamente
6. ✅ Monitore os emails enviados

---

**Precisa de ajuda?** Entre em contato com seu desenvolvedor ou consulte a documentação do PHP.

**Última atualização:** 12/02/2026
