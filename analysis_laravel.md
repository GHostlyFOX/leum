# Laravel Application Analysis: "Детская лига" (Youth Sports Management Platform)

**Project:** Детская лига — Youth Sports Management Platform
**Analysis Date:** March 7, 2026
**Technology Stack:** Laravel 11, PostgreSQL 15, nwidart/laravel-modules
**Status:** Early stage — Infrastructure and models established, business logic incomplete

---

## Executive Summary

The "Детская лига" project is a Laravel-based youth sports management platform with a comprehensive database schema and modular architecture. However, there is a **critical mismatch between the documented design (Database schema + C4 architecture) and the current implementation** in the codebase. The application is in very early stages:

- **Database:** 4 comprehensive migration files are well-designed and follow the technical documentation
- **Models & Entities:** Only ~15 Eloquent models exist; most are simple stubs without relationships
- **Business Logic:** Controllers are mostly empty stubs (returning placeholder views)
- **Module Structure:** 6 modules created (Auth, Club, Team, Trainings, Matches, User) but lack implementation
- **Frontend:** 120+ Livewire components exist but are UI kit templates, NOT related to the project

**Estimated Completion:** The MVP requires ~27 weeks (from plan_mvp.md). Current state is **5-10% complete** at best.

---

## Part 1: Existing vs. Required

### What Exists

#### Database Schema (Well-Designed ✓)
- **3 comprehensive migration files** defining all necessary tables:
  - Schema 1: Users, Clubs, Teams, Profiles, References, Geography
  - Schema 2: Trainings, Venues, Recurring Templates, Attendance
  - Schema 3: Matches, Tournaments, Match Events, Opponents
- All tables include proper foreign keys, indexes, ENUM types, JSONB fields, and comments
- PostgreSQL-specific features used correctly (ENUM types, JSONB, TIMESTAMPTZ)

#### Models (Minimal Implementation ✗)
Only 15 basic Eloquent models exist in `/app/Models/`:
- `User`, `Club`, `Team` (main entities, ~55 lines each)
- Reference models: `RefSex`, `RefRole`, `RefPosition`, `RefRegion`, `RefTypeClub`, `RefTypeSport`, `RefDocType`
- Additional models: `PlayerProfile`, `CoachProfile`, `Document`, `CompositionTeam`, `Role`

**Status:** Models are mostly empty stubs with minimal relationships. Many are only 15-26 lines of code.

#### Modules (Skeleton Only ✗)
6 module directories created with proper structure:
- `Modules/Auth/` — Has `AuthController`, `RegisterController`, but minimal implementation
- `Modules/Club/` — Has `ClubController` with one `saveClub()` method partially implemented
- `Modules/Trainings/` — Empty stub controller
- `Modules/Matches/` — Empty stub controller
- `Modules/Team/` — Empty stub controller
- `Modules/User/` — Empty stub controller

Each module has proper structure (Config, Http/Controllers, Routes, Providers, Resources, Database, Tests) but **Entities folders are empty** (only `.gitkeep` files).

#### Frontend (Irrelevant)
- 127 Livewire components in `/app/Http/Livewire/` are **UI kit templates** (Accordion, Avatar, Badge, Calendar, Chart, etc.)
- These are **NOT related to project logic**
- Likely copied from a template/starter kit
- Should be removed or clearly separated

#### Routes (Minimal)
- Club module: 7 routes defined (list, add, save, team management, staff, refs)
- Auth module: Empty API routes
- Other modules: Minimal/empty routes

#### Configuration
- Laravel Modules package (`nwidart/laravel-modules`) properly configured
- Module model path set to `Entities` directory (line 109 in config/modules.php)
- All standard Laravel configuration files present

---

### What Needs to Be Built (Per Documentation)

Based on `plan_full.md`, `plan_mvp.md`, and C4 architecture (`docs/c4/`):

#### 1. **Auth Module** (MVP Phase 1 — Weeks 1-6)
**Documentation:** `docs/c4/c3_component_api.puml`, Journey diagrams
**Database:** ✓ Ready (`users`, `ref_user_roles` tables)
**Required:**
- [x] Email/password authentication
- [ ] OAuth2 integration (Google, Apple)
- [ ] JWT tokens (Laravel Sanctum)
- [ ] Role-Based Access Control (RBAC) — `ref_user_roles` exists but not implemented
- [ ] User registration flow with email verification
- [ ] Password reset/forgot password
- [ ] API endpoints: login, logout, refresh token, profile

**Current Status:**
- `RegisterController`, `AuthController` exist but are mostly empty
- No OAuth2 implementation visible
- No token generation/validation logic
- No RBAC middleware

---

