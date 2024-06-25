# REST API for Subscriber Management
Overview
This project provides a simple REST API for managing subscribers. It's built on a mini-framework designed using the MVC (Model-View-Controller) pattern. The API supports operations such as viewing and creating subscribers.

## Technologies
* PHP 8.3
* MySQL 8
* Redis
* Docker
## Getting Started
### Prerequisites
* Docker and Docker Compose installed on your machine.
* Basic knowledge of Docker operations.
### Running the Project Locally
1. Clone the Repository
* unzip the project
```
cd project
```
2. Start Docker Containers
* Ensure no services are running on the ports we are going to use (if needed, change the ports as described below).
```
docker-compose up -d --build
```
* By default, the web server runs on port 80. If you have a conflict (e.g., another Nginx server on port 80), modify the docker-compose.yml file to map the web server to another port:
```
services:
web:
ports:
- "8080:80"  # Change 8080 to any available port
```
3. Access the API
* Open your web browser and go to http://localhost:8080 or the port you specified.
### Running PHPUnit Tests
To run the automated tests for this application, execute the following command from the root of your project:
```
docker-compose exec web vendor/bin/phpunit
```
This command runs PHPUnit tests that are predefined in your phpunit.xml configuration file.

### Features
View Subscribers: Get a list of all subscribers.
Create Subscriber: Add a new subscriber to the system.

### Architecture
The application follows the MVC pattern, structured as follows:

Model: Manages data and business logic.
View: Handles data presentation to the user (not utilized in this API).
Controller: Manages user interactions, works with models, and renders views.
