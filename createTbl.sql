
CREATE TABLE log_email (
  id bigint NOT NULL,
  email_to VARCHAR(250) NULL DEFAULT NULL,
  subject_email VARCHAR(250) NULL DEFAULT NULL,
  body_email TEXT NULL DEFAULT NULL,
  is_true INTEGER DEFAULT 0,
  created_date timestamp without time zone DEFAULT now()
  
);

ALTER TABLE log_email OWNER TO "postgres";

CREATE SEQUENCE log_email_id_seq
  START WITH 1
  INCREMENT BY 1
  NO MINVALUE
  NO MAXVALUE
  CACHE 1;

ALTER TABLE log_email_id_seq OWNER TO "postgres";

ALTER SEQUENCE log_email_id_seq OWNED BY log_email.id;

ALTER TABLE ONLY log_email ALTER COLUMN id SET DEFAULT nextval('log_email_id_seq'::regclass);

SELECT pg_catalog.setval('log_email_id_seq', 1, false);

ALTER TABLE ONLY log_email
  ADD CONSTRAINT log_email_pkey PRIMARY KEY (id);