#### 2. **Club & Team Management Module** (MVP Phase 1 — Weeks 1-6)
**Documentation:** `docs/c4/er_01_users_clubs_teams.puml`, C3 diagrams
**Database:** ✓ Ready (`clubs`, `teams`, `team_members`, references)
**Required:**
- [ ] Club CRUD (create, read, update, delete)
- [ ] Club member management (assign roles, manage staff)
- [ ] Team CRUD (with birth_year, gender, sport_type)
- [ ] Team composition (add/remove players)
- [ ] Logo upload and file management
- [ ] Bulk import players from Excel/CSV
- [ ] API endpoints for all CRUD operations
- [ ] Web UI (admin panel) for club/team management

**Current Status:**
- `ClubController::saveClub()` partially implemented (saves to `club` table, but table should be `clubs`)
- Team controller exists but empty
- No API endpoints
- No file upload service layer
- No import/export functionality

**Issues Found:**
- Model uses `$table = 'club'` but schema defines `clubs` table — **MISMATCH**
- Model has typos in relationships: `'coutry'` instead of `country`, `'sity'` instead of `city`
- References use old table names: `ref_type_sport`, `ref_type_club`, `ref_region` vs. new schema `ref_sport_types`, `ref_club_types`, `countries`, `cities`

---

#### 3. **People Management Module** (MVP Phase 1 — Weeks 1-6)
**Documentation:** `docs/c4/er_01_users_clubs_teams.puml`
**Database:** ✓ Ready (`player_profiles`, `coach_profiles`, `user_parent_player`, `team_members`)
**Required:**
- [ ] Player profile creation and management (position, dominant foot, birth date, jersey number)
- [ ] Coach profile creation and management (specialization, license, achievements)
- [ ] Parent/guardian registration and linking to players
- [ ] Document management (identity documents, medical certificates)
- [ ] User photo upload
- [ ] Team member role assignment (player, coach, manager, admin)
- [ ] API endpoints for all operations

**Current Status:**
- `PlayerProfile`, `CoachProfile`, `Document` models exist but are stubs
- No relationships properly defined
- No service layer for profile management
- No photo upload service

---

#### 4. **Training Management Module** (MVP Phase 2 — Weeks 7-10)
**Documentation:** `docs/c4/er_02_trainings.puml`, `sequence_training.puml`
**Database:** ✓ Ready (`trainings`, `recurring_trainings`, `training_attendance`, `venues`, `ref_training_types`)
**Required:**
- [ ] Create and schedule trainings (one-time and recurring)
- [ ] Recurring training templates (weekly schedule, auto-creation rules)
- [ ] Attendance tracking (RSVP from parents, coach mark as present/absent)
- [ ] Reason for absence (injury, illness, personal, etc.)
- [ ] Auto-extend absence (e.g., injury duration)
- [ ] Training cancellation with notifications
- [ ] Venue management
- [ ] Cron job for auto-generating trainings from templates
- [ ] API endpoints and web UI
- [ ] Calendar view and detail cards

**Current Status:**
- `TrainingsController` is empty scaffold
- No models for `Training`, `RecurringTraining`, `TrainingAttendance`, `Venue`
- No service layer
- No cron job implementation
- No notifications

---

#### 5. **Tournaments & Matches Module** (MVP Phase 3 — Weeks 11-15)
**Documentation:** `docs/c4/er_03_matches_tournaments.puml`, `sequence_match_live.puml`
**Database:** ✓ Ready (`tournaments`, `tournament_teams`, `matches`, `match_coaches`, `match_players`, `match_events`, `ref_tournament_types`, `ref_match_event_types`)
**Required:**
- [ ] Tournament CRUD (group stage, playoff format support)
- [ ] Team enrollment in tournaments
- [ ] Match scheduling and bracket generation
- [ ] Match composition (player selection, lineup)
- [ ] Live match tracking (start/end, events, goals, assists, cards)
- [ ] Real-time event updates (WebSocket or polling)
- [ ] Match result recording and statistics
- [ ] Tournament standings/table calculation
- [ ] API endpoints and live UI
- [ ] Live notifications to parents

**Current Status:**
- `MatchesController` is empty scaffold
- No models for `Tournament`, `Match`, `MatchEvent`, `Opponent`
- No live update mechanism
- No WebSocket integration

---

#### 6. **Statistics Module** (MVP Phase 4 — Weeks 16-17)
**Documentation:** C3 diagrams
**Database:** ✓ Table structure ready (aggregate data from matches, trainings)
**Required:**
- [ ] Player statistics aggregation (goals, assists, attendance)
- [ ] Team statistics
- [ ] Coach statistics
- [ ] Statistics dashboard/views
- [ ] Export to Excel
- [ ] Charts and visualizations

**Current Status:** No implementation

---

