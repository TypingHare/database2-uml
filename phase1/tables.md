### Tables

* **Student** (<u>sid</u>, type, name, email, gpa, total_credits)
    * The field `type` is enumerated: `UNDERGRAD`, `MASTER,` and `PHD`.
* **Instructor** (<u>id</u>, name)
* **Course** (<u>code</u>, title, credits)
* **Section** (<u>section_id</u>, course_code, timeslot_id, building, room, section_num, semester, year)
    * The `course_code` field serves as a foreign key that references the `code` column in the **Course** table.
    * The `timeslot_id` field serves as a foreign key that references the `timeslot_id` column in the **Timeslot** table.
    * The `building` and `room` fields serve as foreign keys referencing the `building` and `room` columns in the **Classroom** table.
    * Constraint 1: Each combination of `(section_num, semester, year)` must be *unique* to prevent identical sections.
    * Constraint 2: Each combination of `(timeslot_id, building, room, semester, year)` must be *unique* to prevent time conflicts.
    * Constraint 3: The number of combinations of `(timeslot_id, semester, year)` with different `(building, room)` must be *less than or equal to 4*.
* **Timeslot** (<u>timeslot_id</u>, day_of_week, start_time, end_time)
    * Each combination of `(day_of_week, start_time, end_time)` must be *unique* to prevent overlapping timeslots.
* **Classroom** (<u>building</u>, <u>room</u>, capacity)
* **Takes** (<u>sid</u>, <u>section_id</u>, score)
    * The `sid` field serves as a foreign key that references the `sid` column in the **Student** table.
    * The `section_id` field serves as a foreign key that references the `id` column in the **Section** table.
* **Teaches** (<u>instructor_id</u>, <u>section_id</u>)
    * The `instructor_id` field serves as a foreign key that references the `id` column in the **Instructor** table.
    * The `section_id` field serves as a foreign key that references the `id` column in the **Section** table.
* **IsAdvisor** (<u>sid</u>, <u>instructor_id</u>)
    * The `sid` field serves as a foreign key that references the `sid` column in the **Student** table.
    * The `instructor_id` field serves as a foreign key that references the `id` column in the **Instructor** table.
* **IsTA** (<u>sid</u>, <u>section_id</u>)
    * The `sid` field serves as a foreign key that references the `sid` column in the **Student** table.
    * The `section_id` field serves as a foreign key that references the `id` column in the **Section** table.
    * Constraint: the student associated with `sid` must be a PhD student (the `type` field is `PHD`).
* **IsGrader** (<u>sid</u>, <u>section_id</u>)
    * The `sid` field serves as a foreign key that references the `sid` column in the **Student** table.
    * The `section_id` field serves as a foreign key that references the `id` column in the **Section** table.
    * Constraint 1: the student associated with `sid` must be either a master's student (the `type` field is `MASTER`) or an undergraduate student (the `type` field is `UNDERGRAD`).
    * Constraint 2: the student associated with `sid` must get an `A-` in the course. The score can be obtained by retrieving from the **Takes** course with the same `sid` and `section_id`.