<?php
session_start();
if (!(isset($_SESSION['username']) && $_SESSION['username'] != ''))
{
	header ("loginPage.html");
}

require 'Handler/facilityHandler.php';

$qry = viewFacility();
$row = mysqli_fetch_assoc($qry);

$facilityID = $row['facilityID'];
$facilityName = $row['facilityName'];
$facilityCapacity= $row['facilityCapacity'];
$facilityRate = $row['facilityRate'];
$facilityAmenities = $row['facilityAmenities'];
$facilityStatus = $row['facilityStatus'];

$path = "img/facility/".$facilityName."";
$facilityImage = glob($path."/*.jpg");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS -->
    <link rel="stylesheet" href="CSS/bootstrap.css">    <!-- All pages -->
    <link rel="stylesheet" href="CSS/dashboard.css">    <!-- For pages that uses side navbar -->
    <link rel="stylesheet" href="CSS/master.css">   <!-- All pages  -->
    <style>
        .container
        {
            margin-top: 50px;
            background-color: white;
            border-radius: 10px;
        }

        .container-2
        {
            padding: 40px;
        }

        label
        {
            padding-top: 20px;
        }

        #facilityUpdateBtn
        {
            float: right;
        }

        #facilityList
        {
            display: grid;
            height: 100%;
            grid-template-areas:
                "images"
                "head"
                "amenities"
                "butts";
            background-color: #ffffff;
            grid-template-rows: 70% auto auto auto;
        }

        .images
        {
            grid-area: images;
            display: grid;
            grid-template-areas:
                "bigImg img1 img2"
                "bigImg img3 img4";
            padding-top: 20px;
        }
            .bigImg
            {
                grid-area: bigImg;
                height: inherit;
            }
            .img1 {grid-area: img1}
            .img2 {grid-area: img2}
            .img3 {grid-area: img3}
            .img4 {grid-area: img4}

        .head
        {
            grid-area: head;
            display: grid;
            grid-template-areas:
                "facilityName facilityName check"
                "description description check"
                "description description check";
            grid-template-columns: 30% 30% 40%;
        }
            .facilityName {grid-area: facilityName}
            .check {grid-area: check}
            .description {grid-area: description}

        .amenities{grid-area: amenities}
        .butts{grid-area: butts}
    </style>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="JavaScript/jquery-3.5.1.min.js" crossorigin="anonymous"></script> <!-- Required by Bootstrap 4 -->
    <script src="JavaScript/popper.min.js" crossorigin="anonymous"></script> <!-- Required by Bootstrap 4 -->
    <script src="JavaScript/bootstrap.bundle.js" crossorigin="anonymous"></script> <!-- Required by Bootstrap 4 -->
    <script src="JavaScript/sweetalert2.all.min.js" crossorigin="anonymous"></script> <!-- SweetAlert2 js -->
    <script src="https://kit.fontawesome.com/fea17f5e62.js" crossorigin="anonymous"></script> <!-- Icon CDN -->

    <title>Facility Management - Marina BookIt</title>
