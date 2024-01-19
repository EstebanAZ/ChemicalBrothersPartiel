# Partiel ChemicalBrothers

L'entreprise ChemicalBrothers souhaite mettre en place une GED pour mettre à disposition de ses clients les Fiches de Données de Sécurité (FDS) au format PDF des produits chimiques qu'elle vend.


## Installation

1. Clone this repository to your local machine.
2. Run the following command to install the dependencies:
    ```shell
    composer install
    ```

## Database Configuration

1. Create a new file `.env.local`.
2. Open the `.env.local` file and update the following line with your MySQL database connection information And Mailer information (you can use [MailTrap](https://mailtrap.io/home) for testing):
    ```
    DATABASE_URL=mysql://your_username:your_password@your_host:your_port/your_database_name?serverVersion=8.0.32&charset=utf8mb4
    MAILER_URL=smtp://{user}:{password}@{server}:{port}
    ```
3. Run the following command to create the MySQL database.
    ```shel
    php bin/console doctrine:database:create
    ``````

## Database Migration

1. Run the following command to create the database tables:
    ```shell
    php bin/console doctrine:migrations:migrate
    ```

## Loading Fixtures

1. Run the following command to load the fixtures into the database:
    ```shell
    php bin/console doctrine:fixtures:load
    ```

## Webpack Build

1. Install the required Node.js packages:
    ```shell
    npm install
    ```

2. Build the assets using Webpack:
    ```shell
    npm run build
    ```

## Starting the Server

1. Start the Symfony development server:
    ```shell
    symfony server:start
    ```

2. Access the application in your browser at `http://localhost:8000`.


## Contributing

Please feel free to contribute to this project by submitting pull requests.

## License

This project is licensed under the [MIT License](LICENSE).
