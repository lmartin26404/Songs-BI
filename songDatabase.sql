Create database if not exists songDatabase;

Use songDatabase;

Create Table if not exists songs_data
(
	id int NOT NULL auto_increment,
	title varchar(255),
	album varchar(255),
	year int,
    genre varchar(255),
    artist varchar(255),
    lyrics varchar(15000),
 
    Primary Key(ID)
);

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

-- Is used for the Google Sheet to get all the artists.
select distinct artist from songs_data;

-- select 1 from songs_data where year = 20;
select artist, count(title) as 'Count' from songs_data where artist = 'KISS' group by artist;

select count(title) as 'Count' from songs_data;
select * from lookup_table;

-- drop table songs_return;
set SQL_SAFE_UPDATES = 0;
delete from songs_return where key_col <> "" and value_col <> "";
select * from songs_return;

select count(title) as 'Count' from songs_data where artist = 'KISS';


select distinct artist from songs_data where artist like '%k%';

select * from songs_data;

select count(title) from songs_data;

select lyrics from songs_data;

select * from songs_return;

select lyrics from songs_data;

select MAX( LENGTH(lyrics) - LENGTH(replace(lyrics, ' ', '')) - 1) as 'max' from songs_data;

SELECT distinct lyrics from songs_data WHERE lyrics like '% march %';

-- What bands have the most songs
select artist, count(artist) as songs from songs_data group by artist having count(artist) order by songs desc;

select artist from songs_data where artist = "The Five Keys";


