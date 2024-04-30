CREATE TABLE monitor (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_event VARCHAR(100),
    competition VARCHAR(100),
    sport VARCHAR(100),
    time_start_event TIMESTAMP,
    home_team VARCHAR(100),
    home_team_score INTEGER,
    away_team VARCHAR(100),
    away_team_score INTEGER,
    favourite_team VARCHAR(100),
    favourite_team_odd_pre FLOAT,
    favourite_team_odd_live FLOAT,
    bookmaker VARCHAR(100),
    'type' VARCHAR(100),
    tips VARCHAR(500),
    alert BOOLEAN,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP
);

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(100),
    email VARCHAR(255),
    password_hash VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    mensagem VARCHAR(100)
);