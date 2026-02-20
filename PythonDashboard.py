# All the imports
import MaybeObject as ml

import sys
import pandas as pd
import mysql.connector
import re

# Gets the object that is selected by the user in a dropdown menu.
option = sys.argv[1]

# MySQL connections
connection = mysql.connector.connect(user='root',password='Password@MySQL',host = 'localhost', database = 'songDatabase', ssl_disabled=True)

cursor = connection.cursor()

# The graphs
if(option == "one"):
    object = sys.argv[2]
    artist = sys.argv[3]
    group = sys.argv[4]

    # Delete the old data from the table.
    
    if(group != "group"):
        cursor.execute("delete from songs_return;")

    nosplit = artist
    artist = artist.strip()
    
    # The query that is going to be ran
    if nosplit == "all":
        cursor.execute("SELECT * FROM songs_data;")
    else:
        cursor.execute("SELECT * FROM songs_data where artist = %s;", (artist,))

    # The object to look for
    objectArray = []
    if object == "gender":
        objectArray = ['girl','boy','man','dude','girl','women','male','female','lady']
    elif object == "direction":
        objectArray = ['North', 'East', 'South', 'West', 'Left', 'Right', 'Up', 'Down']
    elif object == "response":
        objectArray = ['yes','yeah','no','nope','sure','alright']
    elif object == "solar":
        objectArray =  ['Saturn','Jupiter','Neptune','Uranus','Mercury','Venus','Mars','Earth','Pluto','milky way','sky','moon']
    elif object == "day":
        objectArray = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']
    elif object == "month":
        objectArray = ['january','february','march','april','May','june','july','august','september','october','novemeber','decemeber']
    elif object == "drinks":
        objectArray = ['water','tea','coffee','milk','soda','pop','juice','milkshake','beer','wine','vodka']
    elif object == "cars":
        objectArray = ['volvo','volkswagen','toyota','ford','bmw','kia','audi','saab','nissan','hyundai','chevroley','mazda','tesla','subaru','mitsubishi','honda','porche','lexus','jeep','chrysler','dodge','jaguar','cadillac','dodge','cadillac','pontiac','buick','lincoln','mercury','ferrari','bently','lamborghini']
    elif object == "relationship":
        objectArray = ['love','kiss','boyfriend','girlfriend','relationship','hug','babe','wedding','marry','breakup','baby','heart','fiancee']

    # Add the query data into an array
    results = []
    for i, data in enumerate(cursor):
        results.append(data)

    # Trouble words are words that easily have double meaning such as May. It could mean a month or as something that is unsure.
    trouble_words = ["may"]

    # Converts the array into a dataframe with the right column headers.
    df_pulled = pd.DataFrame(results)
    df_pulled.columns = ["id","title","album","year","genre","artist","lyrics"]

    # Creates an empty dictionary used to store the Object Word followed by the Count
    word_count = {}
    lyric_count = {}

    # Makes all the elements in the object list lower case
    for i in range(len(objectArray)):
        objectArray[i] = objectArray[i].lower()
 

    # Loop through the dataframe to find different information
    counter = 0
    for index, row in df_pulled.iterrows():
        line = row["lyrics"].lower()

     
        for lyric_line in line.split("\n"):
            for word in lyric_line.split():

              
                cleanWord = word

                # Clean word properly
                cleanWord = re.sub(r'\d+', '', cleanWord)
                cleanWord = re.sub(r'\W+', '', cleanWord)
                cleanWord = cleanWord.lower().strip()

                # If the current word is a trouble word (a word that has multiple meaning)
                # Causing probelms here with how long it takes
                if cleanWord in trouble_words and object == "month":
                    clean_lyric = ml.clean_data(lyric_line)     
                    total = ml.run_model(clean_lyric)           # 1 -> True   0 -> False
                          
                else:
                    total = 1

           
            
                # Checks for the counts
                if cleanWord in objectArray:
                    if cleanWord not in word_count:
                        word_count[cleanWord] = total
                    else:
                        word_count[cleanWord] += total
            
    # Adds the dictionary to a SQL database
    for key_col, value_col in word_count.items():
        cursor.execute("INSERT INTO songs_return (key_col, value_col, artist) VALUES (%s, %s, %s)", (str(key_col), int(value_col), ''.join(artist)))

    connection.commit()

# The key words
if(option == "two"):
    # The index of the selected value.
    numIndex = sys.argv[2]
    numIndex = int(numIndex)

    # Include stop words or not
    stopWords = sys.argv[3]
    stopWords = int(stopWords)

    # Array of stop words
    stopWordsArray = ["i","me","my","myself","we","our","ours","ourselves","you","your","yours","yourself","yourselves","he","him","his","himself","she","her","hers","herself","it","its","itself","they","them","their","theirs","themselves","what","which","who","whom","this","that","these","those","am","is","are","was","were","be","been","being","have","has","had","do","does","did","doing","a","an","the","and","but","if","or","because","as","until","while","of","at","by","for","with","about","against","between","into","through","during","before","after","above","below","to","from","up","down","in","out","on","off","over","under","again","further","then","once","here","there","when","where","why","how","all","any","both","each","few","more","most","other","some","such","no","nor","not","only","own","same","so","than","too","can","will","just","don","should", "i'm", "know","don't","it's"]

    # Need to go through all the songs and word by word to word. The input is the slicer value which is the word index
    cursor.execute("SELECT * from songs_data")

    # Adds the songs lyrics into an array
    results = []
    for i, data in enumerate(cursor):
        results.append(data)

    # Converts the array into a dataframe with the right column headers.
    df_pulled = pd.DataFrame(results)
    df_pulled.columns = ["id","title","album","year","genre","artist","lyrics"]

    word_count = {}

    currentWord = ""

    # Loop through the song lyrics to word by word
    for index, row in df_pulled.iterrows():
        
        # Gets the lyrics
        line = row["lyrics"]
        line = line.lower()

        # Splits the lyrics by word
    
        word = line.split()

        currentWord = currentWord.lower()

      
        # Error handling to prevent an out of bounds error
        try:
            currentWord = word[numIndex]

        except IndexError:
            currentWord = "END OF SONG"
        
        if currentWord not in word_count:
            if stopWords == 1:
                # 1: Do not add stop words
                if currentWord not in stopWordsArray:
                    word_count[currentWord] = 1
            if stopWords == 0:
                # 0: Add stop words
                word_count[currentWord] = 1
        else:
            if currentWord in word_count:
                word_count[currentWord] += 1    

    # Sort the dictionary
    sort_dict = dict(sorted(word_count.items(),key=lambda item:item[1],reverse=True))
    
    print(sort_dict)

    # Delete the old data from the table.
    cursor.execute("delete from songs_return;")

    # Adds the dictionary to a SQL datbase to be
    for key_col, value_col in sort_dict.items():
        cursor.execute("INSERT INTO songs_return (key_col, value_col) VALUES (%s, %s)", (key_col, value_col))

    connection.commit()


# Closes the connection
cursor.close()
connection.close()