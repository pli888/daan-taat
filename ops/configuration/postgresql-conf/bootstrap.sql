CREATE TABLE database (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    host VARCHAR(50) NOT NULL,
    port VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
);

INSERT INTO database(name, host, port, password) VALUES ('gigadb-dev', 'localhost', '54321', 'vagrant');

CREATE TABLE "table" (
    id SERIAL PRIMARY KEY ,
    database_id INTEGER NOT NULL REFERENCES database(id),
    name VARCHAR(50) NOT NULL
);

CREATE TABLE "column" (
    name VARCHAR(50) NOT NULL,
    type VARCHAR(50) NOT NULL,
    table_id INTEGER NOT NULL REFERENCES "table"(id)
);



