// The golbal variables.
let xData;
let yData;
let graphType;
let activeArtistSearch;
let searchBefore = false;
let currentValue;
let graphSwapArray = [];

// TODO:
// Have pre-defined windows and just bring that one up. Certain type of graphs only make sense for some data.

/*
 * Displays the corresponding options for the user selected graph. Not all options are possiable for graphs. For an example
 * only one artist can be displayed for single bar chart when a group bar chart brings up multiple artist.
 */
function ShowHideDiv(id) {
    graphType = id;

    // Need another search box if it is a group or stack graph
    if (graphType == 'group' || graphType == 'stack' || graphType == 'hStack' || graphType == 'hGroup') {
        document.getElementById("sBarTwo").style.display = 'block';
        document.getElementById("changeColor2").style.display = "block";
    } else {

        document.getElementById("sBarTwo").style.display = "none";
        document.getElementById("changeColor2").style.display = "none";
    }

    // Displays the different graph if the graphs have been displayed before.
    if (searchBefore == true) {
        DisplayData();
    }

    var divID = id + "Div"
    //var test = document.getElementById(divID);
    //test.style.display = 'block';

    // Just displays the graph which is empty.

    var output = document.getElementById("OUTPUT");

    // Remove the table if it is there
    var outputTable = document.getElementById("outputTable")

    if (outputTable != null) {
        outputTable.remove();
    }

    CreateEmptyGraph();
}

/*
 * Clears the input of the search box.
 */