</head>
<body>
<div class="flex-container naviBar sticky-top" style="padding: 10px">
    <div>
        <img src="img/logo.png" id="logo">
    </div><?php
	if ($_SESSION['userType'] == 1) //Admin
	{
		echo '
            <div class="dropdown d1">
                <button class="btn btn-outline-dark" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-align: left">
                    Menu <span class="fas fa-angle-down" style="right: -90px; position: relative;"></span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="Dashboard.php">Home</a>
                    <div class="dropdown-divider"></div>
                    <h6 class="dropdown-header">Booking Management</h6>    
                        <a class="dropdown-item" href="#">Pending booking</a>
                    <div class="dropdown-divider"></div>
                    
                    <h6 class="dropdown-header">Staff Management</h6>
                        <a class="dropdown-item" href="listOfStaff.php">List of staff</a>
                        <a class="dropdown-item" href="registerStaff.php">Register staff</a>
                    <div class="dropdown-divider"></div>
                    
                    <h6 class="dropdown-header">Facility Management</h6>
                        <a class="dropdown-item active" href="listOfFacility.php">List of facility</a>
                        <a class="dropdown-item" href="registerFacility.php">Add facility</a>
                </div>
            </div>
        ';
	}

    elseif ($_SESSION['userType'] == 2) //Staff
	{
		echo '
            <div class="dropdown d1">
                <button class="btn btn-outline-dark" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-align: left">
                    Menu <span class="fas fa-angle-down" style="right: -90px; position: relative;"></span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="Dashboard.php">Home</a>
                    
                    <div class="dropdown-divider"></div>
                    
                    <h6 class="dropdown-header">Booking Management</h6>    
                    <a class="dropdown-item" href="#">List of booking</a>
                    
                    <div class="dropdown-divider"></div>
                    
                    <h6 class="dropdown-header">Facility Management</h6>
                    <a class="dropdown-item" href="listOfFacility.php">List of facility</a>
                    
                </div>
            </div>
        ';
	}

    elseif ($_SESSION['userType'] == 3) //Member
	{
		echo '
            <div class="dropdown d1">
                <button class="btn btn-outline-dark" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Menu <i class="fas fa-angle-down"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Booking Management</a>
                    <a class="dropdown-item" href="#">Booking Management</a>
                    <a class="dropdown-item" href="#">Booking Management</a>
                </div>
            </div>
        ';
	}

	?>

    <div class="dropdown">
        <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<?php echo $_SESSION['userName'] ?>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="ProfileUser.php">View profile</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Sign out</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="container-2">
        <h3 style="text-align: center">Facility Information - <?= $facilityName ?></h3><?php
	    if ($_SESSION['userType'] == 1)
        {
            echo '
                <p style="text-align: center">Edit or update information of a room or facility</p>
            ';
        }

	    elseif ($_SESSION['userType'] == 2)
        {
	        echo '
                <p style="text-align: center">Information of a room or facility</p>
            ';
        }
         ?>
        <hr id="hr1"><?php

        if ($_SESSION['userType'] == 1)
        {
        echo '
            <div class="row">
                <div class="col-3"></div>
                <div class="col-6">
                    <button class="btn btn-dark" data-toggle="button" aria-pressed="false" onclick="editFunction()">Edit</button>
                </div>
                <div class="col-3"></div>
            </div>
        ';
        }

        ?>
        <form class="form-group" method="post" action="Handler/eventListener.php">
            <div class="row">
                <div class="col-3"></div>
                <div class="col-6">

                    <br>
                    <label for="facilityID">ID:</label>
                    <input class="form-control" name="facilityID" id="facilityID" value="<?= $facilityID ?>" readonly>

                    <label for="facilityName">Name:</label>
                    <input class="form-control" name="facilityName" id="facilityName" value="<?= $facilityName ?>" readonly>

                    <label for="facilityCapacity">Capacity:</label>
                    <input class="form-control" name="facilityCapacity" id="facilityCapacity" value="<?= $facilityCapacity ?>" readonly>

                    <label for="facilityRate">Rate per hour:</label>
                    <input class="form-control" name="facilityRate" id="facilityRate" value="<?= $facilityRate ?>" readonly>

                    <label for="facilityAmenities">Amenities:</label>
                    <input class="form-control" name="facilityAmenities" id="facilityAmenities" value="<?= $facilityAmenities ?>" readonly><?php

	                if ($_SESSION['userType'] == 1)
                    {
	                    echo '
                            <label for="facilityStatus">Status:</label>
                            <input class="form-control" name="facilityStatus" id="facilityStatus" value='.$facilityStatus.' readonly>
                        ';
                    }

	                elseif ($_SESSION['userType'] == 2)
	                {
		                echo '
                            <label for="facilityStatus">Status:</label>
                            <input class="form-control" name="facilityStatus" id="facilityStatus" value='.$facilityStatus.'>
                        ';
	                }
                    ?>

                    <br><?php

                    if ($_SESSION['userType'] == 1)
                    {
                        echo '
                            <div class="butt">
                                <button type="submit" class="btn btn-danger" id="facilityDeleteBtn" name="facilityDeleteBtn">Delete</button>
                                <button type="submit" class="btn btn-success" id="facilityUpdateBtn" name="facilityUpdateBtn">Save</button>
                            </div>
                        ';
                    }

	                elseif ($_SESSION['userType'] == 2)
	                {
		                echo '
                            <div class="butt">
                                <button type="submit" class="btn btn-success" id="facilityUpdateBtn" name="facilityUpdateBtn">Save</button>
                            </div>
                        ';
	                }

                    elseif ($_SESSION['userType'] == 3)
                    {
	                    echo '
                            <div class="butt">
                                <button type="submit" class="btn btn-primary" id="facilityUpdateBtn" name="#">Book</button>
                            </div>
                        ';
                    }
                    ?>
                </div>
                <div class="col-3"></div>
            </div>
        </form>
    </div>
