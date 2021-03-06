<?php
session_start();
$currentpage = 'home';
$title = "Mick's Licks";
include ('connection.php');

if (isset($_GET['itemNumber'])) {
  if (isset($_SESSION['useremail'])) {
    $email = $_SESSION['useremail'];
    $item = $_GET['itemNumber'];
    $cartquery = "SELECT quantityOrdered FROM SHOPPING_CART
                WHERE USER_ACCOUNT_USEREMAIL = '$email'
                AND RECORD_itemNumber = $item";
    $cart = mysqli_query($link, $cartquery);
    $cartrow = mysqli_fetch_array($cart);
    $row_cnt = $cart->num_rows;

    //If no rows returned, insert into cart. if a row is returned, update quantity ordered
    if ($row_cnt == 0) {
      $addItem = "INSERT INTO SHOPPING_CART (quantityOrdered, RECORD_itemNumber, USER_ACCOUNT_USEREMAIL)
      VALUES (1, '$item', '$email')";
      if ((mysqli_query($link, $addItem)) or die("Error: ".mysqli_error($link))) {
      } else {
        $cartError = true;
      }
    } else {
      $quantity = intval($cartrow['quantityOrdered']);
      $quantity++;
      $incrementItem = "UPDATE SHOPPING_CART SET quantityOrdered = $quantity WHERE USER_ACCOUNT_USEREMAIL = '$email' AND RECORD_itemNumber = $item";
      if ((mysqli_query($link, $incrementItem)) or die("Error: ".mysqli_error($link))) {
      } else {
        $cartError = true;
      }
    }
  } else {
    $login = true;
    echo "<meta http-equiv='refresh' content='0; url=login.php?itemNumber=" . $_GET['itemNumber'] . "'>";
  }
}
include ('header.php');
?>


<?php

if(isset($_GET['privacy'])){
  $_SESSION['privacy']='N';
}

if(isset($_POST['accept']) || isset($_POST['decline'])) {
  unset($_SESSION['privacy']);
}

if (isset($_SESSION['privacy'])) {
  if($_SESSION['privacy']=='N') {
    echo $_SESSION['privacy'] . "
        <body>
        <div class='modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display:block;'>
          <div class='modal-dialog'>
            <div class='modal-content'>
              <div class='modal-header'>
                <h4 class='modal-title' id='myModalLabel'>Our Terms of Use Have Changed</h4>
              </div>
              <div class='modal-body'>
                <p><embed src='https://termsfeed.com/terms-conditions/3fc9f955d476a5bf9ca69d4c91d23b6f' frameborder='100' width='100%' length='400px' height='500px'></p>
              </div>
              <div class='modal-footer'>
                <p>Questions or Concerns, Contact Us <a href='mailto:mick.lick.records@gmail.com?Subject=Terms%20And%20Contions'>Here</a></p>
                <form action='index.php' method='POST'>
                <button type='submit' name='accept' value='accept' class='btn btn-primary' data-dismiss='modal'> Accept <span class='glyphicon glyphicon-ok-circle'></span></button>
                <button type='submit' id='decline' name='decline' value='decline' class='btn btn-default' data-dismiss='modal-body'> Decline <span class='glyphicon glyphicon-ban-circle'></span></button>
                </form>
              </div>
            </div>
          </div>
        </div>
        </body>";
}
}
?>
<?php if (isset($_POST['accept'])) {
  $pQuery = "UPDATE USER_ACCOUNT SET privCheck = 'Y'
  WHERE USEREMAIL = '$email'";
  mysqli_query($link, $pQuery) or die ("Error: ".mysqli_error($link));
  unset($_SESSION['privacy']);
  unset($_SESSION['accept']);
  echo "<script>javascript: alert('Thank you for agreeing to the terms!')</script>";
}

