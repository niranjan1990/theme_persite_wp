<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
global $template;

$template = "404";

get_header('noad'); 

include(__DIR__.'/../../../wp-config-extra.php'); 
?>
<style>
#custom-search-input{
    padding: 3px;
    border: solid 1px #E4E4E4;
    border-radius: 6px;
    background-color: #fff;
}

#custom-search-input input{
    border: 0;
    box-shadow: none;
}

#custom-search-input button{
   /* margin: 2px 0 0 0;*/
    background: none;
    box-shadow: none;
    border: 0;
    color: #666666;
  /*  padding: 0 8px 0 10px;*/
    border-left: solid 1px #ccc;
}

#custom-search-input button:hover{
    border: 0;
    box-shadow: none;
    border-left: solid 1px #ccc;
}

#custom-search-input .glyphicon-search{
    font-size: 23px;
}
</style>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" type="text/css" rel="stylesheet">

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">

				<div class="page-content">
					<center>
						<p style='    font-size: 2em;    font-weight: bold;      margin-top: 100px;margin-bottom: 100px;  font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;    line-height: normal;'>Woops! Looks like this page doesn't exist</p>
						<!--<img src="<?php echo get_template_directory_uri()."/images/404.png"; ?>"></center>-->

        <div class="col-md-9" style="float:none;">
            <div id="custom-search-input">
                <div class="input-group col-md-12">
					<form action="/search.html" method="get">
                    <input type="text" name="q" class="form-control input-lg" placeholder="<?php echo $SEARCHTERM404PLACEHOLDER; ?>" style="width: 70%;"/>
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-lg" type="submit">
                            <i class="glyphicon glyphicon-search" ></i>
                        </button>
                    </span>
					</form>
                </div>
            </div>
        </div>
			
<br><br><br>			
						<?php //get_search_form(); ?>
					</center>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- .site-main -->

		<?php //get_sidebar( 'content-bottom' ); ?>

	</div><!-- .content-area -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
