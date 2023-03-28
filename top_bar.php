<?php include( dirname( __FILE__ ) . '/wp-load.php' );
include(get_template_directory() . "/header_top_bar.php");
?>	
<?php if(isset($_GET['load_jquery']) && $_GET['load_jquery']=='yes'): ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 
<?php endif ?>

<!-- #reviewAppOO7 generated at <?=  date(DATE_RFC2822)?>. -->
