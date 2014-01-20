
CREATE TABLE activity (
    id serial primary key,
    type_id integer,
    user_id integer,
    "name" character varying(255) NOT NULL,
    priority integer NOT NULL,
    description character varying(255) NOT NULL,
    creation_date timestamp(0) without time zone NOT NULL default now(),
    last_update timestamp(0) without time zone NOT NULL default now(),
    active boolean NOT NULL,
    status integer NOT NULL
);


--
-- TOC entry 162 (class 1259 OID 16724)
-- Dependencies: 5
-- Name: activity_interaction; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE activity_interaction (
    id serial primary key,
    user_id integer,
    activity_id integer,
    description character varying(255) NOT NULL,
    creation_date timestamp(0) without time zone NOT NULL,
    allocated_time time(0) without time zone NOT NULL
);



--
-- TOC entry 161 (class 1259 OID 16716)
-- Dependencies: 5
-- Name: activity_type; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE activity_type (
    id serial primary key,
    name character varying(255) NOT NULL,
    description character varying(255) NOT NULL,
    creation_date timestamp(0) without time zone NOT NULL,
    last_update timestamp(0) without time zone NOT NULL,
    active boolean NOT NULL
);

--

CREATE TABLE user_work_day (
    id serial primary key,
    user_id integer,
    date date NOT NULL,
    day_active_hour time(0) without time zone NOT NULL
);


--
-- TOC entry 163 (class 1259 OID 16731)
-- Dependencies: 5
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users (
    id serial primary key,
    name character varying(255) NOT NULL,
    cpf character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    day_active_hour time(0) without time zone NOT NULL,
    active boolean NOT NULL,
    creation_date timestamp(0) without time zone NOT NULL,
    last_access timestamp(0) without time zone NOT NULL,
    last_update timestamp(0) without time zone NOT NULL
);

CREATE INDEX idx_390397ab81c06096 ON activity_interaction USING btree (activity_id);


CREATE INDEX idx_390397aba76ed395 ON activity_interaction USING btree (user_id);


CREATE INDEX idx_7f439a0ca76ed395 ON user_work_day USING btree (user_id);



CREATE INDEX idx_ac74095aa76ed395 ON activity USING btree (user_id);


CREATE INDEX idx_ac74095ac54c8c93 ON activity USING btree (type_id);


ALTER TABLE ONLY activity_interaction
    ADD CONSTRAINT fk_390397ab81c06096 FOREIGN KEY (activity_id) REFERENCES activity(id);


ALTER TABLE ONLY activity_interaction
    ADD CONSTRAINT fk_390397aba76ed395 FOREIGN KEY (user_id) REFERENCES users(id);


ALTER TABLE ONLY user_work_day
    ADD CONSTRAINT fk_7f439a0ca76ed395 FOREIGN KEY (user_id) REFERENCES users(id);


ALTER TABLE ONLY activity
    ADD CONSTRAINT fk_ac74095aa76ed395 FOREIGN KEY (user_id) REFERENCES users(id);


ALTER TABLE ONLY activity
    ADD CONSTRAINT fk_ac74095ac54c8c93 FOREIGN KEY (type_id) REFERENCES activity_type(id);
