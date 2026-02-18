import mysql.connector
import pandas as pd
import csv
import re
import pandas as pd

import subprocess
import sys

# Install scikit-learn

# Worst way to solve this problem. Fix this sometime
#subprocess.check_call([sys.executable, "-m", "pip", "install", "scikit-learn"])
#subprocess.check_call([sys.executable, "-m", "pip", "install", "nltk"])
##subprocess.check_call([sys.executable, "-m", "pip", "install", "joblib"])
#subprocess.check_call([sys.executable, "-m", "pip", "install", "numpy"])

import sklearn

from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score, classification_report
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.feature_extraction.text import CountVectorizer
from sklearn import metrics
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.linear_model import LogisticRegression


from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, confusion_matrix,precision_score, recall_score,f1_score,roc_curve, roc_auc_score
from sklearn.feature_extraction.text import TfidfVectorizer
import os
import joblib
import numpy as np

import nltk

#nltk.download('punkt_tab')      
#nltk.download('wordnet')    
#nltk.download('omw-1.4') 
#nltk.download('averaged_perceptron_tagger_eng')

from nltk.tokenize import word_tokenize
from nltk.stem import WordNetLemmatizer

from nltk.tokenize import word_tokenize
from nltk import pos_tag
from nltk.stem import WordNetLemmatizer

from sklearn.ensemble import RandomForestClassifier

# Gets the data from the database
def get_data():
    connection = mysql.connector.connect(user='root',password='Password@MySQL',host='localhost',database='songDatabase',ssl_disabled=True)

    cursor = connection.cursor()

    cursor.execute("SELECT distinct lyrics FROM songs_data WHERE lyrics LIKE '% May %';")

    rows = cursor.fetchall()  

    df_pulled = pd.DataFrame(rows, columns=["lyrics"])


    with open('clean_data.csv',mode='w', newline = '') as file:
        writer = csv.writer(file)

        for index,row in df_pulled.iterrows():
            lyrics = row["lyrics"]
            for lyric_line in lyrics.splitlines():
                if (" May " in lyric_line or "May " in lyric_line or " May" in lyric_line) and (len(lyric_line) < 65):    
                    
                    lyric_line = clean_data(lyric_line)

                    writer.writerow([lyric_line])  

    cursor.close()
    connection.close()

   

def clean_data(lyric_line):

    # Lower case
    lyric_line = lyric_line.lower()
         
    # Remove numebrs
    lyric_line = re.sub(r'\d+', '', lyric_line)

    # Remove unneed characters and only keeps letters
    lyric_line = re.sub(r'\W', ' ', lyric_line)

    # Lemmatization such as 'cats' get turned into 'cat'
    lemmatizer = WordNetLemmatizer()
    tokens = word_tokenize(lyric_line)

    lem_words = [lemmatizer.lemmatize(word) for word in tokens]
    lyric_line = " ".join(lem_words)

    # Better lemmatization such as 'running' becomes 'run'
    tagged_tokens = pos_tag(tokens)

    def get_wordnet_pos(tag):
        if tag.startswith('J'):
            return 'a'
        elif tag.startswith('V'):
            return 'v'
        elif tag.startswith('N'):
            return 'n'
        elif tag.startswith('R'):
            return 'r'
        else:
            return 'n'
        
    lemmatized_sentence = []
    for word, tag in tagged_tokens:
        if word.lower() == 'are' or word.lower() in ['is','am']:
            lemmatized_sentence.append(word)
        else:
            lemmatized_sentence.append(lemmatizer.lemmatize(word,get_wordnet_pos(tag)))
    
    get_wordnet_pos(tag)

    lyric_line = " ".join(lemmatized_sentence)


    # Remove stop words
    stop_words = ["i", "me", "my", "myself", "we", "our", "ours", "ourselves", "you", "your", "yours", "yourself", "yourselves", "he", "him", "his", "himself", "she", "her", "hers", "herself", "it", "its", "itself", "they", "them", "their", "theirs", "themselves", "what", "which", "who", "whom", "this", "that", "these", "those", "am", "is", "are", "was", "were", "be", "been", "being", "have", "has", "had", "having", "do", "does", "did", "doing", "a", "an", "the", "and", "but", "if", "or", "because", "as", "until", "while", "of", "at", "by", "for", "with", "about", "against", "between", "into", "through", "during", "before", "after", "above", "below", "to", "from", "up", "down", "in", "out", "on", "off", "over", "under", "again", "further", "then", "once", "here", "there", "when", "where", "why", "how", "all", "any", "both", "each", "few", "more", "most", "other", "some", "such", "no", "nor", "not", "only", "own", "same", "so", "than", "too", "very", "s", "t", "can", "will", "just", "don", "should", "now"]

    words = lyric_line.split()

    filtered_words = [w for w in words if w.lower() not in stop_words]

    lyric_line = " ".join(filtered_words)

    return lyric_line

def train_model():

    # Reads in the CSV and converts into a dataframe
    ml = pd.read_csv('LabelData.csv',
                     sep=',',
                     header=None)
    ml.columns = ["Lyrics","Bool_Month"]

    # Splits the data
    X = ml['Lyrics']
    y = ml['Bool_Month']

    # Remove duplicate values
    ml.drop_duplicates()

    X_train, X_test, y_train, y_test = train_test_split(X,y,test_size=0.2,random_state=42)

    count_vectorizer = CountVectorizer()

    X_train_count = count_vectorizer.fit_transform(X_train)
    X_test_count = count_vectorizer.transform(X_test)

    # Random Forest
    rt_classifier = RandomForestClassifier(n_estimators=1000,max_features="sqrt",random_state=3)
    rt_classifier.fit(X_train_count, y_train)

    # Accuracy
    y_pred = rt_classifier.predict(X_test_count)

    accuracy = metrics.accuracy_score(y_test,y_pred)

    print(f"Accuracy Random Forest {accuracy:.3f}")

    # Save the model and the vectorizer
    joblib.dump(rt_classifier,"random_forest.joblib")
    joblib.dump(count_vectorizer, "vectorizer.joblib")


def run_model():

    # Loads the model and the vectorizer
    model = joblib.load("random_forest.joblib")
    vectorizer = joblib.load("vectorizer.joblib")

    # Cleans the data input
    text = "The children are running towards a better place"
    example = clean_data(text)
    example = [example]

    # Converts the word into vectorizer
    example_vec = vectorizer.transform(example)

    # Runs the model on the trained data and outputs the result
    prediction = model.predict(example_vec)
    print("Prediction: ", prediction)

    
def main():
    # Gets the data from the database and cleans it
    get_data()

    # Trains the model and gets the accuary
    train_model()

    # Runs the model on one example based off of the trained model
    run_model()


    
if __name__ == "__main__":
    main()
   
