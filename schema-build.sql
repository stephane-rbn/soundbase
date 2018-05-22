CREATE TABLE MEMBER (
    id                     INTEGER PRIMARY KEY AUTO_INCREMENT,
    email                  VARCHAR(254) NOT NULL UNIQUE,
    name                   VARCHAR(60),
    username               VARCHAR(20) NOT NULL UNIQUE,
    birthday               DATE,
    password               CHAR(60),
    registration_date      DATE,
    profile_photo_filename VARCHAR(100) DEFAULT 'photo.png',
    cover_photo_filename   VARCHAR(100) DEFAULT 'cover.png',
    position               INTEGER DEFAULT 0,
    description            TEXT,
    token                  VARCHAR(64)
);

CREATE TABLE SUBSCRIPTION (
    member_following VARCHAR(254) REFERENCES MEMBER (id),
    member_followed  VARCHAR(254) REFERENCES MEMBER (id),
    PRIMARY KEY (member_following, member_followed)
);

CREATE TABLE TRACK (
    id               INTEGER PRIMARY KEY AUTO_INCREMENT,
    title            VARCHAR(60),
    description      TEXT,
    genre            VARCHAR(30),
    track_filename   VARCHAR(100),
    photo_filename   VARCHAR(100),
    publication_date DATE,
    member           INTEGER REFERENCES MEMBER (id)
);

CREATE TABLE PLAYLIST (
    id     INTEGER PRIMARY KEY AUTO_INCREMENT,
    title  VARCHAR(60),
    member INTEGER NOT NULL UNIQUE REFERENCES MEMBER (id)
);

CREATE TABLE INCLUSION (
    playlist INTEGER REFERENCES PLAYLIST (id),
    track    INTEGER REFERENCES TRACK (id),
    PRIMARY KEY (playlist, track)
);

CREATE TABLE POST (
    id               INTEGER PRIMARY KEY AUTO_INCREMENT,
    content          TEXT,
    publication_date DATE,
    member           INTEGER NOT NULL UNIQUE REFERENCES MEMBER (id)
);

CREATE TABLE COMMENT (
    id               INTEGER PRIMARY KEY AUTO_INCREMENT,
    content          TEXT,
    publication_date DATE,
    post             INTEGER NOT NULL UNIQUE REFERENCES POST (id),
    member           INTEGER NOT NULL UNIQUE REFERENCES MEMBER (id)
);

CREATE TABLE EVENTS (
    id          INTEGER PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(60),
    description TEXT,
    capacity    INTEGER,
    event_date  DATE,
    member      INTEGER NOT NULL UNIQUE REFERENCES MEMBER (id)
);

CREATE TABLE REGISTRATION (
    member  VARCHAR(254) REFERENCES MEMBRE (id),
    events INTEGER REFERENCES EVENTS (id),
    PRIMARY KEY (member, events)
);