#### 7. **Notification Module** (MVP Phases 1-5)
**Documentation:** C3 diagrams, journey maps
**Required:**
- [ ] Email notifications (using Laravel Mail)
- [ ] Push notifications (FCM for Android, APNs for iOS)
- [ ] Telegram bot notifications
- [ ] User notification preferences/settings
- [ ] Notification history/archive
- [ ] Event-driven notification triggers

**Current Status:** No implementation

---

#### 8. **Calendar Integration Module** (MVP Phase 4 — Weeks 16-17)
**Documentation:** C3 diagrams
**Required:**
- [ ] iCal export (Google Calendar, Apple Calendar compatible)
- [ ] Subscribe to calendar feeds
- [ ] Automatic sync with external calendars

**Current Status:** No implementation

---

#### 9. **Import/Export Module** (MVP Phase 1 — Weeks 1-6)
**Documentation:** C3 diagrams
**Required:**
- [ ] Excel/CSV import for players (bulk registration)
- [ ] Data validation and error reporting
- [ ] Excel export for reports/statistics
- [ ] API endpoints for import/export

**Current Status:** No implementation

---

#### 10. **File Storage Module** (MVP Phase 1 — Weeks 1-6)
**Documentation:** `docs/c4/c3_component_api.puml`
**Database:** ✓ Ready (`files` table with S3 path, MIME type, size)
**Required:**
- [ ] S3 file upload service (or local fallback)
- [ ] File type validation (image, PDF, etc.)
- [ ] File size limits
- [ ] Soft delete (mark as deleted in DB, not remove from S3)
- [ ] Access control (only authorized users can download)
- [ ] File cleanup (remove unreferenced files)

**Current Status:**
- `ClubController::saveClub()` has local file upload to `storage/app/public/clubs`, not S3
- No centralized file service
- No reference to `files` table

---

#### 11. **Directory/Reference Data Module** (MVP Phase 0 — Infrastructure)
**Documentation:** C3 diagrams
**Database:** ✓ Ready (all ref_* tables)
**Required:**
- [ ] API endpoints to fetch reference data (sports, positions, roles, clubs, cities)
- [ ] Admin interface to manage reference data
- [ ] Seed/fixture data for common references (sports, positions, relationship types)
- [ ] Cache strategy for frequently accessed references

**Current Status:**
- Reference models exist but without proper relationships
- No API endpoints for reference data
- No seeding or fixtures
- No cache strategy

---

## Part 2: Issues Found

### 1. Critical: Database Schema Mismatch

**Severity:** HIGH — Will cause runtime errors and data inconsistency

**Issue:** Models use old table names and column names that don't match the new PostgreSQL schema (migrations).

| What's Wrong | Old Name | New Name | File |
|---|---|---|---|
| Table name | `club` | `clubs` | `app/Models/Club.php` line 9 |
| Column name | `ref_type_sport` | `sport_type_id` | `Club` model, migration schema 1 |
| Column name | `ref_type_club` | `club_type_id` | `Club` model, migration schema 1 |
| Column name | `country` | `country_id` | `Club` model (relationship typo: `'coutry'`) |
| Column name | `sity` (typo) | `city_id` | `Team` model, typo in column name |
| Table name | `teams` | `teams` | ✓ Correct |
| Table name | — | `team_members` | Schema defines but no model exists |
| Table name | — | `user_parent_player` | Schema defines but no model exists |
| Table name | — | `player_profiles` | Schema defines but no model exists |
| Table name | — | `coach_profiles` | Schema defines but no model exists |

**Example:** Club model references:
```php
public function country(): BelongsTo
{
    return $this->belongsTo(RefRegion::class, 'coutry');  // TYPO: 'coutry'
}
```

Should be:
```php
public function country(): BelongsTo
{
    return $this->belongsTo(Country::class, 'country_id');
}
```

---

### 2. Critical: Missing Models for Key Entities

**Severity:** HIGH — Core functionality impossible without models

**Missing models** that are defined in the schema:

| Entity | Table | Status | Required For |
|--------|-------|--------|---|
| Training | `trainings` | Missing | MVP Phase 2 |
| RecurringTraining | `recurring_trainings` | Missing | MVP Phase 2 |
| TrainingAttendance | `training_attendance` | Missing | MVP Phase 2 |
| Venue | `venues` | Missing | MVP Phase 2, 3 |
| Tournament | `tournaments` | Missing | MVP Phase 3 |
| Match | `matches` | Missing | MVP Phase 3 |
| MatchEvent | `match_events` | Missing | MVP Phase 3 |
| MatchPlayer | `match_players` | Missing | MVP Phase 3 |
| MatchCoach | `match_coaches` | Missing | MVP Phase 3 |
| Opponent | `opponents` | Missing | MVP Phase 3 |
| Country | `countries` | Missing | But `RefRegion` used instead |
| City | `cities` | Missing | But `RefRegion` used instead |
| File | `files` | Missing | MVP Phase 1 (file uploads) |
| TeamMember | `team_members` | Missing | MVP Phase 1 |
| UserParentPlayer | `user_parent_player` | Missing | MVP Phase 1 |
| PlayerProfile | Exists but stub | `player_profiles` | MVP Phase 1 |
| CoachProfile | Exists but stub | `coach_profiles` | MVP Phase 1 |

