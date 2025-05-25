CREATE TABLE IF NOT EXISTS "Message" (
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

CREATE TABLE IF NOT EXISTS "Category" (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    name TEXT NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS "Payment" (
    payment_id INTEGER PRIMARY KEY AUTOINCREMENT,  -- Added payment_id for uniqueness
    service_id INTEGER NOT NULL,
    buyer_id INTEGER NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status TEXT NOT NULL DEFAULT 'pending',
    FOREIGN KEY (service_id) REFERENCES Service(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES User(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS "Service" (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    price REAL NOT NULL,
    created_at TIMESTAMP,
    number_applications INTEGER DEFAULT 0,
    category TEXT NOT NULL,
    buyer_id INTEGER NOT NULL,
    worker_id INTEGER,  -- NULL permitido
    state TEXT NOT NULL DEFAULT "hiring",
    FOREIGN KEY (buyer_id) REFERENCES User(id)
        ON DELETE CASCADE,
    FOREIGN KEY (worker_id) REFERENCES User(id)
        ON DELETE CASCADE,
    FOREIGN KEY (category) REFERENCES Category(name)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS "User" (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at TEXT,
    level INTEGER NOT NULL DEFAULT 0,
    birth_date TEXT NOT NULL,
    profile_picture_id INTEGER,
    phone TEXT NOT NULL,
    nr_bank_account TEXT NOT NULL,
    address TEXT NOT NULL,
    rate INTEGER DEFAULT 0, 
    description TEXT DEFAULT '',
    user_type TEXT NOT NULL DEFAULT 'user',
    FOREIGN KEY (profile_picture_id) REFERENCES Images(id)
        ON DELETE SET NULL
    );

CREATE TABLE IF NOT EXISTS "Application" (
    service_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    PRIMARY KEY (service_id, user_id),
    FOREIGN KEY (service_id) REFERENCES Service(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS "Images" (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL
);