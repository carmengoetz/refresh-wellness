<?php
$testHeader = "";
$hasTestHeader = false;

if (!empty($_REQUEST['test']))
{
    $hasTestHeader = true;
    $testHeader = $_REQUEST['test'];
}
//https://getbootstrap.com/docs/3.3/css/ for making the page responsive
?>

<!DOCTYPE html>
<html>
<head>
    <title>Conversations</title>
    <link type="text/css" rel="stylesheet" href="../../../styles/navbar.css" />
    <link type="text/css" rel="stylesheet" href="../../../styles/view_messages.css" />
    <link type="text/css" rel="stylesheet" href="../../../styles/general.css" />
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.2/knockout-min.js"></script>
    <script type="text/javascript">
        var testHeader = <?php echo empty($testHeader) ? '""' : '"' . $testHeader . '"'  ?>;
        var hasTestHeader = <?php echo empty($hasTestHeader) ? 'false' : 'true'?>;
    </script>
    <script type="text/javascript">
        //Variable to determine if nav arrows need to be shown
        var totalPages = 0;

        //Variable to decide which page to show
        var pageNum = 1;

        //Make AJAX call to wellness controller here to get list of nearby wellness professionals
        var viewModel = {
            conversations: ko.observableArray(),
            messages: ko.observableArray(),
            currentUser: ko.observableArray(),

            //recreate the function to work with the json passed in above
            getConvos: function () {
                var headerName = "test";
                var headerVal = "";
                if (hasTestHeader) {
                    headerVal = testHeader;
                }

                $.ajax(
                    {
                        url: '../conversation/view/'+ pageNum,
                        type: 'GET',
                        dataType: 'json',
                        //Pass in page number we want to see
                        beforeSend: function (xhr) { xhr.setRequestHeader(headerName, headerVal); },
                        success: function (jsonResponse) {
                            //Deal with the response
                            if (jsonResponse.status == "success") {
                                viewModel.conversations.removeAll();
                                for (var i = (20 * pageNum) - 20; i < (20 * pageNum) && jsonResponse.data.length > i ; i++)
                                {
                                    if (jsonResponse.data[i].lastMessage.isRead == 0)
                                    {
                                        jsonResponse.data[i].lastMessage.isRead = 5;
                                        //https://cerkit.com/2016/02/05/dynamically-adding-icons-to-a-boostrap-nav-menu-in-ghost/
                                    }

                                    //take off the seconds so that the time displays only as hours and minutes
                                    jsonResponse.data[i].lastMessage.time = jsonResponse.data[i].lastMessage.time.substring(0, jsonResponse.data[i].lastMessage.time.length - 3);

                                    viewModel.conversations.push(jsonResponse.data[i]);
                                }
                            }
                            //status is failure
                            else {
                                $('#jsonError').html(jsonResponse.message);
                                $(".errorMsg").css("display", "inline");
                            }

                            totalPages = Math.ceil(jsonResponse.data.length / 20);
                            //Check for nav arrows
                            showNavArrows();
                        }
                    })

            },

            openConvo: function (conversations) {
                sessionStorage.setItem("userID", conversations.userID);
                open('../message/viewmessages', '_self');
            }
        };

        //Add method to determine if navigation arrows are needed
        function showNavArrows()
        {
            //Show/hide next nav link
            if (pageNum < totalPages)
            {
                $("#nextPage").show();
            }
            else
            {
                $("#nextPage").hide();
            }

            //Show/hide prev nav link
            if (pageNum > 1)
            {
                $("#prevPage").show();
            }
            else
            {
                $("#prevPage").hide();
            }

        }

        $(function () {
        ko.applyBindings(viewModel);
        viewModel.getConvos();

            $("#nextPage").hide();
            $("#prevPage").hide();

            //Click handler for next
            $("#nextPage").click(function () {
                pageNum++;
                if (pageNum > totalPages)
                {
                    pageNum = totalPages;
                }

                viewModel.getConvos();
            });

            //Click handler for prev
            $("#prevPage").click(function () {
                pageNum--;
                if (pageNum < 1)
                {
                    pageNum = 1;
                }

                viewModel.getConvos();
            });

        });

    </script>
</head>
<body>
    <!-- Navigation Bar-->
    <ul class="nav">
        <div id="navWrapper">
            <li>
                <a href="/register">Register</a>
            </li>
            <li>
                <a class="active" href="#">Nearby Wellness Professionals</a>
            </li>
        </div>
    </ul>


        <div id="mainWrapper">
            <!-- Add heading for messages-->
            <h1>Conversations</h1>

            <!-- Add list of messages using knockout -->
            <div id="convoContainer">
                <div id="msgBox" data-bind="foreach: conversations">
                    <div class="msgItm" data-bind="event: {click: $parent.openConvo}">
                        <div class="read" data-bind="text: lastMessage.isRead"></div>
                        <div class="sender" data-bind="text: userName"></div>
                        <div class="date" data-bind="text: lastMessage.date"></div>
                        <div class="time" data-bind="text: lastMessage.time"></div>
                        <div class="contents" data-bind="text: lastMessage.messageContent"></div>
                    </div>
                </div>
            </div>


            <div class="noMsgMsg" hidden="hidden"></div>

            <!-- Add message indicating success or failure (hidden) -->
            <p class="status"></p>

            <!-- Add error message if needed -->
            <p class="error" id="jsonError"></p>

            <!-- Add navigation arrows that are only visible if needed -->
            <div>
                <a href="#" class="navLink" id="prevPage">Previous</a>
                <a href="#" class="navLink" id="nextPage">Next</a>
            </div>
        </div>
</body>
</html>
