PRAGMA foreign_keys=off

DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Subscrition;
DROP TABLE IF EXISTS Message;
DROP TABLE IF EXISTS Service;
DROP TABLE IF EXISTS Transaction;
DROP TABLE IF EXISTS Phase;

PRAGMA foreign_keys=on;


CREATE TABLE User (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at TEXT NOT NULL,
    level INTEGER NOT NULL DEFAULT 1,
    birth_date TEXT NOT NULL,
    profile_picture TEXT,
    phone TEXT NOT NULL,
    nr_bank_account TEXT NOT NULL,
    address TEXT NOT NULL
);



CREATE TABLE Message (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sender_id INTEGER NOT NULL,
    receiver_id INTEGER NOT NULL,
    content TEXT NOT NULL,
    timestamp TIMESTAMP ,
    FOREIGN KEY (sender_id) REFERENCES User(id)
        ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES User(id)
        ON DELETE CASCADE
);

CREATE TABLE Category (
    name TEXT PRIMARY KEY NOT NULL,
    description TEXT NOT NULL
);


CREATE TABLE Service (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    price REAL NOT NULL,
    created_at TIMESTAMP ,
    number_applications INTEGER DEFAULT 0,
    category TEXT NOT NULL,
    buyer_id INTEGER NOT NULL,
    worker_id INTEGER NOT NULL,
    FOREIGN KEY (buyer_id) REFERENCES User(id)
        ON DELETE CASCADE,
    FOREIGN KEY (worker_id) REFERENCES User(id)
        ON DELETE CASCADE,
    FOREIGN KEY (category) REFERENCES Category(name)
        ON DELETE CASCADE
);

CREATE TABLE Transaction (
    service_id INTEGER PRIMARY KEY NOT NULL,
    FOREIGN KEY (service_id) REFERENCES Service(id),
    transaction_date TIMESTAMP ,
    status TEXT NOT NULL DEFAULT 'pending'
);

CREATE TABLE Phase (
    service_id INTEGER PRIMARY KEY NOT NULL,
    FOREIGN KEY (service_id) REFERENCES Service(id),
    phase_number INTEGER NOT NULL,
    description TEXT NOT NULL,
    start_date TIMESTAMP,
    end_date TIMESTAMP
);
