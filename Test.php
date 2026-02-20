<?php
    function FindMaxSongSpaces()
    {
        $db = new DatabaseConnection("localhost", "root", "Password@MySQL", "songDatabase");
        $conn = $db->connect();

        $sql = "select MAX( LENGTH(lyrics) - LENGTH(replace(lyrics, ' ', '')) - 1) as 'max' from songs_data;";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $conn->close();

        return strval($row['max']);
    }

?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src='https://cdn.plot.ly/plotly-3.1.0.min.js'></script>
    <link rel="shortcut icon" href="#">


    <title>Dashboard</title>
</head>

<body class="bg-slate-600">

    <!-- Master grid -->
    <div class="grid grid-cols-10 h-full">

   

        <!-- Left with Graphs and the Editor -->
        <div class="grid grid-cols-1 grid-rows-2 bg-slate-600 h-[40%] col-span-2">

            <!-- Graphs -->
            <div class="grid grid-rows-7 grid-cols-3 gap-2 p-[2%] overflow-y-auto col-span-2">
                <div class="col-span-3 text-2xl pb-[5%]">Visualizations</div>

                <div id="bar" onclick="ShowHideDiv(this.id)"> <img class="aspect-square h-full w-full" src="Pictures/barChart.svg"> </div>

                <div id="hbar" onclick="ShowHideDiv(this.id)"> <img class="aspect-square h-full w-full" src="Pictures/barHor.svg"></div>

                <div id="table" onclick="ShowHideDiv(this.id)"> <img class="aspect-square h-full w-full" src="Pictures/table.svg"></div>

                <div> <img class="aspect-square h-full w-full" src="Pictures/line.svg"></div>

                <div><img class="aspect-square h-full w-full" src="Pictures/scatter.svg"> </div>

                <div> <img class="aspect-square h-full w-full" src="Pictures/map.svg"></div>

                <div id="stack" onclick="ShowHideDiv(this.id)"><img class="aspect-square h-full w-full" src="Pictures/stackedChart.svg"></div>

                <div id="group" onclick="ShowHideDiv(this.id)"> <img class="aspect-square h-full w-full" src="Pictures/groupBarChart.svg"></div>

                <div id="hStack" onclick="ShowHideDiv(this.id)"><img class="aspect-square h-full w-full" src="Pictures/groupBarHor.svg"></div>

                <div id="hGroup" onclick="ShowHideDiv(this.id)"><img class="aspect-square h-full w-full" src="Pictures/heatmap.svg"></div>

                <div> <img class="aspect-square h-full w-full" src="Pictures/funnel.svg"></div>

                <div><img class="aspect-square h-full w-full" src="Pictures/histogram.svg"></div>


            </div>



            <!-- Editor -->
            <div class="bg-slate-600 col-span-2">
                <div style="display:non;" class="grid grid-cols-2 gap-2">
                    <div class="col-span-2 text-2xl px-[2%]">Editor</div>

                    <div class="px-[5%] py-[2%] gap-2">
                        <div>Change Title</div>
                        <div><input type="text" id="changeTitle" oninput="UpdateGraph()"></div>

                        <div>y-axes label</div>
                        <div> <input type="text" id="changeY" oninput="UpdateGraph()"></div>

                        <div>x-axex label</div>
                        <div><input type="text" id="changeX" oninput="UpdateGraph()"></div>

                        <div>Change color</div>
                        <div><input type="color" value="#3A72AB" id="changeColor" oninput="UpdateGraph()"></div>
                    

                        <div><button id="delGraph" onclick="DeleteDashboard()" class="bg-red-600 p-[2%]">Clear Dashboard</button></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- The Dashboard in the middle -->
        <div class="bg-slate-500 col-span-6">
            
            <div class="grid grid-cols-10">
                <div class="col-start-3 col-end-8 mt-[5%] h-[500px] w-[500px]" id="graphs"></div>
                <div class="col-start-3 col-end-8 mt-[5%]" id="OUTPUT"></div>
            </div>

        </div>

        <div class="bg-slate-600 col-span-2">
            <!-- Data Selection on the right -->
            <div class="grid grid-cols-2">
                <div class="col-span-2 text-2xl p-[2%] ">Data Selection</div>
                <div style="display:non;" id="barDiv">

                    <details name="displayOptions" id="object" class="p-[3%]" onchange="ActiveDetails(id)" open>
                        <summary>Object</summary>

                        <div class="grid grid-rows-2 grid-cols-1 mx-[8%] mb-[5%]">

                            <div>Band: </div>
                            <div class="input-wrapper">
                                <input class="rounded-xl bg-slate-700 text-neutral-50 border-black pl-[2%]" id="sBar" type="text" oninput="SearchArtist(id)" autocomplete="off">
                                <button id="xSearchbar" onClick="ClearSearchInput()" class="text-rose-400">X</button>

                               


                                <input class="rounded-xl bg-gray-700 text-neutral-50 border-black pl-[2%]" style="display:none;" id="sBarTwo" type="text" oninput="SearchArtist(id)" autocomplete="off">
                                <button id="xSearchbar" onClick="ClearSearchInput()" class="text-rose-400">X</button>
                                
                     

                                <label>
                                    <input type="checkbox" name="allBands" id="allBands" onchange="DisableSearchBar()"> Search All
                                </label>

                            </div>
                            <div id="searchResults1"></div>

                            <div id="TEST" style="display:none;">TEST</div>
                  

                            <div id="display2" class="align-middle"></div>


                            <div id="display1" class="align-middle">Topic:</div>
                            <select class="max-h-[60%] bg-gray-700 text-slate-50 rounded-m flex justify-center items-center mt-[7%]" id="dropdown">
                                <option value="gender">Gender</option>
                                <option value="direction">Direction</option>
                                <option value="response">Response</option>
                                <option value="solar">Solar</option>
                                <option value="day">Day</option>
                                <option value="month">Month</option>
                                <option value="drinks">Drinks</option>
                                <option value="cars">Cars</option>
                                <option value="relationship">Relationships</option>

                            </select>
                        </div>

                    </details>

                    <details name="displayOptions" id="nWord" class="p-[3%]" onchange="ActiveDetails(id)">
                        <summary>N<sup>th</sup> word</summary>

                        <div>nth word and stuff</div>

                    </details>

                    <div class="flex justify-center col-span-2 pt-[15%]">
                        <button class="bg-emerald-600 p-[2%]" onclick="DisplayData()">Analysize Data</button>
                    </div>
                </div>


            </div>

        </div>


    </div>

