<?php

$testHeader = "";
$hasTestHeader = false;

if (!empty($_REQUEST['test']))
{
    $hasTestHeader = true;
    $testHeader = $_REQUEST['test'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Nearby Wellness Professionals</title>
    <!-- Import jquery and knockout libraries -->
    <link type="text/css" rel="stylesheet" href="../../../styles/nearby_wellness.css" />
    <link type="text/css" rel="stylesheet" href="../../../styles/navbar.css" />
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
            wellnessPros: ko.observableArray(),

            getWellnessPros: function() {
                var headerName = "test";
                var headerVal = "";
                if (hasTestHeader)
                {
                    
                    headerVal = testHeader;

                }

                $.ajax(
                {
                    url: '../WellnessProfessionals/view/' + pageNum,
                    type: 'GET',
                    dataType: 'json',

                        //Pass in page number we want to see
                        //headers: {"test" : headerVal}
                    beforeSend: function (xhr) { xhr.setRequestHeader(headerName, headerVal);},
                    success: function (jsonResponse)
                    {


                        //Deal with the response
                        if ( jsonResponse.status == "success")
                        {
                            viewModel.wellnessPros.removeAll();
                            for (var i = 0; i < jsonResponse.data.objects.length; i++)
                            {
                                //Convert phone number to good format
                                var phone = jsonResponse.data.objects[i].objectData.contactNumber;
                                if (phone != null)
                                {
                                    phone = "(" + phone.substring(0, 3) + ") " + phone.substring(3, 6) + " - " + phone.substring(6, 10);
                                    jsonResponse.data.objects[i].objectData.contactNumber = phone;
                                }

                                viewModel.wellnessPros.push(jsonResponse.data.objects[i].objectData);
                            }

                            totalPages = Math.ceil(jsonResponse.data.totalFound / 10);

                            //Check for nav arrows
                            showNavArrows();
                        }
                        //status is failure
                        else
                        {
                            $('#jsonError').html(jsonResponse.message);
                            $(".errorMsg").css("display", "inline");
                        }
                    }
                 })
            }
            };

        //Add method to determine if navigation arrows are needed
        function showNavArrows()
        {
            //Show/hide next nav link
            if (pageNum < totalPages)
            {
                $("#next").show();
            }
            else
            {
                $("#next").hide();
            }

            //Show/hide prev nav link
            if (pageNum > 1)
            {
                $("#prev").show();
            }
            else
            {
                $("#prev").hide();
            }

        }

        //Functionality to perform once the page loads
        $(function () {
            ko.applyBindings(viewModel);
            viewModel.getWellnessPros();
            $("#next").hide();
            $("#prev").hide();
            
            //Click handler for next
            $("#next").click(function () {
                pageNum++;
                if (pageNum > totalPages)
                {
                    pageNum = totalPages;
                }

                viewModel.getWellnessPros();
            });

            //Click handler for prev
            $("#prev").click(function () {
                pageNum--;
                if (pageNum < 1)
                {
                    pageNum = 1;
                }

                viewModel.getWellnessPros();
            });

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
        <!-- Add heading for page -->
        <h1>Nearby Wellness Professionals</h1>

        <!-- Add list of wellness professionals using knockout -->
            <div id="wellnessWrapper" data-bind="foreach: wellnessPros">
                <div class="wellnessProfessional">
                    <div class="practiceName" data-bind="text: practiceName"></div>
                    <div class="city" data-bind="text: city"></div>
                    <div class="contactNumber" data-bind="text: contactNumber"></div>
                    <div class="contactEmail"><a target="_blank" data-bind="text: contactEmail, attr:{href:contactEmail}"></a></div>
                    <div class="website"><a target="_blank" data-bind="text:website, attr:{href: website}"></a></div>
                </div>
            </div>
        <!-- Add message indicating success or failure (hidden) -->
        <p class="status"></p>

        <!-- Add error message if needed -->
        <p class="errorMsg" id="jsonError"></p>

        <!-- Add navigation arrows that are only visible if needed -->
        <div>
            <a href="#" class="navLink" id="prev">Previous</a>
            <a href="#" class="navLink" id="next">Next</a>
        </div>
    </div>
</body>
</html>