**Command to generate missing models:**
```bash
php artisan module:make:model Training Trainings
php artisan module:make:model Venue Trainings
php artisan module:make:model Tournament Matches
# ... and so on
```

---

### 3. Major: Inconsistent Reference Table Naming

**Severity:** MEDIUM — Models and schema don't match on reference tables

| Schema (NEW) | Models (OLD) | Issue |
|---|---|---|
| `ref_sport_types` | `RefTypeSport` | Plural vs. implicit singular |
| `ref_club_types` | `RefTypeClub` | Plural vs. implicit singular |
| `ref_user_roles` | `RefRole` | Plural vs. implicit singular |
| `ref_positions` | `RefPosition` | Plural vs. implicit singular |
| `ref_kinship_types` | — | Missing model |
| `ref_dominant_feet` | — | Missing model |
| `ref_document_types` | `RefDocType` | Plural mismatch |
| `ref_training_types` | — | Missing model |
| `ref_tournament_types` | — | Missing model |
| `ref_match_event_types` | — | Missing model |
| `countries` | — | Missing model (instead uses `RefRegion`) |
| `cities` | — | Missing model (instead uses `RefRegion`) |

**Root Cause:** Old models use `RefRegion` for both countries and cities, but the schema defines separate `countries` and `cities` tables.

---

### 4. Major: Module Structure Underutilized

**Severity:** MEDIUM — Proper structure exists but not leveraged

**Issue:** The application uses `nwidart/laravel-modules` for modular architecture, but:
- Module `Entities/` directories are empty (.gitkeep only)
- No models are created within modules
- All models are in shared `/app/Models/`
- No Service/Repository classes in modules
- No module-specific migrations (all in root `database/migrations/`)

**Impact:** Makes code organization unclear and doesn't leverage the modular pattern.

**Recommendation:** Move models to module Entities:
```
Modules/Auth/Entities/User.php
Modules/Club/Entities/Club.php
Modules/Club/Entities/Team.php
Modules/Trainings/Entities/Training.php
Modules/Trainings/Entities/TrainingAttendance.php
Modules/Matches/Entities/Tournament.php
Modules/Matches/Entities/Match.php
```

---

### 5. Major: Empty/Stub Controllers

**Severity:** MEDIUM — Controllers don't implement business logic

**Status by Module:**

| Module | Controller | Status |
|--------|------------|--------|
| Auth | `AuthController` | Empty |
| Auth | `RegisterController` | Empty |
| Club | `ClubController` | 1/3 methods partially implemented (`saveClub()`) |
| Club | `ClubController` | 2 methods return placeholder views |
| Trainings | `TrainingsController` | All empty stubs |
| Matches | `MatchesController` | All empty stubs |
| Team | `TeamController` | Unknown (not checked) |
| User | `UserController` | Unknown (not checked) |

**Example:** Trainings module:
```php
public function index() {
    return view('trainings::index');  // Returns placeholder, no data
}
```

---

### 6. Major: Incomplete ClubController Implementation

**Severity:** MEDIUM — Only one method partially implemented, with issues

**File:** `Modules/Club/Http/Controllers/ClubController.php`

**Issues in `saveClub()` method:**

1. **Wrong table name:** Uses `club` table, schema defines `clubs`
2. **Field mismatches:** Assigns to non-existent columns
   - `$club->ref_type_sport` → should be `sport_type_id`
   - `$club->ref_type_club` → should be `club_type_id`
   - `$club->country` → should be `country_id`
   - `$club->city` → should be `city_id`
3. **File upload inconsistency:** Saves to local `storage/app/public/clubs` instead of `files` table
4. **Validation references wrong tables:**
   - `'ref_type_sport' => 'required|exists:ref_type_sport,id'` → should be `ref_sport_types`
   - `'country' => 'required|exists:ref_regions,id'` → should be `countries`
   - `'city' => 'required|exists:ref_regions,id'` → should be `cities`

```php
// WRONG:
$club->ref_type_sport = $validated['ref_type_sport'];
$club->ref_type_club = $validated['ref_type_club'];

// CORRECT:
$club->sport_type_id = $validated['sport_type_id'];
$club->club_type_id = $validated['club_type_id'];
```

