CREATE TABLE users (
  id         INTEGER PRIMARY KEY     AUTOINCREMENT,
  username   TEXT NOT NULL,
  password   TEXT NOT NULL,
  created    TEXT NOT NULL,
  last_login TEXT NOT NULL
);

INSERT INTO users (username, password, created, last_login) VALUES ('admin', 'pass', 'date1', 'date2');
INSERT INTO users (username, password, created, last_login) VALUES ('user1', 'pass', 'date1', 'date2');
INSERT INTO users (username, password, created, last_login) VALUES ('user2', 'pass', 'date1', 'date2');
