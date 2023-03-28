<?php /* Template Name:  admin editorial review  */
echo "<!-- Template Name: admineditorialreview.php -->";
?>

<?php

/**
 * Use an HTML form to create a new entry in the
 * editorial_reviews  table.
 *
 */


 ob_start();
 session_start();
 require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
 require $_SERVER['DOCUMENT_ROOT'] . '/wp-reviewconfig.php';
 mysql_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD);
 mysql_select_db(DB_RNAME);
 require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';


 // Report all PHP errors
 error_reporting(-1);


$recordAdded = false;
if (isset($_POST['submit']))
{

		$con = mysqli_connect(DB_RHOST,DB_RUSER,DB_RPASSWORD,DB_RNAME) or die("Some error occurred during connection " . mysqli_error($con));

    $product_name  = $_POST['editorial_product_name'];
    $last_space = strrpos($product_name, ' ');
    $product_id = substr($product_name, $last_space);
    $onlyProduct_name = substr($product_name, 0, $last_space);

    $onlyProduct_id = substr($product_id, strpos($product_id, ":") + 1);


		$_SESSION['editorial_review_title']=$_POST['editorial_review_title'];
		$_SESSION['editorial_review_excerpt']=$_POST['editorial_review_excerpt'];
		$_SESSION['editorial_review_url']=$_POST['editorial_review_url'];
		$_SESSION['editorial_review_pros']=$_POST['editorial_review_pros'];
		$_SESSION['editorial_review_cons']=$_POST['editorial_review_cons'];
		$_SESSION['date']=$_POST['date'];
		$_SESSION['author']=$_POST['author'];
		$_SESSION['editorial_review_rating']=$_POST['editorial_review_rating'];

    $_SESSION['editorial_date_review_published']=$_POST['editorial_date_review_published'];
    $published_date = $_SESSION['editorial_date_review_published'];

    $file = $_FILES['image']['tmp_name'];
    if(!isset($file)) {
      // echo "Please select an image.";
   } else {
     $image = addslashes(file_get_contents($_FILES['image']['tmp_name'])); //SQL Injection defence!
     $imgContent = addslashes($_FILES['image']['name']);
     //echo '$image'.$image;
       //echo '$imgContent'.$imgContent;
   }

    //  $image = $_FILES['image']['tmp_name'];
    //  $imgContent = addslashes(file_get_contents($image));


		//$_SESSION['editorial_image']=$_POST['editorial_image'];


		if(isset($_COOKIE['bb_userid']))
		{
			$sqlinsert = mysql_query("INSERT INTO editorial_reviews (ProductID, ChannelID, CategoryID, excerpt, title, pros, cons, author, rating, url, product_name, date_created, editorial_image, editorial_image_name, date_review_published)
			VALUES ('".$onlyProduct_id."','".$_SESSION['channelid']."','null', '".$_SESSION['editorial_review_excerpt']."','".$_SESSION['editorial_review_title']."','".$_SESSION['editorial_review_pros']."', '".$_SESSION['editorial_review_cons']."', '".$_COOKIE['bb_username']."', '".$_SESSION['editorial_review_rating']."', '".$_SESSION['editorial_review_url']."',  '".$onlyProduct_name."',  '".date('Y-m-d H:i:s')."','".$image."', '".$imgContent."', STR_TO_DATE('$published_date', '%m/%d/%Y'))");
      $recordAdded = true;
      echo '<span style="font-size:30px;">' .$onlyProduct_name.  ' review successfully added.</span>';
		}


		if($sqlinsert->execute()){
      echo "editorial review submitted";

    }else{
      echo "show errors";

    }




}
?>
<?php
echo $recordAdded;
if($recordAdded > 1)  { ?>
  <blockquote> <?php echo $_POST['editorial_product_name']; ?> successfully added.</blockquote>
<?php
}
?>




<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="js/typeahead.bundle.js"></script>

</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h1 class="page-header text-center">Add Editorial Review</h1>
      <form class="form-horizontal" role="form" method="post" action="site_editorial_review_form.php">
        <div class="form-group">
          <label for="editorial_product_name" class="col-sm-2 control-label">Search Product: </label>
          <div class="col-sm-10">
            <input type="text" class="typehead" id="editorial_product_name" name="editorial_product_name" placeholder="Product Name" value="<?php echo htmlspecialchars($_POST['editorial_product_name']); ?>">
          </div>
        </div>
      <!--  <div class="form-group">
          <div class="col-sm-12">
            <a class="btn btn-primary" href="/prd_id.aspx" role="button" id="product_url">Product URL</a>
          </div>
        </div> -->
        <div class="form-group">
          <label for="editorial_review_url" class="col-sm-2 control-label">URL</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="editorial_review_url" name="editorial_review_url" placeholder="URL" value="<?php echo htmlspecialchars($_POST['editorial_review_url']); ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="editorial_review_title" class="col-sm-2 control-label">Title</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="editorial_review_title" name="editorial_review_title" placeholder="Title" value="<?php echo htmlspecialchars($_POST['editorial_review_title']); ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="editorial_review_excerpt" class="col-sm-2 control-label">Excerpt</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="editorial_review_excerpt" name="editorial_review_excerpt" placeholder="Excerpt" value="<?php echo htmlspecialchars($_POST['editorial_review_excerpt']); ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="editorial_review_pros" class="col-sm-2 control-label">Pros</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="editorial_review_pros" name="editorial_review_pros" placeholder="Pros" value="<?php echo htmlspecialchars($_POST['editorial_review_pros']); ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="editorial_review_cons" class="col-sm-2 control-label">Cons</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="editorial_review_cons" name="editorial_review_cons" placeholder="Cons" value="<?php echo htmlspecialchars($_POST['editorial_review_cons']); ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="editorial_review_rating" class="col-sm-2 control-label">Rating</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="editorial_review_rating" name="editorial_review_rating" placeholder="Rating" value="<?php echo htmlspecialchars($_POST['editorial_review_rating']); ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="editorial_review_rating" class="col-sm-2 control-label">Date Review Published</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="editorial_date_review_published" name="editorial_date_review_published" placeholder="" value="<?php echo htmlspecialchars($_POST['editorial_date_review_published']); ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="editorial_review_rating" class="col-sm-2 control-label">Upload Image</label>
            <div class="col-sm-10">
            <input name="image" type="file" class="file">
          </div>
      </div>

      <div class="form-group">
  			<div class="col-sm-10 col-sm-offset-2">
  				<input id="submit" name="submit" type="submit" value="Submit" class="btn btn-primary">
  			</div>
     </div>
</form>
</div>
</div>
</div>

<style type="text/css">
.typeahead,
.tt-query,
.tt-hint {
  width: 396px;
  height: 30px;
  padding: 8px 12px;
  font-size: 24px;
  line-height: 30px;
  border: 2px solid #ccc;
  -webkit-border-radius: 8px;
     -moz-border-radius: 8px;
          border-radius: 8px;
  outline: none;
}

.typeahead {
  background-color: #fff;
}

.typeahead:focus {
  border: 2px solid #0097cf;
}

.tt-query {
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.tt-hint {
  color: #999
}

.tt-menu {
  width: 422px;
  margin: 12px 0;
  padding: 8px 0;
  background-color: #fff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  -webkit-border-radius: 8px;
     -moz-border-radius: 8px;
          border-radius: 8px;
  -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
     -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
          box-shadow: 0 5px 10px rgba(0,0,0,.2);
}

.tt-suggestion {
  padding: 3px 20px;
  font-size: 18px;
  line-height: 24px;
}

.tt-suggestion:hover {
  cursor: pointer;
  color: #fff;
  background-color: #0097cf;
}

.tt-suggestion.tt-cursor {
  color: #fff;
  background-color: #0097cf;

}

.tt-suggestion p {
  margin: 0;
}
    </style>

<script>
$(document).ready(function(){
  // Instantiate the Bloodhound suggestion engine
    var source = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('product_id'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url:'/wp-content/themes/site/site_editorial_review_server.php?query=%QUERY',
            wildcard: '%QUERY',
            filter: function (results) {
                // Map the remote source JSON array to a JavaScript object array
                return $.map(results, function (result) {
                    return {
                        value: result.product_name + ' ProductID:' + result.productid,
                        product_id : result.productid
                    };
                });
            }
        }
    });
    // Initialize the Bloodhound suggestion engine
    source.initialize();


    $('#editorial_product_name').typeahead({
      hint: false,
      highlight: false,
      minLength: 0
    }, {
           display: 'value',
           source: source.ttAdapter(),
           limit:10000000
        });

    $('#editorial_product_name').change(function() {
      var productName = $('#editorial_product_name').val();
      var productid= substr(productName, 0, index(productName, ':'));
      $('#product_url').val() = productid;
    });
});
</script>
</body>
</html>