</body>

<script>
    // The golbal variables.
    let xData;
    let yData;
    let graphType;
    let activeArtistSearch;
    let searchBefore = false;
    let currentValue;

    // TODO:
    // Have pre-defined windows and just bring that one up. Certain type of graphs only make sense for some data.

    /*
     * Displays the corresponding options for the user selected graph. Not all options are possiable for graphs. For an example
     * only one artist can be displayed for single bar chart when a group bar chart brings up multiple artist.
     */
    function ShowHideDiv(id) {
        graphType = id;

        var secondSearchBar = document.getElementById('sBarTwo');

        // Need another search box if it is a group or stack graph
        if (graphType == 'group' || graphType == 'stack' || graphType == 'hStack' || graphType == 'hGroup') {
            secondSearchBar.style.display = 'block';
        } else {
            secondSearchBar.style.diplay = 'none';
        }

        // Displays the different graph if the graphs have been displayed before.
        if(searchBefore == true)
        {
            DisplayData();
        }

        var divID = id + "Div"
        //var test = document.getElementById(divID);
        //test.style.display = 'block';

        // Just displays the graph which is empty.

        var output = document.getElementById("OUTPUT");

        // Remove the table if it is there
        var outputTable = document.getElementById("outputTable")

        if(outputTable != null)
        {
            outputTable.remove();
        }

        CreateEmptyGraph();
    }

    /*
     * Clears the input of the search box.
     */
    function ClearSearchInput() {
        var searchBox = document.getElementById("sBar");
        var allBandsCheckbox =document.getElementById("allBands");

        // Does not clear if the all bands check box is on
        if(allBandsCheckbox.checked == false)
        {
            searchBox.value = "";        
        }

        // Remove all the other possiable possiable searches including the one that came up.
        for (var rows = 0; rows <= 4; rows++) {

            var tempDivSearchBox = document.getElementById(rows);

            if (tempDivSearchBox != null) {
                tempDivSearchBox.remove();
            }

        }
    }


    // Finds the artist the user is looking for in a search box.
    function SearchArtist(id) {
        var option = "0"

        // The first bar chart. Just used if it is not a group.
        if (id == "sBar") {
            activeArtistSearch = "one"
            var query = document.getElementById("sBar").value;

            const xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById('searchResults1').innerHTML = this.responseText;
                }
            };

            xhttp.open("POST", "http://localhost:3000/DashboardServer.php?", true)
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhttp.send("option=" + encodeURIComponent(option) + "&search=" + encodeURIComponent(query));
        }

        // Only shows for group bar charts.
        else if (id == "sBarTwo") {
            activeArtistSearch = "two"

            var query = document.getElementById("sBarTwo").value;

            const xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById('display2').innerHTML = this.responseText;
                }
            };

            xhttp.open("POST", "http://localhost:3000/DashboardServer.php?", true)
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhttp.send("option=" + encodeURIComponent(option) + "&search=" + encodeURIComponent(query));
        }
    }

    /*
     * A button click that takes the user selected data and display the data in either a graph or a table 
     */
    function DisplayData() 
    {
        searchBefore = true;
         
        var bandOne = document.getElementById("sBar").value;
        var bandTwo = document.getElementById("sBarTwo").value;
        var object = document.getElementById("dropdown").value;

        // Gets the value of the checkbox to see if the user wants to select all the bands 
        if(document.getElementById("allBands").checked)
        {
            bandOne = "all"
        }

        // Check if the required data is there such as an artist and a graph.
        if(typeof graphType === "undefined")
        {
            alert("Select a graph or table");
            return;
        }
        else if(bandOne == "")
        {
            alert("Select an artist/band to search for");
            return;
        }

        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                document.getElementById('TEST').innerHTML = this.responseText;
            }
        };

        xhttp.open("POST", "http://localhost:3000/DashboardServer.php", true)
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhttp.send("graph=" + encodeURIComponent(graphType) + "&band=" + encodeURIComponent(bandOne) + "&object=" + encodeURIComponent(object) + "&bandTwo=" + encodeURIComponent(bandTwo));

        CreateData();
    }

    // Adds the data to the graph from a div.
    function CreateData() 
    {
        function CallLoadingGraphs()
        {
            Plotly.purge('graphs');
            
            // Create the child
            const newLoading = document.createElement("div");
            newLoading.id = "LoadingBar";
            
            newLoading.textContent = "LOADING DATA...";

            var graphLoading = document.getElementById("graphs");

            graphLoading.appendChild(newLoading);
        }

        function CallDeleteLoadingGraphs()
        {
            // Kill the child as long as it is not a table
            var newLoading = document.getElementById("LoadingBar");

            if(newLoading)
            {
                newLoading.remove();
            }
        }

        CallLoadingGraphs()

        fetch('DashboardServer.php')
            .then(res => res.json())
            .then(data => {
                var toString = document.getElementById("TEST").innerHTML;

                // Delete the old table text 
                if(graphType != "table")
                {
                    var tableTitleText = document.getElementById("graphs");

                    if(tableTitleText)
                    {
                        tableTitleText.innerHTML = "";
                    }
                }

                // Bar chart - horizontal or vertical.
                if (graphType == "hbar" || graphType == "bar") {

              
                    // Bar chart
                    if (graphType == "bar") {

                        const dataObj = JSON.parse(toString);

                        xData = dataObj.x;
                        yData = dataObj.y.map(Number);

                        var layout = {
                        title: 'My Plotly Chart',
                        paper_bgcolor: '#1e293b', 
                        plot_bgcolor: '#334155',
                        font:{
                            color:'white'
                        }
                        };

                        Plotly.newPlot('graphs', [{
                            x: xData,
                            y: yData,
                            type: 'bar',
                            orientation: 'v'
                        }],layout);
                        UpdateGraph();
                    }

                    // Horizontal bar chart
                    else if (graphType == "hbar") {
                        const dataObj = JSON.parse(toString);

                        xData = dataObj.x;
                        yData = dataObj.y.map(Number);


                        var layout = {
                        title: 'My Plotly Chart',
                        paper_bgcolor: '#1e293b', 
                        plot_bgcolor: '#334155',
                        font:{
                            color:'white'
                        }
                        };

                        Plotly.newPlot('graphs', [{
                            x: yData,
                            y: xData,
                            type: 'bar',
                            orientation: 'h'
                        }],layout);
                        UpdateGraph();


                    }
                  

                } else if (graphType == 'group' || graphType == 'stack' || graphType == 'hStack' || graphType == 'hGroup') {
                    // Work to seperate the x,y,z data

                    const dataObj = JSON.parse(toString);

                    objectArray = dataObj.x;
                    valueArray = dataObj.y.map(Number);
                    artistArray = dataObj.z;

                    var xArrayOne = [];
                    var xArrayTwo = [];

                    var yArrayOne = [];
                    var yArrayTwo = [];

                    var artistArrayOne = [];
                    var artistArrayTwo = [];

                    var difValue = 0;
                    var savedArtist = "";
                    var currentArtist = "";
                    for (var i = 0; i < artistArray.length; i++) {
                        // The currentArtist
                        var currentArtist = artistArray[i];


                        // Gets the first artist in the array
                        if (artistArray.length != 0) // Checks that it is not empty
                        {
                            savedArtist = artistArray[0];
                        }

                        // The first artist values.
                        if (currentArtist == savedArtist) {
                            xArrayOne.push(objectArray[i]);
                            yArrayOne.push(valueArray[i]);
                            artistArrayOne.push(artistArray[i]);
                        }

                        // The second artist values.
                        else if (currentArtist != savedArtist) {
                            xArrayTwo.push(objectArray[i]);
                            yArrayTwo.push(valueArray[i]);
                            artistArrayTwo.push(artistArray[i]);
                        }
                    }



                    // Making the group graph
                    var ort = 'v';
                    if (graphType == 'hGroup' || graphType == 'hStack') {
                        ort = 'h';

                        // Swaps the variables around
                        var tempArray;
                        tempArray = xArrayOne.slice(0);
                        xArrayOne = yArrayOne.slice(0);
                        yArrayOne = tempArray.slice(0);

                        tempArray = xArrayTwo.slice(0);
                        xArrayTwo = yArrayTwo.slice(0);
                        yArrayTwo = tempArray.slice(0);
                    }
                    var trace1 = {
                        x: xArrayOne,
                        y: yArrayOne,
                        name: savedArtist,
                        orientation: ort,
                        type: 'bar'
                    };
                    var trace2 = {
                        x: xArrayTwo,
                        y: yArrayTwo,
                        name: currentArtist,
                        orientation: ort,
                        type: 'bar'
                    };


                    if (graphType == 'group' || graphType == 'hGroup') {
                        var layout = {
                            barmode: 'group'
                        };
                    } else {
                        var layout = {
                            barmode: 'stack'
                        };

                    }

                    var data = [trace1, trace2];

                    var layout = {
                    title: 'My Plotly Chart',
                    paper_bgcolor: '#1e293b', 
                    plot_bgcolor: '#334155',
                    font:{
                        color:'white'
                    }
                    };

                    // No songs returned then does not show any graphs.
                    // For group graphs
                    var output = document.getElementById('output');
                    if (graphType == 'hGroup' || graphType == 'hStack') {
                        if(xArrayOne.length == 0 && yArrayOne.length == 0)
                        {
                            if(output != null)
                            {
                                Plotly.purge('graphs');
                                output.innerHTML = "No songs Found"
                            }
                        }
                        else
                        {
                            Plotly.newPlot('graphs', data, layout);
                            UpdateGraph();
                        }
                    }
                    // For single graphs
                    else if(graphType == 'bar' || graphType =='hbar')
                    {
                        if(yData.length == 0 && output != null)
                        {
                            Plotly.purge('graphs');
                            output.innerHTML = "No songs found";
                        }
                        else
                        {
                            Plotly.newPlot('graphs', data, layout);
                            UpdateGraph();
                        }
                    }
                }
                else if(graphType == 'table')
                {
                    // Delete old graphs so the table does not show also
                    Plotly.purge('graphs');

                    var outputDiv = document.getElementById("graphs");
                    var objectValue =document.getElementById('dropdown');

                    const dataObj = JSON.parse(toString);

                    objectArray = dataObj.x;
                    valueArray = dataObj.y.map(Number);
                    artistArray = dataObj.z;

                    var xArray = [];
                    var yArray = [];

                    for(var i = 0; i < artistArray.length; i++)
                    {
                        xArray.push(objectArray[i]);
                        yArray.push(valueArray[i]);
                    }

                    var tableOutput = "<caption>" + artistArray[0] + " singing about "  + objectValue.value + "</caption> <table id='outputTable'> <th>" + objectValue.value + "</th> <th>Count</th><tr>";

                    for(var i = 0; i < xArray.length; i++)
                    {
                        tableOutput = tableOutput + "<tr><td>" + xArray[i] + "</td>" + "<td>" + yArray[i] + "</td>" + "</tr>"
                    }

                    tableOutput = tableOutput + "</tr> </table>"

                    outputDiv.innerHTML = tableOutput;   
                }

                 CallDeleteLoadingGraphs();
            })
            .catch(err => console.error(err));
    }


    /*
     * Changes a gobal variable that gets the current open details tab. This is used to make sure that everything is filled out. For an example if the Object title is open and if they are missing the band to search for it gives the user a warning.
     */
    function ActiveDetails(id) {
        var y = 5;
    }

    function DisableSearchBar()
    {
        // The search bar value and the check box
        var getSearchBar = document.getElementById('sBar');
        var allBandsCheckbox = document.getElementById('allBands');
        var xSearch = document.getElementById('xSearchbar');

        // Want to serach for all bands
        if(allBandsCheckbox.checked == true)
        {
            currentValue = getSearchBar.value;
            getSearchBar.value = "All Bands"
            getSearchBar.disabled = true;

            // Disable the clear button from search input
            xSearch.style.display="none";

            // Clears the serach artist
            ClearSearchInput();
        }

        else
        {
            // Prevents a problem if the user trys to serach something without searching before
           if(currentValue == null)
            {
                currentValue = getSearchBar.value
                currentValue.value = ""
            }

            // Saves what the user was looking for before they selected all bands
            else
                getSearchBar.value = currentValue;
                
            SearchArtist("sBar");

            getSearchBar.disabled = false;
        }

        
    }      

    // Gets the button thtat is clicked to add it to the search bar. Therefore, the user does not need to type the whole artist name out.
    function ShowArtistButtonClick(divId) {
        // Gets the div id.
        getDivID = divId;

        // The input box. There can be multiple search boxes (group graph) this decides if the user clicks a drop down then which one
        // to put it in.
        if (activeArtistSearch == "one") {
            var searchBox = document.getElementById("sBar");
        } else if (activeArtistSearch == "two") {
            var searchBox = document.getElementById("sBarTwo");
        }

        // Get the search bar value
        var searchBarValue = document.getElementById(getDivID).textContent;

        // Adds the clicked value to the search box
        searchBox.value = searchBarValue;

        // Remove all the other possiable possiable searches including the one that came up.
        for (var rows = 0; rows <= 4; rows++) {

            var tempDivSearchBox = document.getElementById(rows);

            if (tempDivSearchBox != null) {
                tempDivSearchBox.remove();
            }

        }

    }

    // Deletes the graphs and the tables that the user had mad
    function DeleteDashboard()
    {
        // Gets the tables
        var tableChild = document.getElementById("outputTable")
        var tableTitleText = document.getElementById("graphs")

        // Allows the user to confirm their choice before they delete their graph
        var choice = confirm("Are you sure you want to clear your current Dashboard")
  
        if(choice == true)
        {
            // Removes the graph not matter if it is a table or not
            Plotly.purge('graphs');

            // Checks if the table before removing it
            if(tableChild)
            {
                tableChild.remove();
                tableTitleText.innerHTML = "";
            }
        }
    }

    // Creates an empty graph. This is when the user clicks the selected graph. It just shows it. Values
    // are not insertered into yet.
    function CreateEmptyGraph() {
        // Creates a table.
        if (graphType == 'table') {
            
            var graph = document.getElementById("graphs");
            var tableString = "<table id ='outputTable'> <tr><th>Header 1</th><th>Header 2</th><tr><td>Row 1</td><td>Row 1</td></tr></table>";
            graph.innerHTML = tableString;

        } else {
            // Used to vertical bar charts
            var ori = "h";

            if (graphType == "hbar") {
                ori = "h";
            }

            // Creates a bar chart - will have to change this later on.
            var data = [{
                x: [''],
                y: ['0'],
                type: 'bar',
                orientation: ori
            }];

            var layout = {
            title: 'My Plotly Chart',
            paper_bgcolor: '#1e293b', 
            plot_bgcolor: '#334155',
            font:{
                color:'white'
            }
            };

            Plotly.newPlot('graphs', data, layout);
        }
    }

    // Have the user edit the graph. Such as change the title and color.
    function UpdateGraph() 
    {
        // Gets the values.
        var title = document.getElementById("changeTitle").value;
        var xtitle = document.getElementById("changeX").value;
        var ytitle = document.getElementById("changeY").value;
        var color = document.getElementById("changeColor").value;

        var layout_update = {
            title:{text:title},xaxis:{title:{text:xtitle}},yaxis:{title:{text:ytitle}}
        };

        var data_update = {
            'marker.color':color
        };
        

        Plotly.update("graphs",data_update,layout_update)

    }
</script>

<style>
    div {
        color:whitesmoke;
    }

    .input-wrapper {
        position: relative;
        display: block;
        max-width: 150px;
        min-width: 150px;
    }

    .input-wrapper input {
        width: 100%;
        padding-right: 40px;
        box-sizing: border-box;
    }

    .input-wrapper button {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%); /* center vertically */
        border: none;
        background-color: transparent;
        cursor: pointer;
    }

    input[type=text]
    {
        background-color:#314158;
    }



    table
    {
        border-collapse: collapse;
        margin: 25px 0;
        font-size:0.9em;
        font-family: sans-serif;
        box-shadow: 0 0 20px rgba(0,0,0,0.15);
        color:white;
        background-color:#314158;
        min-width: 200px;
        text-transform: capitalize;
    }

    table th
    {
        font-weight: bold; 
        background-color:rgb(30, 140, 60);    
    }

    table tr:nth-child(even)
    {
        background-color:#50668A;
    }

    table tr:last-of-type
    {
        border-bottom: 2px solid rgb(30,140,60);
    }

    #output
    {
        background-color:white;
        text-align: center;
    }

</style>


</html>