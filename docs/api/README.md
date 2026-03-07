# Детская лига — API Documentation

OpenAPI 3.0.3 specification for the Youth Sports League (Детская лига) platform.

## Files Overview

### Main Entry Point
- **openapi.yaml** - Main OpenAPI 3.0.3 specification file that references all sub-files

### Resource-Specific Specifications
1. **auth.yaml** - Authentication endpoints
   - POST /auth/register - User registration
   - POST /auth/login - User login
   - POST /auth/logout - User logout

2. **users.yaml** - User management endpoints
   - GET /me - Current authenticated user
   - GET /users - List users with search and pagination
   - GET /users/{id} - Get user by ID
   - PUT /users/{id} - Update user profile
   - POST /users/{id}/player-profile - Create/update player profile
   - POST /users/{id}/coach-profile - Create/update coach profile

3. **clubs.yaml** - Club management endpoints
   - GET /clubs - List clubs with filtering and pagination
   - POST /clubs - Create new club (multipart with logo)
   - GET /clubs/{id} - Get club by ID
   - PUT /clubs/{id} - Update club
   - DELETE /clubs/{id} - Delete club

4. **teams.yaml** - Team management endpoints
   - GET /clubs/{clubId}/teams - List teams in a club
   - POST /clubs/{clubId}/teams - Create team
   - GET /teams/{id} - Get team by ID
   - PUT /teams/{id} - Update team
   - DELETE /teams/{id} - Delete team
   - POST /teams/{teamId}/members - Add team member

5. **trainings.yaml** - Training management endpoints
   - GET /trainings - List trainings with filters
   - POST /trainings - Create training
   - GET /trainings/{id} - Get training by ID
   - PUT /trainings/{id} - Update training
   - POST /trainings/{id}/cancel - Cancel training
   - PATCH /trainings/{trainingId}/attendance/{playerUserId} - Mark attendance

6. **matches.yaml** - Match management endpoints
   - GET /matches - List matches with filters
   - POST /matches - Create match
   - GET /matches/{id} - Get match by ID
   - PUT /matches/{id} - Update match
   - POST /matches/{id}/start - Start match
   - POST /matches/{id}/end - End match with final score
   - POST /matches/{id}/events - Add match event (goal, card, etc.)
   - PUT /matches/{id}/lineup - Set team lineup

7. **tournaments.yaml** - Tournament management endpoints
   - GET /tournaments - List tournaments with filtering
   - POST /tournaments - Create tournament
   - GET /tournaments/{id} - Get tournament by ID
   - PUT /tournaments/{id} - Update tournament
   - DELETE /tournaments/{id} - Delete tournament
   - POST /tournaments/{id}/teams - Register team in tournament

8. **references.yaml** - Reference data endpoints (public, no auth required)
   - GET /refs/sport-types - List sports
   - GET /refs/club-types - List club types
   - GET /refs/user-roles - List user roles
   - GET /refs/positions - List player positions (with sport type filter)
   - GET /refs/dominant-feet - List dominant foot options
   - GET /refs/kinship-types - List kinship types
   - GET /refs/match-event-types - List match event types
   - GET /refs/countries - List countries
   - GET /refs/cities - List cities (with country filter)

## Authentication

All endpoints except references require Bearer token authentication (Laravel Sanctum).

```
Authorization: Bearer <token>
```

## Server

Base URL: `/api/v1`

## Key Features

- RESTful API design following OpenAPI 3.0.3 standard
- Bearer token authentication via Laravel Sanctum
- Comprehensive request/response schemas with examples
- Pagination support for list endpoints
- Russian language descriptions and field names
- Support for sports management including trainings, matches, and tournaments
- User role management (players, coaches, admins)
- Team and club management with multipart file uploads
- Match event tracking and lineup management
- Tournament registration and team management

## Data Models

### Core Entities
- User (with player and coach profiles)
- Club (sports organization)
- Team (group of players)
- Training (coaching session)
- Match (competitive game)
- Tournament (multi-match competition)

### Supporting Entities
- Team Membership
- Training Attendance
- Match Events
- Match Lineup
- Tournament Teams

## Usage

This OpenAPI specification can be used with:
- Swagger UI for interactive documentation
- Postman for API testing
- Code generators for client/server implementations
- API documentation tools

## Notes

All file references use JSON Pointer syntax with URL encoding for path segments containing special characters.
