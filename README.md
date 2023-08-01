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

- Docker (required to run the project)

### Steps

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/yourusername/referhub.git
   ```

2. **Navigate to the Project Directory:**
   ```bash
   cd referhub
   ```

3. **Configure Environment Variables:** Copy the `.env` file to the `.env.local` file with your database and other configurations.

4. **Build the Project:**
   ```bash
   make build
   ```

5. **Run the containers :**
   ```bash
   make up
   ```

6. **Create the db:**
   ```bash
   make db-create
   ```

7. **Run db migrations:**
   ```bash
   make db-migrate
   ```

## Usage

Refer to the [API documentation](doc/API%20Specifications.md) for details on interacting with ReferHub via REST API.

## Contributing

We welcome contributions!

## Support

For support, please open an issue on GitHub or contact the maintainers.

## License

ReferHub is released under the [GNU License](LICENSE.md).

## Acknowledgments

Special thanks to the community and everyone who has contributed to this project.
