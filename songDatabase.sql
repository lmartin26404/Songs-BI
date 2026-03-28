Create database if not exists songDatabase;
Use songDatabase;



Create Table if not exists Song_Table
(
	song_id int NOT NULL Primary key,
    album_id int NOT NULL,
    song_path varchar(255),
	song_title varchar(255),
    song_artist varchar(255),
    song_language varchar(20),
    song_release_date varchar(50),
    song_clip varchar(255),
	song_producer varchar(255),
    song_writer varchar(255),
    song_views int, 
    
    FOREIGN KEY (album_id)
    REFERENCES Album_Table(album_id)
);

Create Table if not exists Album_Table
(
	album_id varchar(255) NOT NULL Primary key,
    album_name varchar(255),
    album_relase varchar(255),
    album_artist varchar(255),
    album_cover varchar(255)
);



select * from Song_Table;


Create Table if not exists songs_return
(
	key_col varchar(255),
    value_col int,
    artist varchar(255)
);

Create Table if not exists lookup_table
(
	artist varchar(255)
);



