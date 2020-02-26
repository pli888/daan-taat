CREATE TABLE database (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO database(name) VALUES ('gigadb-dev');

CREATE TABLE "table" (
    id SERIAL PRIMARY KEY ,
    database_id INTEGER NOT NULL REFERENCES database(id),
    name VARCHAR(50) NOT NULL
);

CREATE TABLE column (
    name VARCHAR(50) NOT NULL,
    type VARCHAR(50) NOT NULL,
    table_id INTEGER NOT NULL REFERENCES table(id)
);