</div>

<div class="container" id="facilityList">
    <div class="images">
        <div class="bigImg" style="background-image: url('img/facility/<?= $facilityName ?>/1.jpg'); background-size: cover; border-radius: 30px 0 0 30px">

        </div>

        <div class="img1" style="background-image: url('img/facility/<?= $facilityName ?>/2.jpg'); background-size: cover;">

        </div>

        <div class="img2" style="background-image: url('img/facility/<?= $facilityName ?>/3.jpg'); background-size: cover; border-radius: 0 30px 0 0">

        </div>

        <div class="img3" style="background-image: url('img/facility/<?= $facilityName ?>/4.jpg'); background-size: cover;">

        </div>

        <div class="img4" style="background-image: url('img/facility/<?= $facilityName ?>/5.jpg'); background-size: cover; border-radius: 0 0 30px 0">

        </div>
    </div>

    <div class="head">
        <div class="facilityName" style="padding: 20px">
            <h3><?= $facilityName ?></h3>
            <h5>Lorem · Ipsum · Sit · Amet</h5>
            <hr>
        </div>
        <div class="check" style="padding: 20px">
            <div class="box" style="width: inherit; border: solid 1px; border-color: gray; border-radius: 20px">
                <div style="padding-left: 20px; padding-right: 20px">
                    <form class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="dateStart">CHECK-IN</label>
                                    <input type="date" class="form-control" id="dateStart" name="dateStart">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="dateEnd">CHECK-OUT</label>
                                    <input type="date" class="form-control" id="dateEnd" name="dateEnd">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-outline-dark btn-block">Check availability</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="description" style="padding: 20px">
            <p>At 500m above sea-level, our place in the hills is 5-6 degrees cooler than and only 40 minutes away from the Klang Valley.
                The treehouse, crafted from recycled materials, nestles among some trees on the site with beautiful views of the adjacent valley.
                Enjoy a restful night among the sound of trees swaying in the breeze, reminiscent of a wooden boat on the seas.
                Advantageous location for bird watching, relaxing, reading, spending time with your special loved-one or writing your next masterpiece.</p>
        </div>
    </div>
</div>

<div class="container" style="max-height: 1%"></div>


<!-- Local JavaScript -->
<script>
    function editFunction()
    {
        document.getElementById('facilityName').readOnly = document.getElementById('facilityName').readOnly !== true;
        document.getElementById('facilityCapacity').readOnly = document.getElementById('facilityCapacity').readOnly !== true;
        document.getElementById('facilityRate').readOnly = document.getElementById('facilityRate').readOnly !== true;
        document.getElementById('facilityAmenities').readOnly = document.getElementById('facilityAmenities').readOnly !== true;
        document.getElementById('facilityStatus').readOnly = document.getElementById('facilityStatus').readOnly !== true;

        if (document.getElementById("hr1").style.borderColor !== "red")
        {
            document.getElementById("hr1").style.borderColor = "red";
        }

        else
        {
            document.getElementById("hr1").style.borderColor = "rgba(0, 0, 0, 0.1)";
        }
    }
</script>
<!-- Optional CDN -->

</body>
</html>