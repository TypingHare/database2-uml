# noinspection SpellCheckingInspectionForFile

DROP DATABASE IF EXISTS db2;
CREATE DATABASE db2;

USE DB2;

create table account
(
    email    varchar(50),
    password varchar(20) not null,
    type     varchar(20),
    primary key (email)
);


create table department
(
    dept_name varchar(100),
    location  varchar(100),
    primary key (dept_name)
);

create table instructor
(
    instructor_id   varchar(10),
    instructor_name varchar(50) not null,
    title           varchar(30),
    dept_name       varchar(100),
    email           varchar(50) not null,
    primary key (instructor_id)
);


create table student
(
    student_id varchar(10),
    name       varchar(20) not null,
    email      varchar(50) not null,
    dept_name  varchar(100),
    primary key (student_id),
    foreign key (dept_name) references department (dept_name)
        on delete set null
);

create table PhD
(
    student_id                varchar(10),
    qualifier                 varchar(30),
    proposal_defence_date     date,
    dissertation_defence_date date,
    primary key (student_id),
    foreign key (student_id) references student (student_id)
        on delete cascade
);

create table master
(
    student_id    varchar(10),
    total_credits int,
    primary key (student_id),
    foreign key (student_id) references student (student_id)
        on delete cascade
);

create table undergraduate
(
    student_id     varchar(10),
    total_credits  int,
    class_standing varchar(10)
        check (class_standing in ('Freshman', 'Sophomore', 'Junior', 'Senior')),
    primary key (student_id),
    foreign key (student_id) references student (student_id)
        on delete cascade
);

create table classroom
(
    classroom_id varchar(8),
    building     varchar(15) not null,
    room_number  varchar(7)  not null,
    capacity     numeric(4, 0),
    primary key (classroom_id)
);

create table time_slot
(
    time_slot_id varchar(8),
    day          varchar(10) not null,
    start_time   time        not null,
    end_time     time        not null,
    primary key (time_slot_id)
);

create table course
(
    course_id   varchar(20),
    course_name varchar(50) not null,
    credits     numeric(2, 0) check (credits > 0),
    primary key (course_id)
);

create table section
(
    course_id     varchar(20),
    section_id    varchar(10),
    semester      varchar(6)
        check (semester in ('Fall', 'Winter', 'Spring', 'Summer')),
    year          numeric(4, 0) check (year > 1990 and year < 2100),
    instructor_id varchar(10),
    classroom_id  varchar(8),
    time_slot_id  varchar(8),
    primary key (course_id, section_id, semester, year),
    foreign key (course_id) references course (course_id)
        on delete cascade,
    foreign key (instructor_id) references instructor (instructor_id)
        on delete set null,
    foreign key (time_slot_id) references time_slot (time_slot_id)
        on delete set null
);

create table prereq
(
    course_id varchar(20),
    prereq_id varchar(20) not null,
    primary key (course_id, prereq_id),
    foreign key (course_id) references course (course_id)
        on delete cascade,
    foreign key (prereq_id) references course (course_id)
);

create table advise
(
    instructor_id varchar(8),
    student_id    varchar(10),
    start_date    date not null,
    end_date      date,
    primary key (instructor_id, student_id),
    foreign key (instructor_id) references instructor (instructor_id)
        on delete cascade,
    foreign key (student_id) references PhD (student_id)
        on delete cascade
);

create table TA
(
    student_id varchar(10),
    course_id  varchar(8),
    section_id varchar(10),
    semester   varchar(6),
    year       numeric(4, 0),
    primary key (student_id, course_id, section_id, semester, year),
    foreign key (student_id) references PhD (student_id)
        on delete cascade,
    foreign key (course_id, section_id, semester, year) references
        section (course_id, section_id, semester, year)
        on delete cascade
);

create table masterGrader
(
    student_id varchar(10),
    course_id  varchar(8),
    section_id varchar(10),
    semester   varchar(6),
    year       numeric(4, 0),
    primary key (student_id, course_id, section_id, semester, year),
    foreign key (student_id) references master (student_id)
        on delete cascade,
    foreign key (course_id, section_id, semester, year) references
        section (course_id, section_id, semester, year)
        on delete cascade
);

