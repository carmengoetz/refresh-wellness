<?php
$testHeader = "";
$hasTestHeader = false;
$userSent = "";

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
    <title>Messages</title>
    <!-- Import jquery and knockout libraries, and stylesheets-->
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
            messages: ko.observableArray(),
            
            //recreate the function to work with the json passed in above
            getMsgs: function () {
                var headerName = "test";
                var headerVal = "";
                var userID = sessionStorage.getItem('userID');
                if (hasTestHeader) {
                    headerVal = testHeader;
                }

                $.ajax(
                    {
                        //add userID to URL to know which messages to pull
                        url: '../message/view/' + userID + '/' + pageNum,
                        type: 'GET',
                        dataType: 'json',
                        //Pass in page number we want to see
                        //headers: {"test" : headerVal}
                        beforeSend: function (xhr) { xhr.setRequestHeader(headerName, headerVal); },
                        success: function (jsonResponse) {
                            //Deal with the response

                            /////////////////////validating off passed in userID needs to be changed to//////
                            ///////////////////send userID to backend////////////////////////////////////////

                            if (jsonResponse.status == "success") {
                                viewModel.messages.removeAll();
                                for (var i = 0; i < jsonResponse.data.messages.length; i++)
                                {
                                    viewModel.messages.push(jsonResponse.data.messages[i]);
                                }
                            }
                            //status is failure
                            else {
                                $('#jsonError').html(jsonResponse.message);
                                $(".errorMsg").css("display", "inline");
                            }

                            totalPages = Math.ceil(jsonResponse.data.length / 20);
                        }
                    })
            }
        };

        $(function () {
        ko.applyBindings(viewModel);
        viewModel.getMsgs();
        
        });

    </script>
</head>
<body>
    <!-- Navigation Bar -->
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
        <h1>Messages</h1>

        <!-- Add list of messages using knockout -->
        <div id="convoContainer">

        <!--Will need to move this to be inside of the conversation div.
            WIll only show if a conversation is clicked. Will then call
            showMsgs with the user ID of the selected conversation-->
        <!--I think it will be easier if we just open a new page from what is clicked instead of try to show the
            messages underneath the convorsation-->
        <div class="convoBox" data-bind="foreach: messages">
            <?php if ($userSent) { ?>
            <div class="msgSender">
                <div class="date" data-bind="text: date"></div>
                <div class="time" data-bind="text: time"></div>
                <div class="contents" data-bind="text: messageContent"></div>
            </div>
            <?php } else { ?>
            <div class="msgReceiver">
                <div class="date" data-bind="text: date"></div>
                <div class="time" data-bind="text: time"></div>
                <div class="contents" data-bind="text: messageContent"></div>
            </div>
            <?php } ?>
        </div>

        <div class="noMsgMsg" hidden="hidden"></div>

        <!-- Add message indicating success or failure (hidden) -->
        <p class="status"></p>

        <!-- Add error message if needed -->
        <p class="error" id="jsonError"></p>


    </div>
</body>
</html>