---

### 7. Major: Routes Incomplete

**Severity:** MEDIUM — Routes defined but controllers/actions missing

**Club module routes:**
```php
Route::get('/', 'ClubController@index')->name('home');          // Returns clubs for user
Route::get('list', 'ClubController@list')->name('club-list');  // Duplicate?
Route::get('add', 'ClubController@add')->name('club-add');     // Shows form
Route::post('save', 'ClubController@saveClub');                // Has partial impl.
Route::get('team/list', 'ClubController@teamList');            // Not implemented
Route::get('team/add', 'ClubController@teamAdd');              // Not implemented
Route::get('staff', 'ClubController@index');                   // Calls index (wrong)
Route::get('refs', 'ClubController@index');                    // Calls index (wrong)
```

Issues:
- `teamList()` and `teamAdd()` return placeholder views only
- `staff` and `refs` routes incorrectly map to `ClubController@index`

---

### 8. Moderate: No API Endpoints

**Severity:** MEDIUM — Documentation mentions REST API, but only web routes exist

**Status:**
- `/Modules/Auth/Routes/api.php` — Only one empty route
- `/Modules/Club/Routes/api.php` — Empty
- `/Modules/Trainings/Routes/api.php` — Empty
- `/Modules/Matches/Routes/api.php` — Empty
- `/Modules/Team/Routes/api.php` — Empty
- `/Modules/User/Routes/api.php` — Empty

**Impact:** Mobile app and SPA require API endpoints, but none are implemented.

---

### 9. Moderate: Livewire Components (Irrelevant Clutter)

**Severity:** LOW — Not a blocker, but code smell

**Status:** 127 Livewire components in `/app/Http/Livewire/` — all are UI kit templates:
- `Accordion.php`, `Avatar.php`, `Badge.php`, `Blog.php`, `Calendar2.php`, `Cards.php`
- `ChartChartjs.php`, `ChartEchart.php`, `Chat.php`, `Clients.php`
- ... and 100+ more generic UI components

**Issue:** These are from a template/starter kit and not related to the project. They clutter the codebase.

**Recommendation:** Delete or move to separate UI kit repository.

---

### 10. Moderate: Model Relationships Incomplete

**Severity:** MEDIUM — Existing models lack proper Eloquent relationships

**Example - User model:**
```php
class User extends Authenticatable
{
    public function sex()
    {
        return $this->belongsTo(RefSex::class, 'ref_sex');
    }
}
```

Missing relationships:
- `user.player_profiles` (1-to-many)
- `user.coach_profiles` (1-to-many)
- `user.parent_children` (user_parent_player pivot)
- `user.teams` (through team_members)
- `user.clubs` (through team_members)
- `user.trainings` (as coach)
- `user.matches` (as coach or player)
- `user.roles` (many-to-many)

**Example - Club model:**
```php
class Club extends Model
{
    // Missing relationships:
    // teams(), members(), trainings(), tournaments(), matches(), files()
}
```

---

### 11. Moderate: No Service Layer

**Severity:** MEDIUM — Business logic not separated from controllers

**Missing Service classes:**
- `ClubService` — Club CRUD, member management
- `TeamService` — Team management, composition
- `TrainingService` — Schedule, attendance tracking, notifications
- `MatchService` — Match CRUD, live updates
- `TournamentService` — Tournament management, bracket generation
- `NotificationService` — Send emails, push, Telegram
- `FileService` — Upload, validate, store in S3
- `ImportService` — Parse Excel/CSV, validate, bulk insert
- `StatisticsService` — Aggregate player/team stats

**Impact:** Logic is scattered, difficult to test, harder to maintain.

---

### 12. Moderate: No Testing Infrastructure

**Severity:** MEDIUM — phpunit.xml exists, but no tests

- `/Modules/*/Tests/` directories are mostly empty
- `/tests/` directory (root) not checked, likely minimal
- No test database configuration
- No factories for generating test data

**Required for MVP:** Feature tests for each module's API endpoints and web UI.

---

### 13. Minor: Naming Inconsistencies

**Severity:** LOW — Various naming issues throughout

| Issue | Examples |
|-------|----------|
| Column name typos | `sity` instead of `city` (Team model) |
| Relationship key typos | `'coutry'` instead of `country` (Club model) |
| Table name inconsistency | Old: `ref_type_sport`, New: `ref_sport_types` |
| Model naming | Models don't follow schema table names |
| Route naming | `club-save`, `club-list` vs. RESTful conventions |

---

### 14. Minor: Timestamps Configuration

**Severity:** LOW — Inconsistent timestamp handling

- `User` model: `public $timestamps = false;` (but migration creates `timestampsTz()`)
- `Team` model: `public $timestamps = false;` (but migration creates `timestampsTz()`)
- `Club` model: Uses default timestamps

