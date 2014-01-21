
CREATE TABLE activity (
    id serial primary key,
    type_id integer not null,
    user_id integer,
    "name" character varying(255) NOT NULL,
    priority integer NOT NULL default 0,
    description character varying(255) NOT NULL,
    creation_date timestamp(0) without time zone DEFAULT now() NOT NULL,
    last_update timestamp(0) without time zone DEFAULT now() NOT NULL,
    active boolean NOT NULL default true,
    status integer NOT NULL default 0
);



CREATE TABLE activity_interaction (
    id serial primary key,
    user_id integer,
    activity_id integer,
    description character varying(255) NOT NULL,
    creation_date timestamp(0) without time zone DEFAULT now() NOT NULL,
    allocated_time time(0) without time zone NOT NULL
);



CREATE TABLE activity_type (
    id serial primary key,
    name character varying(255) NOT NULL,
    description character varying(255) NOT NULL,
    creation_date timestamp(0) without time zone DEFAULT now() NOT NULL,
    last_update timestamp(0) without time zone DEFAULT now() NOT NULL,
    active boolean NOT NULL
);

--

CREATE TABLE user_work_day (
    id serial primary key,
    user_id integer not null,
    date date NOT NULL,
    day_active_hour time(0) without time zone NOT NULL,
    constraint uk_user_date unique(user_id,date) 
);


CREATE TABLE users (
    id serial primary key,
    name character varying(255) NOT NULL,
    cpf character varying(255),
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    day_active_hour time(0) without time zone NOT NULL,
    active boolean NOT NULL,
    creation_date timestamp(0) without time zone DEFAULT now() NOT NULL,
    last_access timestamp(0) without time zone,
    last_update timestamp(0) without time zone DEFAULT now() NOT NULL
);


INSERT INTO users (name, cpf, email, password, day_active_hour, active, creation_date, last_access, last_update)
 VALUES ('Administrador', NULL, 'admin@admin.com', '$2a$08$8kxMyHlhIAYRnhqy5a0q5OMbVf8hkK05Bt9qK0HEeY.Nk9RQ6B3AG',
 '07:30:00', true, '2014-01-20 10:06:48', '2014-01-21 19:32:07', '2014-01-21 19:32:07');
