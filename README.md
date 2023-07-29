# ReferHub

ReferHub is a modular and composable referral software designed to generate and manage referral codes, track events, and handle webhooks. Built with Symfony and following DDD and TDD principles, ReferHub offers a flexible and maintainable solution for developers and businesses.

## Features

- **Referral Code Generation:** Generate referral codes for users identified by email, username, or userId.
- **Event Tracking:** Track generic events like Sign Up, Purchase, and more.
- **Webhook Integration:** Trigger webhooks when tracked events occur.
- **CLI, REST, and Batch API Support:** Flexible API interfaces for various integration needs.
- **Open Source:** Contribute and customize to fit your unique requirements.

## Installation

### Requirements

- PHP 8.2 or higher
- MySQL or PostgreSQL
- Composer
- (Optional) Docker

### Steps

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/yourusername/referhub.git
   ```

2. **Navigate to the Project Directory:**
   ```bash
   cd referhub
   ```

3. **Install Dependencies:**
   ```bash
   composer install
   ```

4. **Configure Environment Variables:** Edit the `.env` file with your database and other configurations.

5. **Run Migrations:**
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

6. **Start the Development Server:**
   ```bash
   symfony server:start
   ```

## Usage

Refer to the API documentation for details on interacting with ReferHub via CLI, REST, or Batch APIs.

## Contributing

We welcome contributions! Please see the `CONTRIBUTING.md` file for guidelines on how to contribute.

## Support

For support, please open an issue on GitHub or contact the maintainers.

## License

ReferHub is released under the [GNU License](LICENSE.md).

## Acknowledgments

Special thanks to the community and everyone who has contributed to this project.