**Should be consistent:** Either all use timestamps or none.

---

### 15. Minor: No Environment Configuration

**Severity:** LOW — .env.example exists but minimal

- No S3 configuration examples
- No FCM/APNs credentials
- No Telegram bot token
- No OAuth2 secrets

**Recommendation:** Update `.env.example` with all required services.

---

## Part 3: Recommendations for Structure

### Immediate Actions (Critical Path)

#### 1. Fix Database Schema Mismatches (Priority: CRITICAL)
**Timeline:** 1-2 days

Steps:
1. Update all models in `/app/Models/` or move to `Modules/*/Entities/`:
   - Change table names to match migrations
   - Fix column name references (e.g., `sport_type_id` instead of `ref_type_sport`)
   - Add all missing relationships

2. Create missing models:
   ```bash
   php artisan make:model Training
   php artisan make:model TrainingAttendance
   php artisan make:model Venue
   php artisan make:model Tournament
   php artisan make:model Match
   php artisan make:model MatchEvent
   php artisan make:model MatchCoach
   php artisan make:model MatchPlayer
   php artisan make:model Opponent
   php artisan make:model Country
   php artisan make:model City
   php artisan make:model File
   php artisan make:model TeamMember
   php artisan make:model UserParentPlayer
   ```

3. Update `ClubController::saveClub()` to use correct column names.

4. Test all models: `php artisan tinker` → Load models, check relationships.

---

#### 2. Create Service Layer (Priority: HIGH)
**Timeline:** 3-5 days

Structure:
```
Modules/Club/
├── Services/
│   ├── ClubService.php
│   └── TeamService.php
Modules/Trainings/
├── Services/
│   ├── TrainingService.php
│   ├── AttendanceService.php
│   └── VenueService.php
Modules/Matches/
├── Services/
│   ├── MatchService.php
│   ├── TournamentService.php
│   └── MatchEventService.php
Modules/Auth/
├── Services/
│   └── AuthService.php
```

Each service should handle:
- Business logic (not controllers)
- Validation (using custom Rules/Requests)
- Database operations (using models)
- Notifications (triggering notification service)
- Event dispatching

Example:
```php
// ClubService.php
class ClubService
{
    public function createClub(array $data): Club
    {
        // Validate file upload
        // Store file via FileService
        // Create club with file_id
        // Create default team composition if needed
        // Dispatch ClubCreated event
        // Return club
    }
}
```

---

#### 3. Complete Model Relationships (Priority: HIGH)
**Timeline:** 2-3 days

Define all Eloquent relationships:

```php
// User.php
public function playerProfiles() { ... }
public function coachProfiles() { ... }
public function childrenAsParent() { ... }  // user_parent_player
public function parentAsChild() { ... }     // user_parent_player
public function teams() { ... }              // through team_members
public function clubs() { ... }              // through team_members
public function trainings() { ... }          // as coach
public function matches() { ... }            // as coach or player
public function roles() { ... }              // many-to-many

// Club.php
public function teams() { ... }
public function members() { ... }
public function trainings() { ... }
public function tournaments() { ... }
public function venues() { ... }
public function logoFile() { ... }           // belongsTo File

// Team.php
public function members() { ... }
public function trainingSchedules() { ... }
public function tournaments() { ... }
// ... etc
```

---

#### 4. Implement REST API Endpoints (Priority: HIGH)
**Timeline:** 5-7 days per module

Create API routes for each module:

```php
// Modules/Club/Routes/api.php
Route::middleware(['auth:sanctum', 'verified'])->prefix('clubs')->group(function () {
    Route::get('/', [ClubController::class, 'index']);          // List clubs
    Route::post('/', [ClubController::class, 'store']);         // Create club
    Route::get('{club}', [ClubController::class, 'show']);      // Get club details
    Route::put('{club}', [ClubController::class, 'update']);    // Update club
    Route::delete('{club}', [ClubController::class, 'destroy']); // Delete club

    Route::post('{club}/teams', [TeamController::class, 'store']);
    Route::get('{club}/teams', [TeamController::class, 'index']);
    // ... etc
});
```

Use Laravel Resource classes for API responses:
```php
php artisan make:resource ClubResource
php artisan make:resource TeamResource
```

---

#### 5. Implement File Storage Service (Priority: HIGH)
**Timeline:** 2-3 days

Create:
```
Modules/File/
├── Services/
│   └── FileService.php
├── Models/
│   └── File.php
├── Http/Controllers/
│   └── FileController.php
```

`FileService` should:
- Validate file type and size
- Upload to S3 (or local storage)
- Create `files` table record
- Return file ID/path
- Handle soft delete (mark in DB, not S3)

