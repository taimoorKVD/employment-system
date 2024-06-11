# Employment System

## Overview

This is a Laravel-based application that utilizes Filament for building a complete employment system. The system includes modules for managing countries, states, cities, departments, employees, and users. The Employee management module is designed with relationships to country, state, city, and department modules.

## Features

- **CRUD Operations**: Create, Read, Update, and Delete operations for all modules.
- **Modular Design**: Separate modules for each entity.
- **Relational Data Management**: Employee management with relationships to country, state, city, and department.

## Modules

### Country
- **Create, View, Edit, Delete Countries**

### State
- **Create, View, Edit, Delete States**

### City
- **Create, View, Edit, Delete Cities**

### Department
- **Create, View, Edit, Delete Departments**

### Employee
- **Create, View, Edit, Delete Employees**
- **Relationships**:
    - Each employee is associated with a country, state, city, and department.

### User Management
- **Create, View, Edit, Delete Users**
- **Manage Roles and Permissions**

## Getting Started

### Prerequisites

- PHP >= 8.0
- Laravel = 10.10
- Filament = 3.2
- Composer = 2.6.6
- MySQL

### Installation

1. **Clone the Repository**

    ```sh
    git clone git@github.com:taimoorKVD/employment-system.git
    cd employment-system
    ```

2. **Install Dependencies**

    ```sh
    composer install
    ```

3. **Environment Setup**

   Copy the `.env.example` to `.env` and update the necessary environment variables, particularly the database settings.

    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

4. **Database Migration and Seeding**

   Run the following commands to migrate the database and seed it with initial data.

    ```sh
    php artisan migrate --seed
    ```

5. **Serve the Application**

    ```sh
    php artisan serve
    ```

   Your application should now be accessible at `http://localhost:8000`.

### Filament Admin Panel

Filament provides an admin panel interface to manage all CRUD operations. Access it by navigating to `/admin` in your browser.

## Usage

### Managing Countries, States, and Cities

1. **Countries**: Add or update country information.
2. **States**: Add or update state information associated with a country.
3. **Cities**: Add or update city information associated with a state.

### Managing Departments

1. **Departments**: Create and manage various departments within your organization.

### Managing Employees

1. **Employees**: Manage employee data, including associating them with a country, state, city, and department.

### User Management

1. **Users**: Manage application users, their roles, and permissions.

## Contributing

Contributions are welcome! Please follow the standard GitHub workflow:

1. Fork the repository.
2. Create a new branch.
3. Make your changes.
4. Submit a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
