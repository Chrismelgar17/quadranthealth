# Quadrant Health

Quadrant Health is a Laravel-based application designed to manage clinic operations, including appointment bookings, medication refills, and more. This project leverages various repositories and services to provide a seamless experience for both clinic staff and patients.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)

## Installation

To get started with Quadrant Health, follow these steps:

1. **Clone the repository:**
	```sh
	git clone https://github.com/yourusername/quadranthealth.git
	cd quadranthealth
	```

2. **Install dependencies:**
	```sh
	composer install
	npm install
	```

3. **Copy the `.env` file and configure your environment variables:**
	```sh
	cp .env.example .env
	```

4. **Generate an application key:**
	```sh
	php artisan key:generate
	```

5. **Run database migrations:**
	```sh
	php artisan migrate
	```

6. **Start the development server:**
	```sh
	php artisan serve
	```

## Usage

### CRUD Operations for Clinics

The application provides a set of API endpoints to manage clinic records. Below are the available endpoints:

- **Get all clinics:**
	```http
	GET /clinics
	```

- **Get a clinic by ID:**
	```http
	GET /clinics/{id}
	```

- **Create or update a clinic:**
	```http
	POST /clinics
	PATCH /clinics/{id}
	```

- **Delete a clinic:**
	```http
	DELETE /clinics/{id}
	```

### Example Requests

#### Get all clinics
```sh
curl -X GET http://localhost:8000/clinics
```


#### Get clinic by id
```sh
curl -X GET http://localhost:8000/clinics/{id}
```

### Create a clinic
```sh
curl -X POST http://localhost:8000/clinics -d '{
    "medical_practice_phone_number" : "+541136688353",
    "clinic_phone_number":"+18336471773",
    "clinic_name" : "Saint Madison Clinic",
    "clinic_address" : "125 High Beach Boulevard",
    "clinic_hours" : "Monday to Friday, from 10am to 9pm",
    "additional_clinic_goals" : null,
    "first_message" : null
}'"
```
### Update a Clinic
```sh
curl -X PATCH http://localhost:8000/clinics/{id} -d '{
    "medical_practice_phone_number" : "+541136688353",
    "clinic_phone_number":"+18336471773",
    "clinic_name" : "Saint Madison Clinic",
    "clinic_address" : "125 High Beach Boulevard",
    "clinic_hours" : "Monday to Friday, from 10am to 9pm",
    "additional_clinic_goals" : null,
    "first_message" : null
}'
```

#### Delete a clinic
```sh
curl -X DELETE http://localhost:8000/clinics/{id}
```