function ClearSearchInput() {
    var searchBox = document.getElementById("sBar");
    var allBandsCheckbox = document.getElementById("allBands");

    // Does not clear if the all bands check box is on
    if (allBandsCheckbox.checked == false) {
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
        xhttp.onreadystatechange = function () {
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
        xhttp.onreadystatechange = function () {
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
function DisplayData() {
    searchBefore = true;

    var bandOne = document.getElementById("sBar").value;
    var bandTwo = document.getElementById("sBarTwo").value;
    var object = document.getElementById("dropdown").value;

    // Gets the value of the checkbox to see if the user wants to select all the bands 
    if (document.getElementById("allBands").checked) {
        bandOne = "all"
    }

    // Check if the required data is there such as an artist and a graph.
    if (typeof graphType === "undefined") {
        alert("Select a graph or table");
        return;
    }
    else if (bandOne == "") {
        alert("Select an artist/band to search for");
        return;
    }

    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
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
function CreateData() {
    function CallLoadingGraphs() {
        Plotly.purge('graphs');

        // Create the child
        const newLoading = document.createElement("div");
        newLoading.id = "LoadingBar";

        newLoading.textContent = "LOADING DATA...";

        var graphLoading = document.getElementById("graphs");

        graphLoading.appendChild(newLoading);
    }

    function CallDeleteLoadingGraphs() {
        // Kill the child as long as it is not a table
        var newLoading = document.getElementById("LoadingBar");

        if (newLoading) {
            newLoading.remove();
        }
    }

    CallLoadingGraphs()

    fetch('DashboardServer.php')
        .then(res => res.json())
        .then(data => {
            var toString = document.getElementById("TEST").innerHTML;

            var color1 = document.getElementById("changeColor").value;
            var color2 = document.getElementById("changeColor2").value;

            // Delete the old table text 
            if (graphType != "table") {
                var tableTitleText = document.getElementById("graphs");

                if (tableTitleText) {
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
                        font: {
                            color: 'white'
                        }
                    };

                    Plotly.newPlot('graphs', [{
                        x: xData,
                        y: yData,
                        type: 'bar',
                        orientation: 'v'
                    }], layout);
                    UpdateGraph(graphType);
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
                        font: {
                            color: 'white'
                        }
                    };

                    Plotly.newPlot('graphs', [{
                        x: yData,
                        y: xData,
                        type: 'bar',
                        orientation: 'h'
                    }], layout);
                    UpdateGraph(graphType);


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
                    type: 'bar',
                    marker: { color: color1 }
                };
                var trace2 = {
                    x: xArrayTwo,
                    y: yArrayTwo,
                    name: currentArtist,
                    orientation: ort,
                    type: 'bar',
                    marker: { color: color2 }
                };


                if (graphType == 'group' || graphType == 'hGroup') {
                    var layout = {
                        barmode: 'group'
                    };
                }
                else if (graphType == 'stack') {
                    var layout = {
                        barmode: 'stack'
                    };

                }

                var data = [trace1, trace2];

                // No songs returned then does not show any graphs.
                // For group graphs
                var output = document.getElementById('output');
                if (graphType == 'hGroup' || graphType == 'hStack' || graphType == 'group' || graphType == 'stack') {
                    if (xArrayOne.length == 0 && yArrayOne.length == 0) {
                        if (output != null) {
                            Plotly.purge('graphs');
                            output.innerHTML = "No songs Found"
                        }
                    }
                    else {
                        Plotly.newPlot('graphs', data, layout);
                        UpdateGraph(graphType);
                    }
                }
                // For single graphs
                else if (graphType == 'bar' || graphType == 'hbar') {
                    if (yData.length == 0 && output != null) {
                        Plotly.purge('graphs');
                        output.innerHTML = "No songs found";
                    }
                    else {
                        Plotly.newPlot('graphs', data, layout);
                        UpdateGraph(graphType);
                    }
                }
            }
            else if (graphType == 'table') {
                // Delete old graphs so the table does not show also
                Plotly.purge('graphs');

                var outputDiv = document.getElementById("graphs");
                var objectValue = document.getElementById('dropdown');

                const dataObj = JSON.parse(toString);

                objectArray = dataObj.x;
                valueArray = dataObj.y.map(Number);
                artistArray = dataObj.z;

                var xArray = [];
                var yArray = [];

                for (var i = 0; i < artistArray.length; i++) {
                    xArray.push(objectArray[i]);
                    yArray.push(valueArray[i]);
                }

                var tableOutput = "<caption>" + artistArray[0] + " singing about " + objectValue.value + "</caption> <table id='outputTable'> <th>" + objectValue.value + "</th> <th>Count</th><tr>";

                for (var i = 0; i < xArray.length; i++) {
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

function DisableSearchBar() {
    // The search bar value and the check box
    var getSearchBar = document.getElementById('sBar');
    var allBandsCheckbox = document.getElementById('allBands');
    var xSearch = document.getElementById('xSearchbar');

    // Want to serach for all bands
    if (allBandsCheckbox.checked == true) {
        currentValue = getSearchBar.value;
        getSearchBar.value = "All Bands"
        getSearchBar.disabled = true;

        // Disable the clear button from search input
        xSearch.style.display = "none";

        // Clears the serach artist
        ClearSearchInput();
    }

    else {
        // Prevents a problem if the user trys to serach something without searching before
        if (currentValue == null) {
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
function DeleteDashboard() {
    // Gets the tables
    var tableChild = document.getElementById("outputTable")
    var tableTitleText = document.getElementById("graphs")

    // Allows the user to confirm their choice before they delete their graph
    var choice = confirm("Are you sure you want to clear your current Dashboard")

    if (choice == true) {
        // Removes the graph not matter if it is a table or not
        Plotly.purge('graphs');

        // Checks if the table before removing it
        if (tableChild) {
            tableChild.remove();
            tableTitleText.innerHTML = "";
        }

        // Reset the values to empty
        document.getElementById("changeTitle").value = "";
        document.getElementById("changeX").value = "";
        document.getElementById("changeY").value = "";
        document.getElementById("changeColor").value = "blue";

        graphSwapArray.length = 0;
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
            font: {
                color: 'white'
            }
        };

        Plotly.newPlot('graphs', data, layout);
    }
}

// Have the user edit the graph. Such as change the title and color. Also, handles if the user switches
// to different types of graphs.
function UpdateGraph(graphType) {
    var graphTitle, graphXTitle, graphYTitle;

    // Gets the values.
    var inputTitle = document.getElementById("changeTitle").value;

    var inputYTitle = document.getElementById("changeY").value;
    var inputXTitle = document.getElementById("changeX").value;

    var inputColor = document.getElementById("changeColor").value;

    var searchBar = document.getElementById("sBar").value;
    var dropdown = document.getElementById("dropdown").value;
    var color1 = document.getElementById("changeColor").value;
    var color2 = document.getElementById("changeColor2").value;

    var oldGraphType = "";
    var currentGraphType = graphType;

    var graphTitle;
    var graphXTitle;
    var graphYTitle;

    // Swap the x-axes labels and the y-axes label if going from a vertical <-> horizontal
    if (graphType !== undefined) // Not undefined then push the graph type
    {
        graphSwapArray.push(currentGraphType);

        // Size larger than 2 we can acess the last element
        var arraySize = graphSwapArray.length;
        if (arraySize >= 2) {
            var lastElement = arraySize - 2;    // Length is +1 and want the prev element
            oldGraphType = graphSwapArray[lastElement];
        }
    }

    graphTitle = searchBar + " Singing About " + dropdown + " Counts";
    graphXTitle = dropdown;
    graphYTitle = "Counts";

    // Do not remove the user's work
    if (graphType == "stack" || graphType == "group" || graphType == "hStack") {
        graphTitle = searchBar + " and " + document.getElementById("sBarTwo").value + " Singing About " + dropdown + " Counts";
    }

    // Allows the user to edit the graph
    if (inputYTitle !== "") {
        graphYTitle = inputYTitle;
    }
    if (inputXTitle !== "") {
        graphXTitle = inputXTitle;
    }
    if (inputTitle !== "") {
        graphTitle = inputTitle;
    }



    // Might want to use a substring and have vGraph and hGraph and just look at the first letter
    if ((oldGraphType.substring(0) == "bar" && currentGraphType == "hbar") || (oldGraphType == "hbar" && currentGraphType == "bar")) {
        var temp = inputXTitle;
        inputXTitle = inputYTitle;
        inputYTitle = temp;

        document.getElementById("changeY").value = inputYTitle;
        document.getElementById("changeX").value = inputXTitle;

        graphXTitle = inputXTitle;
        graphYTitle = inputYTitle;
    }
    else {
        document.getElementById("changeTitle").value = graphTitle;
        document.getElementById("changeX").value = graphXTitle;
        document.getElementById("changeY").value = graphYTitle;
    }

    // Updates the graphs
    var layout_update = {
        title: { text: graphTitle }, xaxis: { title: { text: graphXTitle } }, yaxis: { title: { text: graphYTitle } }
    };


    var data_update = {
        'marker.color': [color1, color2]
    };

    Plotly.update("graphs", data_update, layout_update)

}