create table undergraduateGrader
(
    student_id varchar(10),
    course_id  varchar(8),
    section_id varchar(10),
    semester   varchar(6),
    year       numeric(4, 0),
    primary key (student_id, course_id, section_id, semester, year),
    foreign key (student_id) references undergraduate (student_id)
        on delete cascade,
    foreign key (course_id, section_id, semester, year) references
        section (course_id, section_id, semester, year)
        on delete cascade
);

create table take
(
    student_id varchar(10),
    course_id  varchar(8),
    section_id varchar(10),
    semester   varchar(6),
    year       numeric(4, 0),
    grade      varchar(2)
        check (grade in
               ('A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D',
                'D-', 'F')),
    primary key (student_id, course_id, section_id, semester, year),
    foreign key (course_id, section_id, semester, year) references
        section (course_id, section_id, semester, year)
        on delete cascade,
    foreign key (student_id) references student (student_id)
        on delete cascade
);



insert into account (email, password, type)
values ('admin@uml.edu', '123456', 'admin');
insert into account (email, password, type)
values ('dbadams@cs.uml.edu', '123456', 'instructor');
insert into account (email, password, type)
values ('slin@cs.uml.edu', '123456', 'instructor');
insert into account (email, password, type)
values ('Yelena_Rykalova@uml.edu', '123456', 'instructor');
insert into account (email, password, type)
values ('Johannes_Weis@uml.edu', '123456', 'instructor');
insert into account (email, password, type)
values ('Charles_Wilkes@uml.edu', '123456', 'instructor');


insert into course (course_id, course_name, credits)
values ('COMP1010', 'Computing I', 3);
insert into course (course_id, course_name, credits)
values ('COMP1020', 'Computing II', 3);
insert into course (course_id, course_name, credits)
values ('COMP2010', 'Computing III', 3);
insert into course (course_id, course_name, credits)
values ('COMP2040', 'Computing IV', 3);

insert into department (dept_name, location) value ('Miner School of Computer & Information Sciences',
                                                    'Dandeneau Hall, 1 University Avenue, Lowell, MA 01854');

insert into instructor (instructor_id, instructor_name, title, dept_name,
                        email) value ('1', 'David Adams', 'Teaching Professor',
                                      'Miner School of Computer & Information Sciences',
                                      'dbadams@cs.uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name,
                        email) value ('2', 'Sirong Lin',
                                      'Associate Teaching Professor',
                                      'Miner School of Computer & Information Sciences',
                                      'slin@cs.uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name,
                        email) value ('3', 'Yelena Rykalova',
                                      'Associate Teaching Professor',
                                      'Miner School of Computer & Information Sciences',
                                      'Yelena_Rykalova@uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name,
                        email) value ('4', 'Johannes Weis',
                                      'Assistant Teaching Professor',
                                      'Miner School of Computer & Information Sciences',
                                      'Johannes_Weis@uml.edu');
insert into instructor (instructor_id, instructor_name, title, dept_name,
                        email) value ('5', 'Tom Wilkes',
                                      'Assistant Teaching Professor',
                                      'Miner School of Computer & Information Sciences',
                                      'Charles_Wilkes@uml.edu');

insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS1', 'MoWeFr', '11:00:00', '11:50:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS2', 'MoWeFr', '12:00:00', '12:50:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS3', 'MoWeFr', '13:00:00', '13:50:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS4', 'TuTh', '11:00:00', '12:15:00');
insert into time_slot (time_slot_id, day, start_time, end_time) value ('TS5', 'TuTh', '12:30:00', '13:45:00');

