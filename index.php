<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Road 1 Pharmacy</title>
    <link rel="icon" href="img/icon copy.png" type="image/x-icon" />
    <link rel="stylesheet" href="style2.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    </link>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Optional Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="position:fixed; width:100%; height:50px; padding:none; z-index:3;">
        <div class="navbar-title">
            <img src="img/IMG_5789__1_-removebg-preview.png" class="logo-image-navbar h1" alt="logo">
            <a class="navbar-brand" href="#">Road 1 Pharmacy</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav" style="margin:none;">
            <ul class="navbar-nav navbar-links">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li>
                    <a class="nav-link" onclick="medicineDisplay()">Medicines</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#faq">FAQ</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid" style="display: none;" id="med_list">

    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        var currentSearchTerm = '';

        function medicineDisplay() {
            var med_list = document.getElementById("med_list");
            if (med_list.style.display === "block") {
                med_list.style.display = "none";
            } else {
                loadPage(1); // Load the first page initially
                med_list.style.display = "block";
            }
        }

        function loadPage(page, search = '') {
            $.ajax({
                url: 'med_list2.php',
                method: 'GET',
                data: {
                    page: page,
                    search: search
                },
                success: function(response) {
                    $('#med_list').html(response);
                }
            });
        }

        // Handle pagination link clicks
        $(document).on('click', '.page-link', function(event) {
            event.preventDefault();
            var page = $(this).data('page');
            loadPage(page, currentSearchTerm);
        });

        // Handle go button click
        $(document).on('click', '#goButton', function(event) {
            event.preventDefault();
            var page = $('#gotoPage').val();
            if (page >= 1 && page <= $(this).data('totalPages')) {
                loadPage(page, currentSearchTerm);
            } else {
                alert('Please enter a valid page number between 1 and ' + $(this).data('totalPages'));
            }
        });

        // Handle form submission
        $(document).on('submit', '#searchForm', function(event) {
            event.preventDefault();
            currentSearchTerm = $('input[name="search"]').val();
            loadPage(1, currentSearchTerm); // Load the first page with search term
        });
    </script>
    <div class="carouselimg">
        <div class="custom-shape-divider-bottom-1717314319">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
            </svg>
        </div>
        <div class="head-cont">
            <div class="headings">
                <h1 class="quote1">Your One Stop Healthcare </h1>
                <h1 class="quote2">Pharmacy</h1>
            </div>
        </div>
        <div id="carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100 carousel-img1" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100 carousel-img2" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100 carousel-img3" alt="Third slide">
                </div>
            </div>

        </div>
    </div>
    </div>
    <div class="frontpage">
        <div class="hexagon1 hex">
            <img src="img\pngwingright.com.png" alt="">
        </div>
        <div class="hexagon2 hex">
            <img src="img\pngwingleft.com.png" alt="">
        </div>
        <div class="section1">
            <div class="about-us1" id="about">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 col-md-8 col-sm-8 description">
                            <h5 class="text-danger" id="aboutus">About Us</h5>
                            <h2>We Are The Best Pharmacy</h2>
                            <p>The Mission of Road 1 Pharmacy is to give the best care to patients.
                                We aim to provide top-notch pharmaceutical services,
                                ensuring everyone gets the right medicine and advice they need.
                                By focusing on quality and care,
                                We hope to make a positive difference in people's health and lives.</p>
                        </div>
                        <div class="col-lg-3 col-md-5 col-sm-6">
                            <div class="card bg-white">
                                <div class="card-body box firstboxes bg-white">
                                    <a class="faq-icon"><i class="bi bi-clipboard-check text-danger"></i></a>
                                    <h5 class="card-title">See if Drug is available</h5>
                                    <p class="card-text">It easy to find out if a certain Drug is available. All you need to know is the name and the correct spelling of the medicine you are looking for</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-5 col-sm-6">
                            <div class="card">
                                <div class="card-body box firstboxes">
                                    <a class="faq-icon"><i class="bi bi-stack text-danger"></i></a>
                                    <h5 class="card-title">Check if the Medicine have stocks</h5>
                                    <p class="card-text">Ask if a specific medicine has stock by messaging the chatbot on the lower right part</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <center class="captions">
                <h2>The Dream of Road 1 Pharmacy</h2>
                <p>Provide the best pharmaceutical services for patients.</p>
            </center>
            <div class="about-us2">
                <div class="custom-shape-divider-bottom-1717300047">
                    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                        <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
                    </svg>
                </div>
                <div class="contiainer-fluid">
                    <div class="row justify-content-center">
                        <div class="card-group col-lg-8 col-md-12 col-sm-12 users">
                            <div class="card ">
                                <div class="card-body border">
                                    <i class="bi bi-person-circle text-danger"></i>
                                    <h5 class="card-title">MENITA BORBON</h5>
                                    <p class="card-text">Pharmacist/Purchaser</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body border">
                                    <i class="bi bi-person-circle text-danger"></i>
                                    <h5 class="card-title">RONALDO BORBON</h5>
                                    <p class="card-text">Proprietor</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body border">
                                    <i class="bi bi-person-circle text-danger"></i>
                                    <h5 class="card-title">CHENG CAGADAS</h5>
                                    <p class="card-text">Drugstore Clerk</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body border">
                                    <i class="bi bi-person-circle text-danger"></i>
                                    <h5 class="card-title">JULIET TAROMA</h5>
                                    <p class="card-text">Drugstore Clerk</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section2" id="faq">
            <center class="captions">
                <h2>We Give The Best Products</h2>
            </center>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-2 col-md-3 col-sm-6 ">
                        <div class="card " style=" box-shadow: 0px 0px 10px 2px #888888;  margin-bottom:30px; height:250px;">
                            <div class="card-body justify-content-center ">
                                <center><i class="fa-solid fa-tablets text-danger products"></i>
                                <h5 class="card-title">VITAMINS</h5>
                                <button class="card-button" onclick="vitDisplay()">View more</button>
                                <div class="container-fluid" style="display: none;" id="vit_list">
                                    <?php include 'vit_list.php'; ?>
                                </div>
                                </center>
                                <script>
                                    function vitDisplay() {
                                        var vit_list = document.getElementById("vit_list");
                                        var displayValue = vit_list.style.display;

                                        if (displayValue === "block") {
                                            vit_list.style.display = "none";
                                        } else {
                                            vit_list.style.display = "block";
                                            loadVitList(1); // Load the first page by default when showing the list
                                        }
                                    }

                                    function loadVitList(page) {
                                        $.ajax({
                                            url: 'vit_list.php',
                                            type: 'GET',
                                            data: {
                                                page: page
                                            },
                                            success: function(response) {
                                                $('#vit_list').html(response);
                                                updatePagination();
                                            }
                                        });
                                    }

                                    function updatePagination() {
                                        document.querySelectorAll('.pagination a').forEach(function(element) {
                                            element.addEventListener('click', function(event) {
                                                event.preventDefault();
                                                var page = this.getAttribute('data-page');
                                                loadVitList(page);
                                            });
                                        });
                                    }

                                    $(document).on('click', '.pagination a', function(e) {
                                        e.preventDefault();
                                        var page = $(this).attr('data-page');
                                        loadVitList(page);
                                    });

                                    document.getElementById('goButtonV').addEventListener('click', function() {
                                        var page = document.getElementById('gotoPageV').value;
                                        if (page >= 1 && page <= <?php echo $totalPages; ?>) {
                                            loadVitList(page);
                                        } else {
                                            alert('Please enter a valid page number between 1 and <?php echo $totalPages; ?>');
                                        }
                                    });
                                </script>
                                <div class="ieffect"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <div class="card " style=" box-shadow: 0px 0px 10px 2px #888888;  margin-bottom:30px; height:250px;">
                            <div class="card-body ">
                                <center><i class="fa-solid fa-virus text-danger products "></i>
                                <h5 class="card-title">FLU REMEDIES</h5>
                                <button class="card-button" onclick="fluDisplay()">View more</button>
                                <div class="container-fluid" style="display: none;" id="flu_list">
                                    <?php include 'flu_list.php'; ?>
                                </div>
                                </center>
                                <script>
                                    function fluDisplay() {
                                        var flu_list = document.getElementById("flu_list");
                                        var displayValue = flu_list.style.display;

                                        if (displayValue === "block") {
                                            flu_list.style.display = "none";
                                        } else {
                                            flu_list.style.display = "block";
                                            loadFluList(1); // Load the first page by default when showing the list
                                        }
                                    }

                                    function loadFluList(page) {
                                        $.ajax({
                                            url: 'flu_list.php',
                                            type: 'GET',
                                            data: {
                                                page: page
                                            },
                                            success: function(response) {
                                                $('#flu_list').html(response);
                                                updatePagination();
                                            }
                                        });
                                    }

                                    function updatePagination() {
                                        document.querySelectorAll('.pagination a').forEach(function(element) {
                                            element.addEventListener('click', function(event) {
                                                event.preventDefault();
                                                var page = this.getAttribute('data-page');
                                                loadFluList(page);
                                            });
                                        });
                                    }

                                    $(document).on('click', '.pagination a', function(e) {
                                        e.preventDefault();
                                        var page = $(this).attr('data-page');
                                        loadFluList(page);
                                    });

                                    document.getElementById('goButtonF').addEventListener('click', function() {
                                        var page = document.getElementById('gotoPageF').value;
                                        if (page >= 1 && page <= <?php echo $totalPages; ?>) {
                                            loadFluList(page);
                                        } else {
                                            alert('Please enter a valid page number between 1 and <?php echo $totalPages; ?>');
                                        }
                                    });
                                </script>
                                <div class="ieffect"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <div class="card " style=" box-shadow: 0px 0px 10px 2px #888888;  margin-bottom:30px; height:250px;">
                            <div class="card-body ">
                                <center><i class="fa-solid fa-child text-danger products"></i>
                                <h5 class="card-title">KIDS NUTRITION</h5>
                                <button class="card-button" onclick="kidDisplay()">View more</button>
                                <div class="container-fluid" style="display: none;" id="kid_list">
                                    <?php include 'kid_list.php'; ?>
                                </div>
                                </center>
                                <script>
                                    function kidDisplay() {
                                        var kid_list = document.getElementById("kid_list");
                                        var displayValue = kid_list.style.display;

                                        if (displayValue === "block") {
                                            kid_list.style.display = "none";
                                        } else {
                                            kid_list.style.display = "block";
                                            loadKidList(1); // Load the first page by default when showing the list
                                        }
                                    }

                                    function loadKidList(page) {
                                        $.ajax({
                                            url: 'kid_list.php',
                                            type: 'GET',
                                            data: {
                                                page: page
                                            },
                                            success: function(response) {
                                                $('#kid_list').html(response);
                                                updatePagination();
                                            }
                                        });
                                    }

                                    function updatePagination() {
                                        document.querySelectorAll('.pagination a').forEach(function(element) {
                                            element.addEventListener('click', function(event) {
                                                event.preventDefault();
                                                var page = this.getAttribute('data-page');
                                                loadKidList(page);
                                            });
                                        });
                                    }

                                    $(document).on('click', '.pagination a', function(e) {
                                        e.preventDefault();
                                        var page = $(this).attr('data-page');
                                        loadKidList(page);
                                    });

                                    document.getElementById('goButtonK').addEventListener('click', function() {
                                        var page = document.getElementById('gotoPageK').value;
                                        if (page >= 1 && page <= <?php echo $totalPages; ?>) {
                                            loadKidList(page);
                                        } else {
                                            alert('Please enter a valid page number between 1 and <?php echo $totalPages; ?>');
                                        }
                                    });
                                </script>
                                <div class="ieffect"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <div class="card " style=" box-shadow: 0px 0px 10px 2px #888888; margin-bottom:30px; height:250px;">
                            <div class="card-body">
                                <center><i class="fa-solid fa-baby text-danger products"></i>
                                <h5 class="card-title">ABSORBENT HYGIENE PRODUCTS</h5>
                                <button class="card-button" onclick="diaDisplay()">View more</button>
                                <div class="container-fluid" style="display: none;" id="dia_list">
                                    <?php include 'dia_list.php'; ?>
                                </div>
                                </center>
                                <script>
                                    function diaDisplay() {
                                        var dia_list = document.getElementById("dia_list");
                                        var displayValue = dia_list.style.display;

                                        if (displayValue === "block") {
                                            dia_list.style.display = "none";
                                        } else {
                                            dia_list.style.display = "block";
                                            loadDiaList(1); // Load the first page by default when showing the list
                                        }
                                    }

                                    function loadDiaList(page) {
                                        $.ajax({
                                            url: 'dia_list.php',
                                            type: 'GET',
                                            data: {
                                                page: page
                                            },
                                            success: function(response) {
                                                $('#dia_list').html(response);
                                                updatePagination();
                                            }
                                        });
                                    }

                                    function updatePagination() {
                                        document.querySelectorAll('.pagination a').forEach(function(element) {
                                            element.addEventListener('click', function(event) {
                                                event.preventDefault();
                                                var page = this.getAttribute('data-page');
                                                loadDiaList(page);
                                            });
                                        });
                                    }

                                    $(document).on('click', '.pagination a', function(e) {
                                        e.preventDefault();
                                        var page = $(this).attr('data-page');
                                        loadDiaList(page);
                                    });

                                    document.getElementById('goButtonD').addEventListener('click', function() {
                                        var page = document.getElementById('gotoPageD').value;
                                        if (page >= 1 && page <= <?php echo $totalPages; ?>) {
                                            loadDiaList(page);
                                        } else {
                                            alert('Please enter a valid page number between 1 and <?php echo $totalPages; ?>');
                                        }
                                    });
                                </script>
                                <div class="ieffect"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="container-fluid footer">
            <div class="row inner-footer justify-content-evenly">
                <div class=" col-lg-2 col-md-3 col-sm-12 footer-header ">
                    <div class="footer-h1 ">
                        <img src="img/IMG_5789__1_-removebg-preview.png" class="footer-logo" alt="logo">
                        <h1>Road 1 Pharmacy</h1>
                    </div>
                    <h3>Your one stop healthcare pharmacy</h3>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 footer-maps">
                    <h2>Location:</h2>
                    <p style="padding: 10px 10px 0 10px;">
                        <iframe class="gmaps" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3856.6919732709853!2d121.07851119999998!3d14.8425359!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397af55773a7a17%3A0xfe00da48e36d6f5c!2sRoad%201%20Pharmacy%20Convenience%20Store!5e0!3m2!1sfil!2sph!4v1715661497604!5m2!1sfil!2sph" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                    <p><a href="https://maps.app.goo.gl/T6Y8MSbjgEYKvnnf7">Unit 2, Ipo Road cor, Road 1 , Minuyan Proper, City of San Jose del Monte, Bulacan</a></p>
                    </p>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12 footer-contacts">
                    <h2>Contact us:</h2>
                    <p style="padding: 10px 10px 0 10px;">
                        Cellphone no. : #09157901029 <br>
                    </p>
                    <p style="padding: 0px 50px 0 10px;">
                        Email: <br>
                        <a href="mailto:road1pharmacy@gmail.com"> road1pharmacy@gmail.com</a>
                    </p>
                </div>
            </div>
        </footer>
        <div class="ai-chatbot">
            <a class="chatbot" onclick="toggleChatbox()">
                <div class="chatbot-circle">
                    <i class="bi bi-chat-dots-fill text-light chaticon" title="Hi"></i>
                </div>
            </a>
        </div>

        <div id="chatbox" style="display: none;">
            <a onclick="closeChatBox()"><i class="fas fa-times" id="chat_close"></i></a>
            <?php
            include 'database/config.php';

            $sql = "SELECT greetings FROM training_greetings";
            $result = mysqli_query($conn, $sql);
            $greet = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $greet[] = $row['greetings'];
            }
            $randomIndex = array_rand($greet);
            $randomValue = $greet[$randomIndex];


            ?>


            <div class="chat-container">
                <h2>Alternative Medicine Chatbot</h2>
                <div class="chat-messages" id="chat-messages">
                    <div class="bot-message"><?php echo $randomValue; ?></div>
                </div>
                <form id="chat-form">
                    <input type="text" id="user-input" placeholder="Type your message...">
                    <button type="submit">Send</button>
                </form>
            </div>

            <script>
                document.getElementById('chat-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                    sendMessage();
                    document.getElementById("user-input").value = "";
                });

                function sendMessage() {
                    var userInput = document.getElementById('user-input').value;
                    appendMessage('user', userInput);

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'chatbotkuno/index.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            var botResponse = xhr.responseText;
                            appendMessage('bot', botResponse);
                        }
                    };
                    xhr.send('input=' + userInput);
                }

                function appendMessage(sender, message) {
                    var chatMessages = document.getElementById('chat-messages');
                    var messageDiv = document.createElement('div');
                    messageDiv.className = sender + '-message';
                    messageDiv.textContent = message;
                    chatMessages.appendChild(messageDiv);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            </script>
        </div>
        <script>
            function toggleChatbox() {
                var chatbox = document.getElementById("chatbox");
                chatbox.style.display = chatbox.style.display === "none" ? "block" : "none";
                // console.log("hi");
            }

            function closeChatBox() {
                var chatbox = document.getElementById("chatbox");
                chatbox.style.display = chatbox.style.display === "none" ? "block" : "none";
            }
        </script>

</body>

</html>