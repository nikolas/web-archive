CREATE TABLE IF NOT EXISTS bins (
	id INTEGER NOT NULL auto_increment PRIMARY KEY,
  url VARCHAR(255) NOT NULL,
	text LONGTEXT NOT NULL
)

-- rollback
-- DROP TABLE bins