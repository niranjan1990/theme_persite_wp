<?php /* Template Name: Search Page */ ?>
 <?php get_header('noad'); ?>

<div id="content-left" class="col-md-12">
<div class="inner">

 
<div id="loading-search"><span>Searching...</span><img src="<?php bloginfo('template_url'); ?>/images/Loading-Animation.gif" alt="Be patient..." /></div>
  <!-- <div class="se-pre-con"></div> -->

<script>
    (function() {
        var cx = <?php $search=get_option( 'search_var' ); echo "'".$search."'"; ?>;
        var gcse = document.createElement('script');
        gcse.type = 'text/javascript';
        gcse.async = true;
        gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//cse.google.com/cse.js?cx=' + cx;
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(gcse, s);
    })();
</script>
<div id="content-search">
    <gcse:searchresults-only></gcse:searchresults-only>
</div>

</div>
</div>
<?php get_footer(); ?>
<script>
    $(document).ready(function() {
        // when user browses to page
        $('#content-search').css('opacity : 0.1');
        $('#loading-search').show();
        // then when the #content div has loaded
            if ($('#content-search').length) {
                $('#loading-search').hide();
                $('#content-search').css('opacity : 1.0');
               }
    });
</script> 