```php
class FileService
{
    public function store(UploadedFile $file, string $disk = 's3'): File
    {
        $validated = $this->validate($file);
        $path = $file->store('', $disk);
        return File::create([
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
        ]);
    }
}
```

---

#### 6. Remove or Separate UI Kit Livewire Components (Priority: MEDIUM)
**Timeline:** 1 day

Options:
- Delete `/app/Http/Livewire/` entirely (if not needed)
- Move to separate `resources/ui-kit/` or `docs/ui-kit/`
- Or clearly document which components are used vs. unused

---

### Medium-Term Actions (Phase-Based Implementation)

#### Phase 1: Auth, Club, Team, People (Weeks 1-6)
**Deliverables:**
- [x] Database schema (already done)
- [ ] Complete all models with relationships
- [ ] Implement AuthService with email + OAuth2
- [ ] Implement ClubService for CRUD
- [ ] Implement TeamService for CRUD
- [ ] Implement PeopleService (players, coaches, parents)
- [ ] REST API endpoints for all modules
- [ ] Web admin panel for club/team/people management
- [ ] Bulk import from Excel/CSV
- [ ] Email notifications (invitations, password reset)

---

#### Phase 2: Trainings (Weeks 7-10)
**Deliverables:**
- [ ] TrainingService (CRUD, recurring templates)
- [ ] AttendanceService (RSVP, mark present/absent)
- [ ] VenueService (CRUD)
- [ ] Cron job for auto-generating trainings
- [ ] REST API endpoints
- [ ] Web UI (calendar, detail cards)
- [ ] Push + Telegram notifications
- [ ] Sync to Google/Apple Calendar (iCal export)

---

#### Phase 3: Matches & Tournaments (Weeks 11-15)
**Deliverables:**
- [ ] TournamentService (CRUD, bracket generation)
- [ ] MatchService (CRUD, composition, live updates)
- [ ] MatchEventService (track goals, assists, cards in real-time)
- [ ] WebSocket support for live match updates
- [ ] REST API endpoints
- [ ] Web UI (tournament brackets, live match screen)
- [ ] Live notifications

---

#### Phase 4: Statistics & Export (Weeks 16-17)
**Deliverables:**
- [ ] StatisticsService (aggregate player/team stats)
- [ ] Statistics dashboard
- [ ] Excel export
- [ ] Charts and visualizations

---

#### Phase 5: Mobile App (Weeks 18-27)
**Deliverables:**
- React Native or Flutter app
- Auth with deep links
- Home, calendar, attendance, live match screens
- Statistics and profile screens
- Push notification handling
- Offline sync (SQLite/Hive)

---

### Structural Best Practices

#### Recommended Project Structure
```
/
├── app/
│   ├── Models/                      (Shared base models or empty)
│   ├── Exceptions/                  (Custom exceptions)
│   ├── Http/
│   │   ├── Requests/                (Shared form requests)
│   │   ├── Resources/               (Shared API resources)
│   │   └── Middleware/              (Shared middleware)
│   ├── Traits/                      (Reusable traits)
│   └── Services/                    (Shared services)
│
├── Modules/
│   ├── Auth/
│   │   ├── Entities/
│   │   │   └── User.php             (or extend from app/Models)
│   │   ├── Services/
│   │   │   └── AuthService.php
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   ├── Routes/
│   │   │   ├── web.php
│   │   │   └── api.php
│   │   ├── Database/
│   │   │   └── Migrations/          (Module-specific migrations)
│   │   └── Tests/
│   │
│   ├── Club/
│   │   ├── Entities/
│   │   │   ├── Club.php
│   │   │   └── Team.php
│   │   ├── Services/
│   │   │   ├── ClubService.php
│   │   │   └── TeamService.php
│   │   ├── Repositories/            (Optional, for complex queries)
│   │   ├── Http/
│   │   ├── Routes/
│   │   ├── Database/
│   │   └── Tests/
│   │
│   ├── Trainings/
│   │   ├── Entities/
│   │   │   ├── Training.php
│   │   │   ├── TrainingAttendance.php
│   │   │   └── Venue.php
│   │   ├── Services/
│   │   ├── Jobs/                    (Cron jobs, queued tasks)
│   │   ├── Http/
│   │   ├── Routes/
│   │   ├── Database/
│   │   └── Tests/
│   │
│   ├── Matches/
│   │   ├── Entities/
│   │   │   ├── Tournament.php
│   │   │   ├── Match.php
│   │   │   ├── MatchEvent.php
│   │   │   └── Opponent.php
│   │   ├── Services/
│   │   ├── Events/                  (Domain events for real-time updates)
│   │   ├── Listeners/               (WebSocket broadcasts)
│   │   ├── Http/
│   │   ├── Routes/
│   │   ├── Database/
│   │   └── Tests/
│   │
│   ├── Statistics/
│   │   ├── Services/
│   │   │   └── StatisticsService.php
│   │   ├── Http/
│   │   └── Routes/
│   │
│   ├── Notification/
│   │   ├── Services/
│   │   │   ├── EmailService.php
│   │   │   ├── PushService.php
│   │   │   └── TelegramService.php
│   │   ├── Http/
│   │   ├── Jobs/
│   │   └── Routes/
│   │
│   └── Directory/                   (Reference data, settings)
│       ├── Entities/
│       ├── Http/
│       └── Routes/
│
├── database/
│   └── migrations/                  (All migrations)
│
├── docs/
│   ├── c4/                          (Architecture diagrams - EXCELLENT!)
│   ├── sql/                         (SQL schemas - EXCELLENT!)
│   └── api/                         (API documentation - TODO)
│
├── routes/
│   └── api.php                      (Aggregate API routes)
│
└── resources/
    └── views/                       (Shared views, layouts)
```

