# Resend with Symfony

This example shows how to use Resend with [Symfony](https://symfony.com).

> [!NOTE]  
> This application uses Symfony 7.1 RC1. It will be updated to the stable version once it's released.

## Prerequisites

To get the most out of this guide, you’ll need to:

* [Create an API key](https://resend.com/api-keys)
* [Verify your domain](https://resend.com/domains)

## Instructions

1. Go to `./application` directory

2. Create a `.env.local` file and paste `MAILER_DSN=resend+api://API_KEY@default` then replace `API_KEY` with your API key.

3. Install dependencies:

  ```sh
composer install
  ```

4. Start the server:

  ```sh
symfony serve -d
  ```

5. Open your browser and go to `https://127.0.0.1:8000/`

6. Click on the button to send an email.

## Instructions for webhook

1. Expose your local server to the internet using [expose.dev](https://expose.dev).

2. Register your webhook URL (`{EXPOSE_URL}/webhook/mailer_resend`) in [Resend settings](https://resend.com/webhooks). Check the wanted events.

3. Paste `MAILER_RESEND_SECRET=SECRET_KEY` on `.env.local` and replace `SECRET_KEY` with the webhook signing secret you get after registering the webhook in Resend.

4. Click on the button to send an email.

5. Go to `https://127.0.0.1:8000/email-statuses` to see received events (stored in `var/emails.json`)

## License

MIT License
