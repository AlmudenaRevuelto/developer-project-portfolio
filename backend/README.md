# Backend

PHP backend for the Client & Project Manager application.

## Structure
- `config/` – Configuration files (database connection, environment)
- `src/` – Application source code
- `public/` – Entry point for HTTP requests (future API)

## Architecture
The backend follows a simple layered architecture:
- Models represent domain entities
- Repositories handle database access
- Services contain business logic
- Controllers manage request handling (to be implemented)
