Create database if not exists songDatabase;
Use songDatabase;

-- Data about Albums
Create Table if not exists Album_Table
(
	album_id int NOT NULL Primary key,
    album_name varchar(255),
    album_release varchar(255),
    album_artist varchar(255),
    album_cover varchar(255)
);

-- Data about Songs
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
    song_lyrics text,
    song_views int, 
    
    FOREIGN KEY (album_id)
    REFERENCES Album_Table(album_id)
);


select song_artist, count(song_artist) 
from Song_Table 
group by song_artist 
order by count(song_artist) desc;

select * from song_table;


select count(song_artist) from Song_Table;


select * from Song_Table;
select * from Album_Table;

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


Select * from songs_return;
select * from lookup_table;