if (isset($_POST['decline'])) {
  $pQuery = "UPDATE USER_ACCOUNT SET privCheck = NULL
  WHERE USEREMAIL = '$email'";
  mysqli_query($link, $pQuery) or die ("Error: ".mysqli_error($link));
  session_destroy();
  echo "<script>javascript: alert('We will need to sign you out!')</script>";
  echo "<script>setTimeout(function() {
    window.location='index.php';
  }, 2000)</script>";
}
?>

<div class='container-fluid'>
<?php

// Check for genre request
if (isset($_GET['genre'])) {
  $genre = $_GET['genre'];
  $query = "select * from RECORD where itemNumber IN
	   (SELECT RECORD_itemNumber from RECORD_CATEGORY where GENRE_genreID IN
     (SELECT genreID from GENRE where genre = '$genre'))";
  $result = mysqli_query($link, $query) or die("Error: ".mysqli_error($link));
} else {
  $query = "select * from RECORD";
  $result = mysqli_query($link, $query) or die("Error: ".mysqli_error($link));
}

if (isset($_GET['addtocart']) && !isset($cartError) && !isset($login)) {
  echo "<p class='alert alert-success'>Record Added!</p>";
}
elseif (isset($cartError)) {
  echo "<p class='alert alert-danger'>Something went wrong...</p>";
}

// Set page title
if (isset($genre))   {
  echo "<h4>$genre Records</h4>";
} else {
  echo "<h4>All Records</h4>";
}
echo "<br>";

// Populate home page with records
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_array($result)) {
          echo "
          <div class='col-sm-3'>
             <article class='col-item'>
              <div class='albumArtwork'>
           			<img src='" . $row['albumArtwork'] . "' alt='Product Image' onerror=" . "this.onerror=null;this.src='../images/records.jpg';" . "height=200 width=200>
                <div class='item-buttons'>
                  <div class='animated fadeInDown'>
                    <button link='" . $row['spotifyLink'] . "' description='" . htmlspecialchars(($row['description']), ENT_QUOTES) . "' releaseDate='" . $row['RELEASEDATE'] .
                    "' title='" . htmlspecialchars(($row['albumTitle']), ENT_QUOTES) . "' artist='" . htmlspecialchars(($row['artist']), ENT_QUOTES) .
                    "' class='more-info btn btn-info' data-toggle='modal' data-target='#myModal'>
                    <span class='glyphicon glyphicon-headphones'></span><p style='display:inline;'>Info</p></button>
                    <a href='index.php?itemNumber=" . $row['itemNumber'] . "&addtocart=true' id='shoppingCart' class='btn btn-info' title='Add to cart'>
                    <span class='glyphicon glyphicon-shopping-cart id='addtocart'></span><p style='display:inline;'>Add</p></a>
                  </div>
                </div>
              </div>
       		<div class='info'>
       				<div class='price-details col-sm-10'>
       					<div class='details'>"
       						. $row['quality'] . "
       					</div>
         					<div style='font-size:16pt'>" . $row['albumTitle'] . "</div>
                   <b>" . $row['artist'] . "</b>
                   <br>
         					<span class='price-new'>" . "$" . $row['PRICE'] . "</span>
                   <br>
                   <br>
         				  </div>
       		      </div>
         	    </article>
          </div>";
  }
} else {
  echo "No records match that genre";
}

?>
</div>

<!-- start of modal window -->
<div class='modal fade' id='myModal'>
<div class='modal-dialog'  style='max-width:400px;'>

    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title' id='titleArtist'>Info</h4>
      </div>
      <div class='modal-body'>
        <span><strong>Release Date: </strong></span><p id='releaseDate'></p>
        <span><strong>Description: </strong></span><p id='description'></p>
        <span><strong>Listen On Spotify: </strong></span>
        <iframe id='link' src='' width='250' height='80' frameborder='0' allowtransparency='true' allow='encrypted-media'></iframe>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
      </div>
    </div>

</div>
</div><!-- end of modal window -->

<?php
 include ('footer.php');
?>
