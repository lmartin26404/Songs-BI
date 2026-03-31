// The golbal variables.
let xData;
let yData;
let graphType;
let activeArtistSearch;
let searchBefore = false;
let currentValue;
let graphSwapArray = [];
let currentSelected = "";
let figure;


// Values for the graphs
let idFigCounter = 0;
xPos = 150;
yPos = 150;
height = 200;
width = 200;
xLabel = "X Label";
yLabel = "Y Label";
color = "blue";

class Figure
{
    constructor(id, xPos, yPos, height, width, type, title, xLabel, yLabel, color)
    {
        this.id = id;
        this.xPos = xPos;
        this.yPos = yPos;
        this.height = height;
        this.width = width;
        this.type = type;
        this.title = title;
        this.xLabel = xLabel;
        this.yLabel = yLabel;
        this.color = color;
        // this.xData of the array
        // this.yData of the array

    }

    // Add the needed functions of the graphs
}

// Checks that the user clicks a figure
document.addEventListener('click',function(event)
{

    // Make this into a function
    // Call a function that then populates the EDITOR view with the data based off the Class Figure

    // Checks if the user clicked on a valid figure such as a graph or a table
    let isFig = CheckValidFigure();

    if(isFig == true)
    {
        UpdateEditorValues();
    }
});





function UpdateEditorValues()
{
    // Loop over all the Figure until the id matches the current one
  
    document.getElementById("xPos").value = figure.xPos;
}

function CheckValidFigure()
{
    const clickedFigure = event.target.id;

    

    currentSelected = "" + clickedFigure;

    var figPre = "";
    var figPos = "";
    
    if(clickedFigure.length >= 3)
    {
        figPre = clickedFigure.substring(0,2);  // Id
        figPos = clickedFigure.substring(3);    // number
    }

    // The user clicked a Fig
    if(figPre == "id")
    {
        var curFig = event.target.id;
        currentSelected = curFig;

        document.getElementById("CurrentGraph").innerHTML = currentSelected;

        return true;
    }

    else
    {
        return false;
    }
}

// TODO:
// Have pre-defined windows and just bring that one up. Certain type of graphs only make sense for some data.


/*
 * Displays the corresponding options for the user selected graph. Not all options are possiable for graphs. For an example
 * only one artist can be displayed for single bar chart when a group bar chart brings up multiple artist.
 */
function CreateFigure(id) {
    graphType = id;

    // Everything that makes up a graph
    id = "id" + idFigCounter;
    var xPos = "150px";
    var yPos = "350px";
    var height = "400px";
    var width = "400px";
    type = "bar";            
    title = idFigCounter + " Title";
    var xLabel = idFigCounter + " X LABEL";
    var yLabel = idFigCounter + " Y LABEL";
    color = "blue";          

    figure = new Figure(id, xPos, yPos, height, width, type, title, xLabel, yLabel, color);
    
    // Creates a new Figure which is a div that holds the graph/table
    var dashboard = document.getElementById("dashboard");

    const childFigure = document.createElement("div");

    childFigure.draggable = "true";


   
    childFigure.ondragstart = function(){};

    childFigure.id = figure.id;

    childFigure.style.background = figure.color;
    childFigure.style.height = figure.height;
    childFigure.style.width = figure.width;

    //childFigure.style.position = "fixed";
    childFigure.style.top = figure.xPos;
    childFigure.style.left =  figure.yPos;
    childFigure.classList.add('testGraph');

    document.getElementById("CurrentGraph").value = document.getElementById("CurrentGraph").value + figure.id + " is ";


    dashboard.append(childFigure);

    // Creates a bar chart - will have to change this later on.
    var data = [{
        x: ['40','20'],
        y: ['39','50'],
    type: figure.type,
    orientation: 'v'
    }];

    var layout = {
        title: figure.title,
        paper_bgcolor: '#1e293b',
        plot_bgcolor: '#334155',
        font: {
           color: figure.color
        }
    };

    Plotly.newPlot(figure.id, data, layout);


    idFigCounter++;

    // Fix this at some point
    childFigure.ondragstart = function() {StartDrag();};
    childFigure.ondragend = function() {EndDrag();};


/*
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

    // Remove the table if it is there
    var outputTable = document.getElementById("outputTable")

    if (outputTable != null) {
        outputTable.remove();
    }

    

    CreateEmptyGraph();*/
}