---

#### Key Principles

1. **Models in Modules**: Move models to their respective module's `Entities/` folder:
   ```php
   Modules/Club/Entities/Club.php
   Modules/Trainings/Entities/Training.php
   ```

2. **Services Handle Business Logic**: Controllers should be thin:
   ```php
   // Controller
   public function store(StoreClubRequest $request)
   {
       $club = $this->clubService->createClub($request->validated());
       return new ClubResource($club);
   }
   ```

3. **API Resources for Responses**: Use Laravel Resource classes:
   ```php
   return ClubResource::collection(Club::paginate());
   ```

4. **Repositories for Complex Queries** (Optional but recommended):
   ```php
   class ClubRepository
   {
       public function getByAdmin(User $user)
       {
           return Club::where('admin_id', $user->id)->get();
       }
   }
   ```

5. **Events for Domain Logic**:
   ```php
   event(new ClubCreated($club));
   ```

6. **Jobs for Long-Running Tasks**:
   ```php
   dispatch(new ImportPlayersFromExcel($file));
   ```

7. **Comprehensive Tests**:
   ```php
   Feature/ClubTest.php
   Feature/TrainingTest.php
   Unit/Services/ClubServiceTest.php
   ```

---

## Part 4: Summary of Required Work

### Metrics

| Category | Current | Required | % Complete |
|----------|---------|----------|---|
| **Models** | 15 stubs | 30+ full | ~20% |
| **Services** | 0 | 10+ | 0% |
| **Controllers** | 7 (mostly empty) | 20+ (full CRUD) | ~10% |
| **API Endpoints** | 1 (empty) | 100+ | 1% |
| **Tests** | 0 | 100+ | 0% |
| **Database** | 4 migrations ✓ | 4 migrations ✓ | 100% |
| **Documentation** | C4, ER, journeys ✓ | — | ✓ Complete |

**Estimated MVP Completion:** 27 weeks (from plan_mvp.md)
**Current State:** ~5-10% complete
**Critical Path Items:** Models → Services → Controllers → API → Tests

---

### Top 5 Priorities (Next 2 Weeks)

1. **Fix all model-to-schema mismatches** (2-3 days)
   - Update table/column names
   - Add missing models
   - Define relationships

2. **Create service layer for Club & Team** (3-4 days)
   - ClubService, TeamService
   - Implement createClub(), createTeam(), addMember()
   - Add validation and error handling

3. **Implement REST API for Club & Team** (2-3 days)
   - Routes, Controllers, Resources
   - Test all CRUD endpoints

4. **Implement File Storage Service** (2-3 days)
   - FileService, S3 integration
   - Update Club model to use it

5. **Create comprehensive test suite** (ongoing)
   - Feature tests for each endpoint
   - Unit tests for services

---

## Conclusion

The "Детская лига" project has a **solid foundation** with well-designed database schema and architecture documentation (C4 diagrams are excellent). However, the **implementation is severely incomplete**:

- Database schema is well-designed but models don't match it
- Module structure exists but isn't leveraged
- Controllers are stubs with minimal logic
- No API endpoints despite being designed for REST
- No tests or service layer
- 120+ Livewire components are irrelevant clutter

**To reach MVP in 27 weeks, the team should immediately:**

1. Fix database/model mismatches
2. Create missing models and relationships
3. Implement service layer for business logic
4. Build REST API endpoints
5. Add comprehensive tests
6. Follow the phases in plan_mvp.md closely

**The good news:** The database design and architecture are solid. With focused effort on models, services, and API endpoints, the application can be built to MVP quickly.

---

**Generated:** March 7, 2026
**File:** /sessions/zealous-compassionate-curie/analysis_laravel.md
