CREATE TABLE "user"
(
    id       serial PRIMARY KEY,
    email    varchar(100) UNIQUE NOT NULL,
    password varchar(250)        NOT NULL
);