// Bad gloabl variables
let xStart = 0; let yStart = 0; let xEnd = 0; let yEnd = 0;

function StartDrag()
{
    let xStart = 0; let yStart = 0; let xEnd = 0; let yEnd = 0;
    

    let isFig = CheckValidFigure();

    // Get out of the current function not a fig so don't move it
    if(isFig == false)
    {
        return;
    }

    // get the selected fig
    const clickedFigure = event.target.id;

    var element = document.getElementById(clickedFigure);
    
    const rect = element.getBoundingClientRect();

    // Find the start of the mouse
    xStart = event.clientX;
    yStart = event.clientY;

    var rTopBefore = rect.top;
    var rBottomBefore = rect.bottom;
    var rLeftBefore = rect.left;
    var rRightBefore = rect.right;
}

function EndDrag()
{    
    let isFig = CheckValidFigure();

    // Get out of the current function not a fig so don't move it
    if(isFig == false)
    {
        return;
    }

    // get the selected fig
    const clickedFigure = event.target.id;

    var element = document.getElementById(clickedFigure);

    const rect = element.getBoundingClientRect();

    // Find the end of the mouse
    xEnd = event.clientX;
    yEnd = event.clientY;

    var rTopAfter = rect.top;
    var rBottomAfter = rect.bottom;
    var rLeftAfter = rect.left;
    var rRightAfter = rect.right;

    FindDragDistance(rTopAfter, rBottomAfter, rLeftAfter, rRightAfter);
   
}

function FindDragDistance(rTopAfter, rBottomAfter, rLeftAfter, rRightAfter)
{
    var deltaX = Math.abs(xEnd - xStart);
    var deltaY = Math.abs(yEnd - yStart);

    // Sets the Fig to the new location
    const clickedFigure = event.target.id;

    var element = document.getElementById(clickedFigure);
    const rect = element.getBoundingClientRect();

    element.style.position = "absolute";

    element.style.top = deltaY + 'px';
    element.style.bottom = (deltaY + (rect.top - rect.bottom)) + 'px';
    element.style.left = deltaX + 'px';
    element.style.right = (deltaX + (rect.right - rect.left)) + 'px';
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
        Plotly.purge(figure.id);

        // Create the child
        const newLoading = document.createElement("div");
        newLoading.id = "LoadingBar";

        newLoading.textContent = "LOADING DATA...";

        var graphLoading = document.getElementById(figure.id);

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
                var tableTitleText = document.getElementById(figure.id);

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

                    Plotly.newPlot(figure.id, [{
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

                    Plotly.newPlot(figure.id, [{
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
                            Plotly.purge(figure.id);
                            output.innerHTML = "No songs Found"
                        }
                    }
                    else {
                        Plotly.newPlot(figure.id, data, layout);
                        UpdateGraph(graphType);
                    }
                }
                // For single graphs
                else if (graphType == 'bar' || graphType == 'hbar') {
                    if (yData.length == 0 && output != null) {
                        Plotly.purge(figure.id);
                        output.innerHTML = "No songs found";
                    }
                    else {
                        Plotly.newPlot(figure.id, data, layout);
                        UpdateGraph(graphType);
                    }
                }
            }
            else if (graphType == 'table') {
                // Delete old graphs so the table does not show also
                Plotly.purge(figure.id);

                var outputDiv = document.getElementById(figure.id);
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
    var tableTitleText = document.getElementById(figure.id)

    // Allows the user to confirm their choice before they delete their graph
    var choice = confirm("Are you sure you want to clear your current Dashboard")

    if (choice == true) {
        // Removes the graph not matter if it is a table or not
        Plotly.purge(figure.id);

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

        var graph = document.getElementById(figure.id);
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

        Plotly.newPlot(figure.id, data, layout);
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

    Plotly.update(figure.id, data_update, layout_update)

}