insert into section (course_id, section_id, semester, year) value ('COMP1010', 'Section101', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP1010', 'Section102', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP1010', 'Section103', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP1010', 'Section104', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP1020', 'Section101', 'Spring', 2024);
insert into section (course_id, section_id, semester, year) value ('COMP1020', 'Section102', 'Spring', 2024);
insert into section (course_id, section_id, semester, year) value ('COMP2010', 'Section101', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP2010', 'Section102', 'Fall', 2023);
insert into section (course_id, section_id, semester, year) value ('COMP2040', 'Section201', 'Spring', 2024);

-- ***************** The following are added by students. ***************** --

-- BEGIN ADD_INDSTRUCTORS
insert into instructor (instructor_id, instructor_name, title, dept_name,
                        email) value ('6', 'David Hendrickson',
                                      'Ajunct Professor',
                                      'Miner School of Computer & Information Sciences',
                                      'thegoat@uml.edu');
-- END ADD_INSTRUCTOR

-- BEGIN ADD_CLASSROOMS
INSERT INTO classroom (classroom_id, building, room_number, capacity)
VALUES ('CR1', 'Fal', '305', 50),
       ('CR2', 'Ols', '300', 80),
       ('CR3', 'Dan', '207', 30),
       ('CR4', 'Dan', '309', 30),
       ('CR5', 'Bal', '301', 30);
-- END ADD_CLASSROOMS

-- BEGIN ADD_COURSES
INSERT INTO course (course_id, course_name, credits)
VALUES ('COMP2030', 'Assembly Language', 3),
       ('COMP3050', 'Computer Architecture', 3),
       ('MATH1010', 'Calculus I', 3),
       ('MATH1020', 'Calculus II', 3),
       ('MATH3010', 'Discrete Structures I', 3),
       ('MATH3040', 'Discrete Structures II', 3),
       ('SOC1010', 'Ethics', 3),
       ('SOC1020', 'Diversity', 3);
-- END ADD_COURSES

-- BEGIN ADD_SECTIONS
INSERT INTO section (course_id, section_id, semester, year, instructor_id,
                     classroom_id, time_slot_id)
VALUES ('COMP2030', 'Section201', 'Spring', 2025, '3', 'CR1', 'TS4'),
       ('COMP3050', 'Section201', 'Spring', 2025, '4', 'CR2', 'TS5'),
       ('MATH1010', 'Section101', 'Spring', 2025, '5', 'CR3', 'TS1'),
       ('MATH3010', 'Section202', 'Spring', 2025, '1', 'CR5', 'TS1'),
       ('COMP1010', 'Section101', 'Spring', 2025, '6', 'CR3', 'TS3'),
       ('COMP2010', 'Section201', 'Spring', 2025, '2', 'CR4', 'TS2'),
       ('COMP2030', 'Section201', 'Fall', 2025, '3', 'CR1', 'TS4'),
       ('COMP3050', 'Section201', 'Fall', 2025, '4', 'CR2', 'TS5'),
       ('MATH1010', 'Section101', 'Fall', 2025, '5', 'CR3', 'TS1'),
       ('MATH3010', 'Section202', 'Fall', 2025, '1', 'CR5', 'TS1'),
       ('COMP1010', 'Section101', 'Fall', 2025, '6', 'CR3', 'TS3'),
       ('COMP2010', 'Section201', 'Fall', 2025, '2', 'CR4', 'TS2'),
       ('MATH1020', 'Section101', 'Fall', 2024, '2', 'CR4', 'TS1'),
       ('MATH3040', 'Section101', 'Fall', 2024, '3', 'CR5', 'TS2'),
       ('SOC1010', 'Section101', 'Fall', 2025, '1', 'CR5', 'TS2'),
       ('SOC1020', 'Section101', 'Fall', 2025, '5', 'CR3', 'TS2'),
       ('SOC1020', 'Section101', 'Fall', 2023, '5', 'CR3', 'TS2');
-- END ADD_SECTIONS

-- BEGIN ADD_STUDENTS
INSERT INTO account (email, password, type)
VALUES ('avengersassemble@stark.com', '123456', 'student'),
       ('andrew@uml.edu', '123456', 'student'),
       ('scarletwitch@uml.edu', '123456', 'student'),
       ('hulksmash@uml.edu', '123456', 'student'),
       ('i_am_ironman@uml.edu', '123456', 'student'),
       ('natasha@uml.edu', '123456', 'student'),
       ('clint@uml.edu', '123456', 'student'),
       ('peter.parker@uml.edu', '123456', 'student'),
       ('sam.wilson@uml.edu', '123456', 'student'),
       ('bucky@uml.edu', '123456', 'student'),
       ('tchalla@uml.edu', '123456', 'student'),
       ('carol.danvers@uml.edu', '123456', 'student'),
       ('scott.lang@uml.edu', '123456', 'student'),
       ('stephen.strange@uml.edu', '123456', 'student'),
       ('hammertime@uml.edu', '123456', 'student'),
       ('stretcharmstrong@uml.edu', '123456', 'student'),
       ('invisibo@uml.edu', '123456', 'student'),
       ('leader@uml.edu', '123456', 'student'),
       ('shuri@uml.edu', '123456', 'student');

INSERT INTO student (student_id, name, email, dept_name)
VALUES ('0102559623', 'Steve Rogers', 'avengersassemble@stark.com',
        'Miner School of Computer & Information Sciences'),
       ('3149703500', 'Andrew Dodge', 'andrew@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('5519262752', 'Wanda Maximoff', 'scarletwitch@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('0488917281', 'Bruce Banner', 'hulksmash@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('0175846026', 'Tony Stark', 'i_am_ironman@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('2983746590', 'Natasha Romanoff', 'natasha@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('3498752311', 'Clint Barton', 'clint@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('1123581321', 'Peter Parker', 'peter.parker@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('8374659201', 'Sam Wilson', 'sam.wilson@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('8371948203', 'Bucky Barnes', 'bucky@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('9182736450', 'Challa', 'tchalla@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('5647382910', 'Carol Danvers', 'carol.danvers@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('7362819450', 'Scott Lang', 'scott.lang@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('9102837465', 'Stephen Strange', 'stephen.strange@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('1029384756', 'Shuri', 'shuri@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('9102817465', 'Thor Odinson', 'hammertime@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('9602837465', 'Reed Richards', 'stretcharmstrong@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('9102837565', 'Sue Storm', 'invisibo@uml.edu',
        'Miner School of Computer & Information Sciences'),
       ('9602837461', 'Samuel Sterns', 'leader@uml.edu',
        'Miner School of Computer & Information Sciences');

INSERT INTO undergraduate (student_id, total_credits, class_standing)
VALUES ('0102559623', 0, 'Freshman'),
       ('3149703500', 0, 'Junior'),
       ('2983746590', 15, 'Sophomore'),
       ('3498752311', 45, 'Junior'),
       ('1123581321', 0, 'Freshman'),
       ('8374659201', 90, 'Senior'),
       ('8371948203', 60, 'Junior'),
       ('9182736450', 30, 'Sophomore'),
       ('5647382910', 0, 'Freshman'),
       ('7362819450', 105, 'Senior'),
       ('9102837465', 75, 'Junior'),
       ('1029384756', 15, 'Sophomore');

INSERT INTO master (student_id, total_credits)
VALUES ('5519262752', 0);

INSERT INTO PhD (student_id, qualifier, proposal_defence_date,
                 dissertation_defence_date)
VALUES ('0488917281', 'Passed', '2022-04-06', '2025-04-06'),
       ('0175846026', 'Failed', '2021-10-12', '2025-04-06'),
       ('9102817465', 'Failed', '2021-10-12', '2025-04-06'),
       ('9602837465', 'Passed', '2021-10-12', '2025-04-06'),
       ('9102837565', 'Passed', '2021-10-12', '2025-04-06'),
       ('9602837461', 'Passed', '2021-10-12', '2025-04-06');
-- END ADD_STUDENTS

-- BEGIN ADD_TAKES_RECORDS
INSERT INTO take (student_id, course_id, section_id, semester, year, grade)
VALUES ('0102559623', 'COMP1010', 'Section101', 'Fall', 2023, 'A+'),
       ('0102559623', 'COMP1020', 'Section101', 'Spring', 2024, 'A-'),
       ('3149703500', 'COMP1020', 'Section101', 'Spring', 2024, 'B-'),
       ('2983746590', 'COMP1020', 'Section101', 'Spring', 2024, 'B-'),
       ('3498752311', 'COMP1020', 'Section101', 'Spring', 2024, 'B-'),
       ('1123581321', 'COMP1020', 'Section101', 'Spring', 2024, 'B-'),
       ('8374659201', 'COMP1020', 'Section101', 'Spring', 2024, 'B-'),
       ('8371948203', 'COMP1020', 'Section101', 'Spring', 2024, 'B-'),
       ('9182736450', 'COMP1020', 'Section101', 'Spring', 2024, 'B-'),
       ('5647382910', 'COMP1020', 'Section101', 'Spring', 2024, 'B-'),
       ('7362819450', 'COMP1020', 'Section101', 'Spring', 2024, 'B-'),
       ('9102837465', 'COMP1020', 'Section101', 'Spring', 2024, 'B-'),
       ('1029384756', 'COMP1020', 'Section101', 'Spring', 2024, 'C-'),
       ('5519262752', 'COMP2010', 'Section101', 'Fall', 2023, 'F'),
       ('0102559623', 'COMP2040', 'Section201', 'Spring', 2024, null),
       ('3149703500', 'COMP2040', 'Section201', 'Spring', 2024, 'B+'),
       ('2983746590', 'COMP2040', 'Section201', 'Spring', 2024, 'A+'),
       ('3498752311', 'COMP2040', 'Section201', 'Spring', 2024, 'C+'),
       ('1123581321', 'COMP2040', 'Section201', 'Spring', 2024, 'B'),
       ('8374659201', 'COMP2040', 'Section201', 'Spring', 2024, 'F'),
       ('8371948203', 'COMP2040', 'Section201', 'Spring', 2024, 'B+'),
       ('9182736450', 'COMP2040', 'Section201', 'Spring', 2024, 'A'),
       ('5647382910', 'COMP2040', 'Section201', 'Spring', 2024, 'A-'),
       ('7362819450', 'COMP2040', 'Section201', 'Spring', 2024, 'C'),
       ('9102837465', 'COMP2040', 'Section201', 'Spring', 2024, 'B+'),
       ('1029384756', 'COMP2040', 'Section201', 'Spring', 2024, 'B-'),
       ('5519262752', 'COMP2040', 'Section201', 'Spring', 2024, 'A+'),
       ('0175846026', 'COMP2040', 'Section201', 'Spring', 2024, 'B-'),
       ('3149703500', 'COMP2030', 'Section201', 'Fall', 2025, null),
       ('3149703500', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('0102559623', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('2983746590', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('3498752311', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('1123581321', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('8374659201', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('8371948203', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('9182736450', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('5647382910', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('7362819450', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('9102837465', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('1029384756', 'COMP3050', 'Section201', 'Fall', 2025, null),
       ('3149703500', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('2983746590', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('3498752311', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('1123581321', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('8374659201', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('8371948203', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('9182736450', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('5647382910', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('7362819450', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('9102837465', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('1029384756', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('5519262752', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('9602837465', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('9102837565', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('9602837461', 'COMP1010', 'Section101', 'Fall', 2025, null),
       ('0488917281', 'COMP1010', 'Section101', 'Fall', 2023, 'A+'),
       ('0488917281', 'COMP1020', 'Section102', 'Spring', 2024, 'A'),
       ('0488917281', 'COMP2010', 'Section101', 'Fall', 2023, 'B'),
       ('0488917281', 'COMP2040', 'Section201', 'Spring', 2024, 'B-'),
       ('0488917281', 'MATH1010', 'Section101', 'Spring', 2025, 'A+'),
       ('0488917281', 'MATH1020', 'Section101', 'Fall', 2024, 'B'),
       ('0488917281', 'MATH3010', 'Section202', 'Spring', 2025, 'C+'),
       ('0488917281', 'MATH3040', 'Section101', 'Fall', 2024, 'A+'),
       ('0488917281', 'COMP2030', 'Section201', 'Spring', 2025, 'C'),
       ('0488917281', 'COMP3050', 'Section201', 'Spring', 2025, 'A'),
       ('9102837465', 'SOC1020', 'Section101', 'Fall', 2025, null),
       ('1029384756', 'SOC1020', 'Section101', 'Fall', 2025, null),
       ('5519262752', 'SOC1020', 'Section101', 'Fall', 2025, null),
       ('9602837465', 'SOC1020', 'Section101', 'Fall', 2025, null),
       ('9102837565', 'SOC1020', 'Section101', 'Fall', 2025, null),
       ('9602837461', 'SOC1020', 'Section101', 'Fall', 2025, null),
       ('0102559623', 'SOC1020', 'Section101', 'Fall', 2025, null),
       ('5519262752', 'SOC1020', 'Section101', 'Fall', 2023, 'A'),

       ('3149703500', 'MATH3010', 'Section202', 'Spring', 2025, 'A'),
       ('5519262752', 'MATH3010', 'Section202', 'Spring', 2025, 'B+'),
       ('0175846026', 'MATH3010', 'Section202', 'Spring', 2025, 'B'),
       ('2983746590', 'MATH3010', 'Section202', 'Spring', 2025, 'A'),
       ('3498752311', 'MATH3010', 'Section202', 'Spring', 2025, 'B-'),
       ('1123581321', 'MATH3010', 'Section202', 'Spring', 2025, 'A'),
       ('8374659201', 'MATH3010', 'Section202', 'Spring', 2025, 'B+'),
       ('8371948203', 'MATH3010', 'Section202', 'Spring', 2025, 'A-'),
       ('0102559623', 'MATH3010', 'Section202', 'Spring', 2025, 'A'),

       ('5647382910', 'MATH3010', 'Section202', 'Fall', 2025, NULL),
       ('7362819450', 'MATH3010', 'Section202', 'Fall', 2025, NULL),
       ('9102837465', 'MATH3010', 'Section202', 'Fall', 2025, NULL),
       ('1029384756', 'MATH3010', 'Section202', 'Fall', 2025, NULL),
       ('9102817465', 'MATH3010', 'Section202', 'Fall', 2025, NULL),
       ('9602837465', 'MATH3010', 'Section202', 'Fall', 2025, NULL),
       ('9102837565', 'MATH3010', 'Section202', 'Fall', 2025, NULL),
       ('9602837461', 'MATH3010', 'Section202', 'Fall', 2025, NULL),
       ('9182736450', 'MATH3010', 'Section202', 'Fall', 2025, NULL),

       ('3149703500', 'SOC1010', 'Section101', 'Fall', 2025, NULL),
       ('5519262752', 'SOC1010', 'Section101', 'Fall', 2025, NULL),
       ('0175846026', 'SOC1010', 'Section101', 'Fall', 2025, NULL),
       ('2983746590', 'SOC1010', 'Section101', 'Fall', 2025, NULL),
       ('3498752311', 'SOC1010', 'Section101', 'Fall', 2025, NULL),
       ('1123581321', 'SOC1010', 'Section101', 'Fall', 2025, NULL),
       ('8374659201', 'SOC1010', 'Section101', 'Fall', 2025, NULL),
       ('8371948203', 'SOC1010', 'Section101', 'Fall', 2025, NULL),
       ('9182736450', 'SOC1010', 'Section101', 'Fall', 2025, NULL);


-- END ADD_TAKES_RECORDS

-- BEGIN ADD_PREREQUISITES_RECORDS
INSERT INTO prereq (course_id, prereq_id)
VALUES ('COMP1020', 'COMP1010'),
       ('COMP2010', 'COMP1020'),
       ('COMP2040', 'COMP2010'),
       ('MATH1020', 'MATH1010'),
       ('MATH3040', 'MATH3010'),
       ('COMP3050', 'COMP2030');
-- END ADD_PREREQUISITES_RECORDS

-- BEGIN ADD_ADVISE_RECORDS
INSERT INTO advise (instructor_id, student_id, start_date, end_date)
VALUES ('1', '0175846026', '2025-01-21', '2025-12-21'),
       ('3', '0175846026', '2025-01-21', '2025-12-21'),
       ('5', '0488917281', '2024-01-21', '2025-05-04');
-- END ADD_ADVISE_RECORDS

-- BEGIN ADD_UNDERGRADUATE_GRADER_RECORDS
INSERT INTO undergraduateGrader
(student_id, course_id, section_id, semester, year)
VALUES ('7362819450', 'MATH3010', 'Section202', 'Fall', '2025'),
       ('8371948203', 'COMP3050', 'Section201', 'Fall', '2025');
-- END ADD_UNDERGRADUATE_GRADER_RECORDS

-- BEGIN ADD_UNDERGRADUATE_GRADER_RECORDS
-- END ADD_UNDERGRADUATE_GRADER_RECORDS

-- BEGIN CREATE_BILL_TABLE
-- @desc Create a payment table.
-- @author James Chen
CREATE TABLE bill
(
    student_id VARCHAR(10),
    semester   VARCHAR(6),
    year       DECIMAL(4),
    status     VARCHAR(6),
    CHECK (semester in ('Fall', 'Winter', 'Spring', 'Summer')),
    CHECK (status in ('Paid', 'Unpaid')),
    PRIMARY KEY (student_id, semester, year),
    FOREIGN KEY (student_id) REFERENCES student (student_id)
);
INSERT INTO bill (student_id, semester, year, status)
VALUES ('0102559623', 'Spring', 2024, 'Unpaid'),
       ('0102559623', 'Fall', 2023, 'Paid');
-- END CREATE_BILL_TABLE

-- BEGIN CREATE_SCHOLARSHIP_TABLE
-- @desc Represents a collection of scholarships.
-- @author James Chen
CREATE TABLE scholarship
(
    student_id  VARCHAR(10),
    semester    VARCHAR(6) NOT NULL,
    year        DECIMAL(4) NOT NULL,
    scholarship INT        NOT NULL,
    CHECK (semester in ('Fall', 'Winter', 'Spring', 'Summer')),
    PRIMARY KEY (student_id, semester, year),
    FOREIGN KEY (student_id) REFERENCES student (student_id)
        ON DELETE CASCADE
);
-- END CREATE_SCHOLARSHIP_TABLE

-- BEGIN CREATE_DEGREE_PATHWAY
CREATE TABLE degree_pathway
(
    dept_name VARCHAR(100) NOT NULL,
    course_id VARCHAR(20),
    PRIMARY KEY (dept_name, course_id),
    FOREIGN KEY (course_id) REFERENCES course (course_id)
        ON DELETE CASCADE
);
-- END CREATE_DEGREE_PATHWAY

-- BEGIN ADD_DEGREE_PATHWAY_RECORDS
INSERT INTO degree_pathway (dept_name, course_id)
VALUES ('Miner School of Computer & Information Sciences', 'COMP1010'),
       ('Miner School of Computer & Information Sciences', 'COMP1020'),
       ('Miner School of Computer & Information Sciences', 'COMP2010'),
       ('Miner School of Computer & Information Sciences', 'COMP2030'),
       ('Miner School of Computer & Information Sciences', 'COMP2040'),
       ('Miner School of Computer & Information Sciences', 'COMP3050'),
       ('Miner School of Computer & Information Sciences', 'MATH1010'),
       ('Miner School of Computer & Information Sciences', 'MATH1020'),
       ('Miner School of Computer & Information Sciences', 'MATH3010'),
       ('Miner School of Computer & Information Sciences', 'MATH3040'),
       ('Miner School of Computer & Information Sciences', 'SOC1010'),
       ('Miner School of Computer & Information Sciences', 'SOC1020');
-- END ADD_DEGREE_PATHWAY_RECORDS
