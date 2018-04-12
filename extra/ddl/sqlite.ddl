CREATE TABLE users (
  id         INTEGER PRIMARY KEY     AUTOINCREMENT,
  username   TEXT NOT NULL,
  password   TEXT NOT NULL,
  created    TEXT NOT NULL,
  last_login TEXT NOT NULL
);
