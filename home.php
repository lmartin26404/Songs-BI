<html>
    <head>
        <title>Songs BI</title>
        <script src="https://cdn.tailwindcss.com"></script>

    </head>


    <body>

    <!--
            TODO:
    1) Have the button be in a fixed location. Put a hover animation on it too. Fix the location of the Pictures. Maybe put a trans on the changing images.
    2) Need to fix the sizes of the music Spotlight. Write JS code to have this dynamic and not static. Maybe want to set up a table.
    3) Have things that take you to a link take you to a link
    4) Song Analysis Dashboard, Explore the data have a picture instead of a buttton
    5) Have an animation for the flip board


    -->

        <!-- Header -->
        <header>
            <div class = "bg-stone-50 w-screen h-[20%] text-center text-6xl p-[2%] border-solid border-stone-900 border-b-4 font-mono">Songs BI</div>
            <div class="justify-center items-center ml-[33%]">
                <div> 
                    <a class="hover:text-rose-400 hover:underline ml-[2%] mr-[2%]" href="/Dashboard.php">Songs Analysis</a> 
                    <a class="hover:text-rose-400 hover:underline ml-[2%] mr-[2%]" href="www.google.com">Upload Spotify Playlist</a>   
                    <a class="hover:text-rose-400 hover:underline ml-[2%] mr-[2%]" href="www.google.com">Explore Data</a> 
                    <a class="hover:text-rose-400 hover:underline ml-[2%] mr-[2%]" href="www.google.com">The Playground</a> 

                </div>
            </div>
        </header>
        
        


        <!-- Picture that takes up most of the screen -->
        <!-- Flashy colors with a quote about music with a Sing about ...  and have a picture and a graident-->
        <!-- Flash with a white color -->
        <div id="backgroundGradient" class="w-full h-[75%] mt-[1%] bg-gradient-to-t from-slate-900 to-cyan-300 grid grid-cols-2 grid-rows-1 gap-4">
            <div class="grid girds-rows-2 grid-cols-1"> 
                <div id="backgroundQuote" class = "flex items-center justify-center text-2xl pt-[20%] pl-[5%]" style="font-family:'Arial'">One good thing about music, when it hits you, you feel no pain - Bob Marley</div>
                <div class="flex items-center justify-center">
                    <a id="backgroundButton" href="https://open.spotify.com/artist/6BH2lormtpjy3X9DyrGHVj"></a>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full mb-[25%]">
                            Listen Now
                        </button>
                    </a>    
                </div>
            </div>
        
            <img id="backgroundPhoto" class="max-w-[60%] object-cover justify-center p-12 mr-[20%] ml-[20%] rounded-4xl"  src="/Pictures/BobMarley.jpg"></div>
        </div>

        <!-- Music Spotlight -->
        <div class="grid girds-rows-2 bg-gray-100 mt-[2%] ">
            <div class="flex items-center justify-center  max-w-full  text-2xl font-bold mt-[2%]">Music Spotlight</div>

            <div class="grid grid-cols-3 girds-rows-2 mt-[2%] mb-[2%] flex justify-items-center">

                <div class="flex text-center">Artist of the Month: <br> Pink Floyd</div>
                <div class="flex text-center">Album of the Week: <br> Pure Heroine</div>
                <div class="flex text-center">Song of the day: <br>All I Think About Now</div>

                <a href="www.google.com" class="hover:bg-yellow-600 rounded-full w-48 h-48">
                    <div class="bg-[url('Pictures/PinkFloyd.jpeg')] bg-cover bg-center w-48 h-48 rounded-full"></div>
                </a>

                <a href="www.google.com" class="hover:bg-yellow-600 rounded-full w-48 h-48">
                    <div class="bg-[url('Pictures/LordePureHeroine.png')] bg-cover bg-center w-48 h-48 rounded-full"></div>
                </a>

                <a href="www.google.com" class="hover:bg-yellow-600 rounded-full w-48 h-48">
                    <div class="bg-[url('Pictures/SOD.png')] bg-cover bg-center w-48 h-48 rounded-full"></div>
                </a>

            </div>
        </div>

        <!-- Upload spotify playlist -->
        <div class="m2-[4%] pt-[4%] mb-[2%] grid grid-rows-3 grid-cols-1 flex justify-items-center divide-solid divide-indigo-500 border-t-2 ">
            <div class="text-3xl">
                Upload your Spotify Playlist
            </div>

            <div class="min-w-[30%] max-w-[40%] text-center">
                To find out what the music you listen to sings about. Just copy the URL from Spotify and paste it below to find out if your playlist is

                <div id="singabout">
                   singing about cars.
                </div>
            </div>

            <div class="mt-[1%]">
                <input class="h-14 w-96 pr-8 pl-5 rounded z-0 focus:shadow border border-2 border-indigo-500 " placeholder="Upload URL" type="text">
            </div>
        </div>


        <!-- Another way to get to the header information, which is Songs Analysis, and Explore Data. Just not Upload Spotify Playlist
        I might be changing these or adding more. 
        -->
        <div class="grid grid-rows-1 grid-cols-2 text-center mt-[2%] divide-x-2 divide-solid divide-indigo-500 border-t-2 border-indigo-500 border-b-2 border-indigo-500">


            <div class="grid grid-rows-3 grid-cols-1">
                <div class="text-2xl mt-[2%]">Song Analysis Dashboard</div>
                <div class="mx-[4%]">
                    With the Song Analysis Dashboard discover trends in thousands of song lyrics. Identify trends in music or how music differes from generes, years or albums. Find common words such as which day of the week gets sung the most. Many different types of analysis can also be done. Click to find out.
                </div>
                <a href="/Dashboard.php">
                    <button class="bg-blue-500">
                        Goto Song Analysis Dasdhboard
                    </button>
                </a>
            </div>

            <!-- Have a picturte of data??? -->
            <div class="grid grid-rows-3 grid-cols-1">
                <div class="text-2xl mt-[2%]">Explore the Data</div>
                <div class="mx-[4%]">
                    Uncover the songs that make up the data. Find if your favoritie song is included by searing for them. If not it can be easily added with a click of a button. Also, the data can be sorted by year, artist or song name. Once, added it will be saved for good.
                </div>
                <a href="www.google.com">
                    <button class="bg-blue-500">
                        Goto Explore the Data
                    </button>
                </a>
            </div>

        </div>







