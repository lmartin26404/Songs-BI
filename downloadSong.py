
import requests
import json
from bs4 import BeautifulSoup
import time
import mysql.connector

# Overall, flow of this program.
# Search -> artist id -> songs to get songs id -> lyrics web
#
# Loop Artist
#   Search For Artist -> Artist ID      - Just need 1 
#     Loop over songs to get songs ID   - Add them to an array
#       Loop over the lyrics with song ID to get the lyrics
#
# Todo:
#   1) Clean the data. Igore everything up to the first line break?
#   2) Remove [Verse] [Chourus] [Outro]. Just igorne all [] Things like that just igore everything before and after them.
#   3) Get the data that we want to and put that in the data base. Make sure to have a sleep.

# Database connections
connection = mysql.connector.connect(user='root',password='Password@MySQL',host = 'localhost', database = 'songDatabase')

cursor = connection.cursor()

# Genius API data
client_access_token  = 'wbairHAoyw6vkQksrO7YPTDZ95skv7s6l0arodw9e0IUbqRaajYRx4KLmPcix9Uc'
headers = {"Authorization": f"Bearer {client_access_token}"}



# Change these values
# Total songs = songs_per_page * max_pages
band_array = ["Radiohead","Pink Floyd"]

songs_per_page = 2
max_pages = 2

song_id_array = []

artist_id = 0


def get_Genius_Data(songs_per_page, max_pages): 

  new_band = True

  for artist in range(len(band_array)):
    current_band = band_array[artist]
    current_band = current_band.replace(" ","%20")

    # Band to search for
    band_url = f"https://api.genius.com/search?q={current_band}"

    response = requests.get(band_url, headers=headers)
    artist_data = response.json()

    # Gets the Band ID
    # Prevent searching for the Band ID over and over
    if new_band == True:
      print("***************************")
      print(f"Finding songs by {current_band}")
      print("***************************")

      artist_id = artist_data["response"]["hits"][0]["result"]["primary_artist"]["id"]
      new_band = False
    
    # Gets all the song ids for an artist


    page_counter = 1

    for page in range(max_pages):
      songs_url = f"https://api.genius.com/artists/{artist_id}/songs?sort=popularity&page={page_counter}&per_page={songs_per_page}"
      response_songs = requests.get(songs_url, headers=headers)
      songs_data = response_songs.json()


      for song in songs_data["response"]["songs"]:
        song_id = song["id"]
        print(f"Found song {song_id}")

        song_id_array.append(song_id)
      
      # Sleep to prevent being rate limited
      time.sleep(1)

      page_counter = page_counter + 1
    
    new_band = True

  lyrics = ""
  sleep_counter = 0
  max_songs_before_sleep = 50

  # Get the song lryics from the song id
  for song in range(len(song_id_array)):
    current_song_id = song_id_array[song]

    song_url = f"https://api.genius.com/songs/{current_song_id}"

    print(f"Searching for song {song_url}")

    response_song = requests.get(song_url, headers=headers)

    json_song = response_song.json()

    # Prevents a NoneType Error
    song_data = json_song.get("response", {}).get("song", {})
    views_data = json_song.get("response", {}).get("song",{}).get("stats",{})
    pro_data = json_song.get("response",{}).get("song",{}).get("producer_artists",[])
    write_data = json_song.get("response",{}).get("song",{}).get("writer_artists",[])

    album_data = song_data.get("album") or {}

    # Song data
    song_path = song_data.get("path")
    song_id = song_data.get("id")
    song_title = song_data.get("title")
    song_artist = song_data.get("artist_names")
    song_language = song_data.get("language")
    song_release_date = song_data.get("release_date")
    song_clip = song_data.get("apple_music_player_url")
    song_producer = pro_data[0].get("name") if pro_data else None
    song_writer = write_data[0].get("name") if write_data else None
    song_views = views_data.get("pageviews")

    # Album data
    album_id = album_data.get("id")
    album_cover = album_data.get("cover_art_url")
    album_name = album_data.get("name")
    album_artist = album_data.get("primary_artist_names")
    album_release = album_data.get("release_date_for_display")

    # Gets the song lyrics
    compelte_url = "https://genius.com" + song_path

    page = requests.get(compelte_url)
    html = BeautifulSoup(page.text,"html.parser")
    song_lyrics = ""

    lyrics_divs = html.select('div[data-lyrics-container="true"]')

    # ADD TO THE DATABASE. Check that the song does not exist already
    for div in lyrics_divs:
        song_lyrics += div.get_text(separator="\n")


    # Adds the song and album data to the database if it does not exists
    cursor.execute(
    """
      INSERT IGNORE INTO Song_Table
        (song_id, album_id, song_path, song_title, song_artist, song_language, song_release_date, song_clip, song_producer, song_writer, song_views)
      VALUES
        (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)               
    """,
      (str(song_id), str(album_id), ''.join(song_path), ''.join(song_title), ''.join(song_artist), ''.join(song_language), ''.join(song_release_date), ''.join(song_clip), str(song_producer), str(song_writer),  int(song_views))
    )

    cursor.execute(
    """
      INSERT IGNORE INTO Album_Table
        (album_id, album_name, album_relase, album_artist, album_cover)
      VALUES
        (%s,%s,%s,%s,%s)
    """,
      (str(album_id), str(album_name), str(album_release), str(album_artist), str(album_cover))
    )

    connection.commit()

    # Prevent being rate limited
    sleep_counter = sleep_counter + 1
    if sleep_counter >= max_songs_before_sleep:
      time.sleep(1)
      sleep_counter = 0

  

  # Closes the connection
  cursor.close()




get_Genius_Data(songs_per_page, max_pages)


def get_spotify_data():
  # Spotify API
  # duration
  # explicit
  # Genre
  # id
  # link to spotify page
  # artist url

  # Authorization
  spotify_url = "https://accounts.spotify.com/api/token"
  spotify_headers = {}
  spotify_data = {}

  spotify_clientId = "5b0ad3970d454a0594eef209992fcbdd"
  spotify_client_sercet = "54fac85a3770416cba8fc05582b323c4"

  import base64

  messae = f"{spotify_clientId}:{spotify_client_sercet}"
  messageBytes = messae.encode('ascii')
  base64Bytes = base64.b64encode(messageBytes)
  base64Message = base64Bytes.decode('ascii')

  spotify_headers['Authorization'] = f"Basic {base64Message}"
  spotify_data['grant_type'] = "client_credentials"

  r = requests.post(spotify_url, headers=spotify_headers, data=spotify_data)

  token = r.json()['access_token']


  # Search for an artist
  artist_name = "Radiohead"
  album_name_url = f"https://api.spotify.com/v1/search?q={artist_name}&type=artist&limit=10&offset=0"
  headers = {
    "Authorization":"Bearer " + token
  }

  res = requests.get(url=album_name_url, headers=headers)

  json_data = res.json()

  counter = 0

  # The flow of the program
  # 

  # Gets Artist ID
  artist_id = 0
  # Some of the band names that come up are not what they should be
  for data in json_data["artists"]["items"]:
    current_band_name = data["name"]

    if artist_name == current_band_name:
      artist_id = data["id"]
      print(artist_id)


      break

    band_id = data["id"]



    counter = counter + 1



      # extranl_urls -> spotify 
      # Followers
      # Genre
      # Populatiry


        #for song in songs_data["response"]["songs"]:
        #  song_id = song["id"]
        #  print(f"Found song {song_id}")

        #  song_id_array.append(song_id)

  # Have a different program to get users Spotify Playlist