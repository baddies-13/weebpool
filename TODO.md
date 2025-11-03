# TODO: Arrange Resume Project to Meet Requirements

## 1. Create Database Schema and Sample Data
- [x] Create php/init.sql with tables: users (id PK auto, first_name, last_name, email, phone_number, photo), experiences (id PK, user_id FK, job_title, company_name, start_date, end_date, description), educations (id PK, user_id FK, degree, school_name, start_date, end_date), skills (id PK, user_id FK, skill_name, level).
- [x] Insert at least one user entry with sample data for all sections.

## 2. Update Docker Configuration
- [x] Modify docker-compose.yml to copy and run init.sql on DB startup.

## 3. Implement CRUD in edit.php
- [x] Create php/edit.php with forms for adding/updating/deleting each section (Profile, Experience, Education, Skills).
- [x] Handle POST requests to insert/update/delete in DB.
- [x] Make edit.php responsive and improve its design.

## 4. Update view.php
- [x] Remove extra sections (About Me, Projects).
- [x] Ensure sections: Profile, Experience, Education, Skills.
- [x] Update navigation menu with anchors to each section.
- [x] Confirm email is mailto hyperlink.
- [x] Ensure fade-in animations.
- [x] Adjust responsive CSS for mobile (<600px), tablet (600-1024px), desktop (>1024px).

## 5. Testing and Verification
- [ ] Run docker compose up.
- [ ] Check DB tables and data.
- [ ] Access view.php?user_id=1 and verify display, nav, mailto, animations, responsiveness.
- [ ] Access edit.php?user_id=1 and test CRUD operations.
- [ ] Ensure no console errors or missing resources.