<!--
Ideas


- How the project works

Footer
- About me
- Motivation for project
- My favorites
- How it works




-->

    <script>
        var counter = 0;
        
        window.onload=function()
        {
            RunTimerOne();
            RunTimerTwo();
        }

        function InsertObject()
        {
            // Gets the element.
            var getSingabout = document.getElementById("singabout");

            var randomArray = ["cars","Ford","Chevy","gender","girls","boys"];
            var arraySize = randomArray.length;

            var randomNumber = Math.floor(Math.random() * arraySize);

            getSingabout.textContent =  "singing about " + randomArray[randomNumber];
         
        }

        function RunTimerOne()
        {
            setInterval(FindFunction, 15000);
        }

        function RunTimerTwo()
        {
            setInterval(InsertObject,20000)
        }

        function FindFunction()
        {
            counter = counter + 1
            console.debug("The value of counter: " + counter)
            

            // Gets the needed elements.
            var getBackgroundGradient = document.getElementById("backgroundGradient");
            var getBackgroundQuote = document.getElementById("backgroundQuote");
            var getBackgroundButton = document.getElementById("backgroundButton");
            var getBackgroundPhoto = document.getElementById("backgroundPhoto");

            if(counter % 4 == 0)
            {
                ChangeTopBackgroundOne(getBackgroundGradient, getBackgroundQuote, getBackgroundButton, getBackgroundPhoto)
            }
            else if(counter % 4 == 1)
            {
                ChangeTopBackgroundTwo(getBackgroundGradient, getBackgroundQuote, getBackgroundButton, getBackgroundPhoto)
            }
            else if(counter % 4 == 2)
            {
                ChangeTopBackgroundThree(getBackgroundGradient, getBackgroundQuote, getBackgroundButton, getBackgroundPhoto)
            }
            else if(counter % 4 == 3)
            {
                ChangeTopBackgroundFour(getBackgroundGradient, getBackgroundQuote, getBackgroundButton, getBackgroundPhoto)
            }
            return counter;
        }

        function ChangeTopBackgroundOne(getBackgroundGradient, getBackgroundQuote, getBackgroundButton, getBackgroundPhoto)
        {
            getBackgroundGradient.classList.toggle('from-indigo-500');
            getBackgroundGradient.classList.toggle('to-green-300');

            getBackgroundPhoto.src = "Pictures/Bono.jpeg";

            getBackgroundButton.src = "https://open.spotify.com/artist/51Blml2LZPmy7TTiAg47vQ";


            getBackgroundQuote.textContent = "Music can change the world because it can change people. - Bono"
        }

        function ChangeTopBackgroundTwo(getBackgroundGradient, getBackgroundQuote, getBackgroundButton, getBackgroundPhoto)
        {
            getBackgroundGradient.classList.toggle('from-orange-700');
            getBackgroundGradient.classList.toggle('to-cyan-400');

            getBackgroundPhoto.src = "Pictures/EdSheeran.jpg";

            //getBackgroundQuote.classList.toggle('text-white')

            getBackgroundButton.src = "https://open.spotify.com/artist/6eUKZXaKkcviH0Ku9w2n3V";

            getBackgroundQuote.textContent = "Music is a powerful tool in galvanizing people around an issue. There's no better way to get your point across than to put it in a beautiful song. - Ed Sheeran"


        }

        function ChangeTopBackgroundThree(getBackgroundGradient, getBackgroundQuote, getBackgroundButton, getBackgroundPhoto)
        {
            getBackgroundQuote.textContent = "Of emotions, of love, of breakup, of love and hate and death and dying, mama, apple pie, and the whole thing. It covers a lot of terriory, country music does. - Johnny Cash"

            getBackgroundPhoto.src = "Pictures/JohnnyCash.jpeg";


            
            getBackgroundButton.src = "https://open.spotify.com/artist/6kACVPfCOnqzgfEF5ryl0x";


            getBackgroundGradient.classList.toggle('from-amber-300');
            getBackgroundGradient.classList.toggle('to-teal-200');


        }

        function ChangeTopBackgroundFour(getBackgroundGradient, getBackgroundQuote, getBackgroundButton, getBackgroundPhoto)
        {
            getBackgroundQuote.textContent = "Music is the explosive expression of humanity. It's something we are all touched by. No matter what culture we're from, everyone loves music. - Billy Joel "

            getBackgroundPhoto.src = "Pictures/BillyJoel.jpg";

            getBackgroundButton.src = "https://open.spotify.com/artist/6zFYqv1mOsgBRQbae3JJ9e";

            getBackgroundGradient.classList.toggle('from-indgio-500');
            getBackgroundGradient.classList.toggle('to-blue-500');
        }

  
      
    </script>




 


        </div>

        <div>

        </div>

    </body>
</html>