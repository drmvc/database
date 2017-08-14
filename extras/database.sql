-- Sample table for tests
CREATE TABLE users (
  id             INT  NOT NULL AUTO_INCREMENT,
  email          TEXT NOT NULL,
  username       TEXT NOT NULL,
  password       TEXT NOT NULL,
  create_time    TEXT,
  lastlogin_time TEXT,
  enabled        BOOL NOT NULL DEFAULT TRUE,
  deleted        BOOL NOT NULL DEFAULT FALSE,
  PRIMARY KEY (id)
);
-- Test user
INSERT INTO users (email, username, password) VALUES ('admin@email.com', 'admin', 'hash');
